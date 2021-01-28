<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Collection;
use App\CollectionProduct;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\File;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use App\Product;

class CollectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(Request $request)
    {
        // collections
        $collections = collection::notDelete()->search($request)->sortId($request)->status($request);
        $count_collection = 0;
        $view = 0;
        $count_collection = $collections->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $collections = $collections->paginate($view);
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        // search
        $search = $request->search;
        // user
        $user = Auth::guard('admin')->user();
        // product
        $products = Product::notDelete()->orderBy('name', 'asc')->get();
        return view('admin.collection.index', [
            // collections
            'collections' => $collections,
            'count_collection' => $count_collection,
            'view' => $view,
            // filter
            'sort_id' => $sort_id,
            'status' => $status,
            // search
            'search' => $search,
            // user
            'current_user' => $user,
            // product
            'products' => $products,
        ]);
    }

    public function doActivate($id)
    {
        $collection = Collection::find($id);
        $collection->is_actived = 1;
        if ($collection->save()) {
            return back()->with('success', 'Collection #' . $collection->id . ' has been activated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDeactivate($id)
    {
        $collection = Collection::find($id);
        $collection->is_actived = 0;
        if ($collection->save()) {
            return back()->with('success', 'Collection #' . $collection->id . ' has been deactivated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function add()
    {
        // user
        $user = Auth::guard('admin')->user();
        $products = Product::where('is_deleted', 0)->get();
        return view('admin.collection.add', [
            'products' => $products,
            // user
            'current_user' => $user,
        ]);
    }

    public function doAdd(Request $request)
    {

        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'description' => 'required',
                'category_id' => 'required',
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
            $collection = new Collection();
            $collection->name = $request->name;
            $collection->description = $request->description;
            $collection->category_id = $request->category_id;
            if ($request->priority) {
                $collection->priority = $request->priority;
            } else {
                $collection->priority = 0;
            }
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $format = $file->getClientOriginalExtension();
                if ($format != 'jpg' && $format != 'png' && $format != 'jpeg') {
                    $errors = new MessageBag(['errorImage' => 'File is not an image!']);
                    return response()->json([
                        'error' => true,
                        'message' => $errors,
                    ]);
                }
                $name = $file->getClientOriginalName();
                $avatar = Str::random(4) . "_" . $name;
                while (file_exists("/uploads/categories-images/" . $request->category_id . "/" . $avatar)) {
                    $avatar = Str::random(4) . "_" . $name;
                }
                $file->move(public_path() . '/uploads/categories-images/' . $request->category_id, $avatar);
                $collection->image = $avatar;
            } else {
                $collection->image = null;
            }
            $result = $collection->save();
            if ($result) {
                if ($request->has('product_id_list')) {
                    foreach ($request->product_id_list as $index => $value) {
                        $collection_product = null;
                        $collection_product = new CollectionProduct();
                        $collection_product->collection_id = $collection->id;
                        $collection_product->product_id = $value;
                        $result2 = $collection_product->save();
                        if (!$result2) {
                            $errors = new MessageBag(['errorAddDetail' => 'Error occurred!']);
                            return response()->json([
                                'error' => true,
                                'message' => $errors
                            ]);
                        }
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
        $collection =  Collection::find($id);
        $collection_products = CollectionProduct::where('is_deleted', 0)->where('collection_id', '=', $id)->get();
        $collection_products_array = array();
        foreach ($collection_products as $collection_product) {
            $collection_products_array[] = $collection_product->id;
        }
        $products = Product::where('is_deleted', 0)->whereIn('id', $collection_products_array)->get();
        // print_r($collection_products);
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.collection.edit', [
            'collection' => $collection,
            'products' => $products,
            'collection_products' => $collection_products,
            //
            'current_user' => $user,
        ]);
    }

    public function doEdit(Request $request)
    {

        $validate = Validator::make(
            $request->all(),
            [
                'id' => 'required',
                'name' => 'required',
                'description' => 'required',
                'category_id' => 'required',
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
            $collection = Collection::find($request->id);
            $collection->name = $request->name;
            $collection->description = $request->description;
            $collection->category_id = $request->category_id;
            if ($request->has('priority')) {
                $collection->priority = $request->priority;
            }
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $format = $file->getClientOriginalExtension();
                if ($format != 'jpg' && $format != 'png' && $format != 'jpeg') {
                    $errors = new MessageBag(['errorImage' => 'File is not an image!']);
                    return response()->json([
                        'error' => true,
                        'message' => $errors,
                    ]);
                }
                $name = $file->getClientOriginalName();
                $avatar = Str::random(4) . "_" . $name;
                while (file_exists("/uploads/categories-images/" . $request->category_id . "/" . $avatar)) {
                    $avatar = Str::random(4) . "_" . $name;
                }
                $file->move(public_path() . '/uploads/categories-images/' . $request->category_id, $avatar);
                $collection->image = $avatar;
            }
            $result = $collection->save();
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
        $collection = Collection::find($id);
        $count_collection_products = CollectionProduct::where('collection_id', $collection->id)->count();
        if ($count_collection_products == 0) {
            $collection->is_deleted = 1;
            if ($collection->save()) {
                return back()->with('success', 'Collection #' . $collection->id . ' has been removed.');
            } else {
                return back()->with('error', 'Error occurred!');
            }
        } else {
            return back()->with('error', 'Collection #'.$collection->id.' relates to  ' . $count_collection_products . ' products. Unable to remove');
        }
    }

    public function recycle(Request $request)
    {
        // collections
        $collections = collection::softDelete()->search($request)->sortId($request)->status($request);
        $count_collection = 0;
        $view = 0;
        $count_collection = $collections->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $collections = $collections->paginate($view);
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        // search
        $search = $request->search;
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.collection.recycle', [
            // collections
            'collections' => $collections,
            'count_collection' => $count_collection,
            'view' => $view,
            // filter
            'sort_id' => $sort_id,
            'status' => $status,
            // search
            'search' => $search,
            // user
            'current_user' => $user,
        ]);
    }

    public function doRestore($id)
    {
        $collection = Collection::find($id);
        $collection->is_deleted = 0;
        if ($collection->save()) {
            return back()->with('success', 'Collection #' . $collection->id . ' has been restored.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDelete($id)
    {
        $collection = Collection::find($id);
        $count_collection_products = CollectionProduct::where('collection_id', $collection->id)->count();
        if ($count_collection_products == 0) {
            $collection->is_deleted = 1;
            if ($collection->forceDelete()) {
                return back()->with('success', 'Collection #' . $collection->id . ' has been deleted.');
            } else {
                return back()->with('error', 'Error occurred!');
            }
        } else {
            return back()->with('error', 'Collection #'.$collection->id.' relates to  ' . $count_collection_products . ' products. Unable to delete');
        }
    }
    public function bulk_action(Request $request)
    {
        if ($request->has('bulk_action')) {
            if ($request->has('collection_id_list')) {
                $message = null;
                $error = null;
                switch ($request->bulk_action) {
                    case 0: // deactivate
                        $message = 'collection ';
                        foreach ($request->collection_id_list as $collection_id) {
                            $collection = null;
                            $collection = collection::find($collection_id);
                            $collection->is_actived = 0;
                            if ($collection->save()) {
                                $message .= ' #' . $collection->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred when deactivate collection #' . $collection->id);
                            }
                        }
                        $message .= 'have been deactivated.';
                        return back()->with('success', $message);
                        break;
                    case 1: // activate
                        $message = 'collection ';
                        foreach ($request->collection_id_list as $collection_id) {
                            $collection = null;
                            $collection = collection::find($collection_id);
                            $collection->is_actived = 1;
                            if ($collection->save()) {
                                $message .= ' #' . $collection->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred when activate collection #' . $collection->id);
                            }
                        }
                        $message .= 'have been activated.';
                        return back()->with('success', $message);
                        break;
                    case 2: // remove
                        $message = 'Collection';
                        foreach ($request->collection_id_list as $collection_id) {
                            $collection = null;
                            $collection = collection::find($collection_id);
                            $count_relative_collection_product = CollectionProduct::where('collection_id', $collection_id)->count();
                            if ($count_relative_collection_product == 0) {
                                $collection->is_deleted = 1;
                                if ($collection->save()) {
                                    $message .= ' #' . $collection->id . ', ';
                                } else {
                                    return back()->with('error', 'Error occurred when remove collection #' . $collection->id);
                                }
                            } else {
                                return back()->with('error', 'Collection #' . $collection->id . ' relates to ' . $count_relative_collection_product . ' products.');
                            }
                        }
                        $message .= 'have been removed.';
                        break;
                    case 3: // restore
                        $message = 'collection ';
                        foreach ($request->collection_id_list as $collection_id) {
                            $collection = null;
                            $collection = collection::find($collection_id);
                            $collection->is_deleted = 0;
                            if ($collection->save()) {
                                $message .= ' #' . $collection->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while restoring collection #' . $collection->id);
                            }
                        }
                        $message .= 'have been restored.';
                        return back()->with('success', $message);
                        break;
                    case 4: // delete
                        $message = 'Collection';
                        foreach ($request->collection_id_list as $collection_id) {
                            $collection = null;
                            $collection = collection::find($collection_id);
                            $count_relative_collection_product = CollectionProduct::where('collection_id', $collection_id)->count();
                            if ($count_relative_collection_product == 0) {
                                if ($collection->forceDelete()) {
                                    $message .= ' #' . $collection->id . ', ';
                                } else {
                                    return back()->with('error', 'Error occurred while removing collection #' . $collection->id);
                                }
                            } else {
                                return back()->with('error', 'Collection #' . $collection->id . ' relates to ' . $count_relative_collection_product . ' products. Unable to deleted');
                            }
                        }
                        $message .= 'have been deleted.';
                        break;
                    case 5: // add tag to product
                        if ($request->product_id_list == null) {
                            return back()->with('error', 'Please select at least one product!');
                        } else {
                            foreach ($request->collection_id_list as $collection_id) {
                                foreach ($request->product_id_list as $product_id) {
                                    if (CollectionProduct::where('collection_id', $collection_id)->where('product_id', $product_id)->count() > 0) {
                                        $error .= 'Collection #' . $collection_id . ' already has product #' . $product_id . '. ';
                                        continue;
                                    } else {
                                        $message .= 'Product';
                                        $collection_product = null;
                                        $collection_product = new CollectionProduct();
                                        $collection_product->collection_id = $collection_id;
                                        $collection_product->product_id = $product_id;
                                        $result = $collection_product->save();
                                        if ($result) {
                                            $message .= ' #' . $product_id . ' ';
                                        } else {
                                            return back()->with('error', 'Error occurred while adding product #' . $product_id . ' to collection #' . $collection_id);
                                        }
                                    }
                                    $message .= 'have been added to collection #' . $collection_id . '. ';
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
                return back()->with('error', 'Please select collections to take action!');
            }
        } else {
            return back()->with('error', 'Please select an action!');
        }
    }
}
