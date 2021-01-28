<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Category;
use App\tag;
use App\Product;
use App\TagProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;



class TagProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index($id, Request $request)
    {
        $tag = Tag::find($id);
        $tag_products = TagProduct::notDelete()->where('tag_id', '=', $id)
            ->sortId($request)->status($request);
        $count_tag_product = 0;
        $view = 0;
        $count_tag_product = $tag_products->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $tag_products = $tag_products->paginate($view);

        // product belong to tag
        $tag_products_array = array();
        foreach ($tag_products as $tag_product) {
            $tag_products_array[] = $tag_product->product_id;
        }
        $products = Product::notDelete()->whereIn('id', $tag_products_array)->get();

        // user
        $user = Auth::guard('admin')->user();
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        return view('admin.tag.product.index', [
            'tag' => $tag,
            'count_tag_product' => $count_tag_product,
            'view' => $view,
            //
            'products' => $products,
            'tag_products' => $tag_products,
            //
            'current_user' => $user,
            // filter
            'sort_id' => $sort_id,
            'status' => $status,
        ]);
    }

    public function doActivate($id, $product_id)
    {
        $tag_product = TagProduct::find($product_id);
        $tag_product->is_actived = 1;
        if ($tag_product->save()) {
            return back()->with('success', 'Item #' . $tag_product->id . ' has been activated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDeactivate($id, $product_id)
    {
        $tag_product = TagProduct::find($product_id);
        $tag_product->is_actived = 0;
        if ($tag_product->save()) {
            return back()->with('success', 'Item #' . $tag_product->id . ' has been deactivated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doRemove($id, $product_id)
    {
        $tag_product = TagProduct::find($product_id);
        $tag_product->is_deleted = 1;
        if ($tag_product->save()) {
            return back()->with('success', 'tag\'s product #' . $tag_product->id . ' has been removed.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doRestore($id, $product_id)
    {
        $tag_product = TagProduct::find($product_id);
        $tag_product->is_deleted = 0;
        if ($tag_product->save()) {
            return back()->with('success', 'Tag\'s product #' . $tag_product->id . ' has been restored.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDelete($id, $product_id)
    {
        $tag_product = TagProduct::find($product_id);
        if ($tag_product->forceDelete()) {
            return back()->with('success', 'Tag\'s product #' . $tag_product->id . ' has been deleted.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function add($id)
    {
        $tag = Tag::find($id);
        $tag_products = TagProduct::notDelete()->where('tag_id', $id)->get();
        $tag_products_array = array();
        foreach ($tag_products as $tag_product) {
            $tag_products_array[] = $tag_product->product_id;
        }
        $products = Product::notDelete()->whereNotIn('id', $tag_products_array)->get();
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.tag.product.add', [
            'tag' => $tag,
            'products' => $products,
            'tag_products' => $tag_products,
            //
            'current_user' => $user,
        ]);
    }

    public function doAdd(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'product_id_list' => 'required',
        ], [
            'required' => ':attribute must be filled'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors(),
            ]);
        } else {
            foreach ($request->product_id_list as $index => $value) {
                $tag_product = null;
                $tag_product = new TagProduct();
                $tag_product->tag_id = $request->id;
                $tag_product->product_id = $value;
                $result2 = $tag_product->save();
                if (!$result2) {
                    $errors = new MessageBag(['errorAddDetail' => 'Error occurred!']);
                    return response()->json([
                        'error' => true,
                        'message' => $errors
                    ]);
                }
            }
            return response()->json([
                'error' => false,
                'message' => 'Success'
            ]);
        }
    }

    public function edit($id, $product_id)
    {
        $tag = Tag::find($id);
        $tag_products = TagProduct::notDelete()->where('tag_id', $id)->get();
        $tag_products_array = array();
        foreach ($tag_products as $tag_product) {
            $tag_products_array[] = $tag_product->product_id;
        }
        $products = Product::notDelete()->whereNotIn('id', $tag_products_array)->get();

        $tag_product = TagProduct::find($product_id);
        $product = Product::find($tag_product->product_id);
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.tag.product.edit', [
            'tag' => $tag,
            'products' => $products,
            'tag_product' => $tag_product,
            'product' => $product,
            //
            'current_user' => $user,
        ]);
    }
    public function doEdit($id,Request $request)
    {
        $validate = Validator::make($request->all(), [
            'tag_product_id' => 'required',
            'product_id' => 'required',
        ], [
            'required' => ':attribute must be filled'
        ]);
        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors(),
            ]);
        } else {
            $tag_product = TagProduct::find($request->tag_product_id);
            $tag_product->product_id = $request->product_id;
            $result = $tag_product->save();
            if ($result) {
                return response()->json([
                    'error' => false,
                    'message' => 'Success'
                ]);
            } else {
                $errors = new MessageBag(['errorEdit' => 'Error occurred!']);
                return response()->json([
                    'error' => true,
                    'message' => $errors
                ]);
            }
        }
    }

    public function recycle($id, Request $request)
    {
        $tag = Tag::find($id);
        $tag_products = TagProduct::softDelete()->where('tag_id', '=', $id)
            ->sortId($request)->status($request);
        $count_tag_product = 0;
        $view = 0;
        $count_tag_product = $tag_products->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $tag_products = $tag_products->paginate($view);

        // product belong to tag
        $tag_products_array = array();
        foreach ($tag_products as $tag_product) {
            $tag_products_array[] = $tag_product->product_id;
        }
        $products = Product::notDelete()->whereIn('id', $tag_products_array)->get();

        // user
        $user = Auth::guard('admin')->user();
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        return view('admin.tag.product.recycle', [
            'tag' => $tag,
            'count_tag_product' => $count_tag_product,
            'view' => $view,
            //
            'products' => $products,
            'tag_products' => $tag_products,
            //
            'current_user' => $user,
            // filter
            'sort_id' => $sort_id,
            'status' => $status,
        ]);
    }

    public function bulk_action(Request $request)
    {
        if ($request->has('bulk_action') && $request->bulk_action != null) {
            if ($request->has('tag_product_id_list')) {
                $message = null;
                $error = null;
                switch ($request->bulk_action) {
                    case 0: // deactivate
                        $message = 'Item ';
                        foreach ($request->tag_product_id_list as $product_id) {
                            $item = null;
                            $item = TagProduct::find($product_id);
                            $item->is_actived = 0;
                            if ($item->save()) {
                                $message .= ' #' . $item->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while deactivating item #' . $item->id);
                            }
                        }
                        $message .= 'have been deactivated.';
                        return back()->with('success', $message);
                        break;
                    case 1: // activate
                        $message = 'Item ';
                        foreach ($request->tag_product_id_list as $product_id) {
                            $item = null;
                            $item = TagProduct::find($product_id);
                            $item->is_actived = 1;
                            if ($item->save()) {
                                $message .= ' #' . $item->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while activating item #' . $item->id);
                            }
                        }
                        $message .= 'have been activated.';
                        return back()->with('success', $message);
                        break;
                    case 2: // remove
                        $message = 'Item ';
                        foreach ($request->tag_product_id_list as $product_id) {
                            $item = null;
                            $item = TagProduct::find($product_id);
                            $item->is_deleted = 1;
                            if ($item->save()) {
                                $message .= ' #' . $item->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while deleting item #' . $item->id);
                            }
                        }
                        $message .= 'have been removed.';
                        return back()->with('success', $message);
                        break;
                    case 3: // restore
                        $message = 'Item ';
                        foreach ($request->tag_product_id_list as $product_id) {
                            $item = null;
                            $item = TagProduct::find($product_id);
                            $item->is_deleted = 0;
                            if ($item->save()) {
                                $message .= ' #' . $item->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while restoring item #' . $item->id);
                            }
                        }
                        $message .= 'have been restored.';
                        return back()->with('success', $message);
                        break;
                    case 4: // delete
                        $message = 'Item ';
                        foreach ($request->tag_product_id_list as $product_id) {
                            $item = null;
                            $item = TagProduct::find($product_id);
                            if ($item->forceDelete()) {
                                $message .= ' #' . $item->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while deleting item #' . $item->id);
                            }
                        }
                        $message .= 'have been deleted.';
                        return back()->with('success', $message);
                        break;
                }
                if ($error != null) {
                    return back()->with('success', $message)->with('error', $error);
                }
                return back()->with('success', $message);
            } else {
                return back()->with('error', 'Please select items to take action!');
            }
        } else {
            return back()->with('error', 'Please select an action!');
        }
    }
}
