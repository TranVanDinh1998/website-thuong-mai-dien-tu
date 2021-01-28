<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Tag;
use Illuminate\Support\Facades\Auth;
use App\TagProduct;
use Illuminate\Support\Facades\File;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use App\Product;

class TagController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(Request $request)
    {
        // tags
        $tags = tag::notDelete()->search($request)->sortId($request)->status($request);
        $count_tag = 0;
        $view = 0;
        $count_tag = $tags->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $tags = $tags->paginate($view);
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        // user
        $user = Auth::guard('admin')->user();
        // search
        $search = $request->search;
        // product
        $products = Product::notDelete()->orderBy('name', 'asc')->get();
        return view('admin.tag.index', [
            // tags
            'tags' => $tags,
            'count_tag' => $count_tag,

            'view' => $view,
            // filter
            'sort_id' => $sort_id,
            'status' => $status,
            // search
            'search' => $search,
            //
            'current_user' => $user,
            // product
            'products' => $products,
        ]);
    }

    public function doActivate($id)
    {
        $tag = tag::find($id);
        $tag->is_actived = 1;
        if ($tag->save()) {
            return back()->with('success', 'Tag #' . $tag->id . ' has been activated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDeactivate($id)
    {
        $tag = tag::find($id);
        $tag->is_actived = 0;
        if ($tag->save()) {
            return back()->with('success', 'Tag #' . $tag->id . ' has been deactivated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function add()
    {
        // user
        $user = Auth::guard('admin')->user();
        $products = Product::where('is_deleted', 0)->get();
        return view('admin.tag.add', [
            'products' => $products,
            //
            'current_user' => $user,
        ]);
    }

    public function doAdd(Request $request)
    {

        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required',
            ],
            [
                'required' => ':attribute must be filled',
            ]
        );
        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors(),
            ]);
        } else {
            $tag = new Tag();
            $tag->name = $request->name;
            $result = $tag->save();
            if ($result) {
                foreach ($request->product_id_list as $index => $value) {
                    $tag_product = null;
                    $tag_product = new TagProduct();
                    $tag_product->tag_id = $tag->id;
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
            } else {
                $errors = new MessageBag(['errorAdd' => 'Error occurred!']);
                return response()->json([
                    'error' => true,
                    'message' => $errors
                ]);
            }
        }
    }
    public function edit($id)
    {
        // user
        $user = Auth::guard('admin')->user();
        $tag = Tag::find($id);
        return view('admin.tag.edit', [
            'tag' => $tag,
            //
            'current_user' => $user,
        ]);
    }
    public function doEdit(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'id' => 'required',
            ],
            [
                'required' => ':attribute must be filled',
            ]
        );
        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors(),
            ]);
        } else {
            $tag = Tag::find($request->id);
            $tag->name = $request->name;
            $result = $tag->save();
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
    public function doRemove($id)
    {
        $tag = Tag::find($id);
        $count_tag_products = TagProduct::where('tag_id', $tag->id)->count();
        if ($count_tag_products == 0) {
            $tag->is_deleted = 1;
            if ($tag->save()) {
                return back()->with('success', 'Tag #' . $tag->name . ' has been removed.');
            } else {
                return back()->with('error', 'Error occurred!');
            }
        } else {
            return back()->with('error', 'This tag relates to  ' . $count_tag_products . ' products');
        }
    }

    public function recycle(Request $request)
    {
        // tags
        $tags = tag::softDelete()->search($request)->sortId($request)->status($request);
        $count_tag = 0;
        $view = 0;
        $count_tag = $tags->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $tags = $tags->paginate($view);
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        // user
        $user = Auth::guard('admin')->user();
        // search
        $search = $request->search;
        return view('admin.tag.recycle', [
            // tags
            'tags' => $tags,
            'count_tag' => $count_tag,

            'view' => $view,
            // filter
            'sort_id' => $sort_id,
            'status' => $status,
            //
            // search
            'search' => $search,
            'current_user' => $user,
        ]);
    }

    public function doRestore($id)
    {
        $tag = Tag::find($id);
        $tag->is_deleted = 0;
        if ($tag->save()) {
            return back()->with('success', 'Tag #' . $tag->id . ' has been restored.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDelete($id)
    {
        $tag = Tag::find($id);
        if ($tag->forceDelete()) {
            return back()->with('success', 'Tag #' . $tag->id . ' has been deleted.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function bulk_action(Request $request)
    {
        if ($request->has('bulk_action')) {
            if ($request->has('tag_id_list')) {
                $message = null;
                $error = null;
                switch ($request->bulk_action) {
                    case 0: // deactivate
                        $message = 'tag ';
                        foreach ($request->tag_id_list as $tag_id) {
                            $tag = null;
                            $tag = tag::find($tag_id);
                            $tag->is_actived = 0;
                            if ($tag->save()) {
                                $message .= ' #' . $tag->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while deactivating tag #' . $tag->id);
                            }
                        }
                        $message .= 'have been deactivated.';
                        return back()->with('success', $message);
                        break;
                    case 1: // activate
                        $message = 'tag ';
                        foreach ($request->tag_id_list as $tag_id) {
                            $tag = null;
                            $tag = tag::find($tag_id);
                            $tag->is_actived = 1;
                            if ($tag->save()) {
                                $message .= ' #' . $tag->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while activating tag #' . $tag->id);
                            }
                        }
                        $message .= 'have been activated.';
                        return back()->with('success', $message);
                        break;
                    case 2: // remove
                        $message = 'tag';
                        foreach ($request->tag_id_list as $tag_id) {
                            $tag = null;
                            $tag = tag::find($tag_id);
                            $count_relative_product = TagProduct::where('tag_id', $tag_id)->count();
                            if ($count_relative_product == 0) {
                                $tag->is_deleted = 1;
                                if ($tag->save()) {
                                    $message .= ' #' . $tag->id . ', ';
                                } else {
                                    return back()->with('error', 'Error occurred while removing tag #' . $tag->id);
                                }
                            } else {
                                return back()->with('error', 'tag #' . $tag->id . ' relates to ' . $count_relative_product . ' products.');
                            }
                        }
                        $message .= 'have been removed.';
                        break;
                    case 3: // restore
                        $message = 'tag ';
                        foreach ($request->tag_id_list as $tag_id) {
                            $tag = null;
                            $tag = tag::find($tag_id);
                            $tag->is_deleted = 0;
                            if ($tag->save()) {
                                $message .= ' #' . $tag->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while restoring tag #' . $tag->id);
                            }
                        }
                        $message .= 'have been restored.';
                        return back()->with('success', $message);
                        break;
                    case 4: // delete
                        $message = 'tag ';
                        foreach ($request->tag_id_list as $tag_id) {
                            $tag = null;
                            $tag = tag::find($tag_id);
                            if ($tag->forceDelete()) {
                                $message .= ' #' . $tag->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while deleting tag #' . $tag->id);
                            }
                        }
                        $message .= 'have been deleted.';
                        return back()->with('success', $message);
                        break;
                    case 5: // add tag to product
                        if ($request->product_id_list == null) {
                            return back()->with('error', 'Please select at least one product!');
                        } else {
                            foreach ($request->tag_id_list as $tag_id) {
                                foreach ($request->product_id_list as $product_id) {
                                    if (TagProduct::where('tag_id', $tag_id)->where('product_id', $product_id)->count() > 0) {
                                        $error .= 'tag #' . $tag_id . ' already has product #' . $product_id . '. ';
                                        continue;
                                    } else {
                                        $message .= 'Product';
                                        $tag_product = null;
                                        $tag_product = new TagProduct();
                                        $tag_product->tag_id = $tag_id;
                                        $tag_product->product_id = $product_id;
                                        $result = $tag_product->save();
                                        if ($result) {
                                            $message .= ' #' . $product_id . ' ';
                                        } else {
                                            return back()->with('error', 'Error occurred when add product #' . $product_id . ' to tag #' . $tag_id);
                                        }
                                    }
                                    $message .= 'have been added to tag #' . $tag_id . '. ';
                                }
                            }
                        }
                        break;
                }
                if ($error != null) {
                    return back()->with('success', $message)->with('error', $error);
                }
                return back()->with('success', $message);
            } else {
                return back()->with('error', 'Please select tags to take action!');
            }
        } else {
            return back()->with('error', 'Please select an action!');
        }
    }
}
