<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\Collection;
use App\CollectionProduct;
use App\ProductImage;
use App\Category;
use App\Order;
use App\OrderDetail;
use App\Producer;
use App\TagProduct;
use App\Review;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;



class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(Request $request)
    {
        // products
        $products = Product::notDelete()->sortId($request)->search($request)
            ->date($request)->price($request)->remaining($request)
            ->category($request)->producer($request)->status($request);

        // 
        $count_product = 0;
        $view = 0;
        $count_product = $products->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $products = $products->paginate($view);

        // filter
        $sort_id = $request->sort_id;
        $search = $request->search;
        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $price_from = $request->price_from;
        $price_to = $request->price_to;
        $remaining = $request->remaining;
        $category_id = $request->category_id;
        $producer_id = $request->producer_id;
        $status = $request->status;
        // search
        $search = $request->search;

        $categories = Category::notDelete()->get();
        $producers = Producer::notDelete()->get();
        // tag
        $tags = Tag::notDelete()->orderBy('name', 'asc')->get();
        $collections = Collection::notDelete()->orderBy('name', 'asc')->get();
        // user
        $user = Auth::guard('admin')->user();

        return view('admin.product.index', [
            //products
            'products' => $products,
            'count_product' => $count_product,
            'view' => $view,
            // filter
            'sort_id' => $sort_id,
            'search' => $search,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'price_from' => $price_from,
            'price_to' => $price_to,
            'remaining' => $remaining,
            'category_id' => $category_id,
            'producer_id' => $producer_id,
            'status' => $status,
            // search
            'search' => $search,
            //
            'producers' => $producers,
            'categories' => $categories,
            'tags' => $tags,
            'collections' => $collections,
            //
            'current_user' => $user,
        ]);
    }

    public function doActivate($id)
    {
        $product = Product::find($id);
        $product->is_actived = 1;
        if ($product->save()) {
            return back()->with('success', 'Product ' . $product->name . ' has been activated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDeactivate($id)
    {
        $product = Product::find($id);
        $product->is_actived = 0;
        if ($product->save()) {
            return back()->with('success', 'Product ' . $product->name . ' has been deactivated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function add()
    {
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.product.add', [
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
                'description' => 'required',
                'image' => 'required|image',
                'quantity' => 'required|min:0',
                'price' => 'required|min:0',
                'category_id' => 'required',
                'producer_id' => 'required'
            ],
            [
                'required' => ':attribute must be filled',
                'image' => ':attribute must be an image'
            ]
        );
        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors(),
            ]);
        } else {
            $product = new Product();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->quantity = $request->quantity;
            $product->remaining = $request->quantity;
            $product->price = $request->price;
            $product->category_id = $request->category_id;
            $product->producer_id = $request->producer_id;
            $product->create_date = date('Y-m-d');
            if ($request->discount != null && $request->discount != 0) {
                $product->discount = $request->discount;
            }
            $result = $product->save();
            if ($result) {
                $path = public_path('uploads/products-images/' . $product->id);
                if (!File::isDirectory($path)) {
                    File::makeDirectory($path, 0777, true, true);
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
                    while (file_exists("/uploads/products-images/" . $product->id . "/" . $avatar)) {
                        $avatar = Str::random(4) . "_" . $name;
                    }
                    $file->move(public_path() . '/uploads/products-images/' . $product->id, $avatar);
                    $product->image = $avatar;
                } else {
                    $product->image = null;
                }
                $result = $product->save();
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
        $product =  Product::find($id);
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.product.edit', [
            'product' => $product,
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
                'quantity' => 'required|min:0',
                'price' => 'required|min:0',
                'category_id' => 'required',
                'producer_id' => 'required'
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
            $product = Product::find($request->id);
            $product->name = $request->name;
            $product->description = $request->description;
            $product->quantity = $request->quantity;
            $product->remaining = $request->quantity;
            $product->price = $request->price;
            $product->category_id = $request->category_id;
            $product->producer_id = $request->producer_id;
            // $product->create_date = date('Y-m-d');
            if ($request->discount != null && $request->discount != 0) {
                $product->discount = $request->discount;
            }
            $path = public_path('uploads/products-images/' . $request->id);
            if (!File::isDirectory($path)) {
                File::makeDirectory($path, 0777, true, true);
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
                while (file_exists("/uploads/products-images/" . $request->id . "/" . $avatar)) {
                    $avatar = Str::random(4) . "_" . $name;
                }
                $file->move(public_path() . '/uploads/products-images/' . $request->id, $avatar);
                $product->image = $avatar;
            }
            $result = $product->save();
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
        $product = Product::find($id);
        if ($product->remaining > 0) {
            return back()->with('error', 'Unable to remove. Product #' . $product->id . ' has ' . $product->remaining . ' item(s) left in stock.');
        } else {
            $count_relative_collection = CollectionProduct::where('product_id', $id)->count();
            if ($count_relative_collection == 0) {
                $count_relative_order = OrderDetail::where('product_id', $product->id)->count();
                if ($count_relative_order == 0) {
                    $count_relative_tag = TagProduct::where('product_id', $product->id)->count();
                    if ($count_relative_tag == 0) {
                        $count_relative_review = Review::where('product_id', $product->id)->count();
                        if ($count_relative_review == 0) {
                            $product->is_deleted = 1;
                            $product->save();
                            if ($product->save()) {
                                return back()->with('success', 'Unable to remove. Product #' . $product->id . ' has been removed.');
                            } else {
                                return back()->with('error', 'Error occurred!');
                            }
                        } else {
                            return back()->with('error', 'Unable to remove. Product #' . $product->id . ' has related ' . $count_relative_review . ' reviews.');
                        }
                    } else {
                        return back()->with('error', 'Unable to remove. Product #' . $product->id . ' has related ' . $count_relative_tag . ' tags.');
                    }
                } else {
                    return back()->with('error', 'Unable to remove. Product #' . $product->id . ' has related ' . $count_relative_order . ' orders.');
                }
            } else {
                return back()->with('error', 'Unable to remove. Product #' . $product->id . ' relates to ' . $count_relative_collection . ' collections.');
            }
        }
    }

    public function doImport(Request $request)
    {
        // $number = $request->number;
        // $parameter = $request->all();
        // foreach ($request->import as $import) {
        // print_r($import) . '<br>';
        // foreach ($import as $quantity=>$value) {
        //     echo $quan
        // }
        // }
        // return $parameter;
        foreach ($request->import as $import) {
            // print_r($import) . '<br>';
            // echo $import['product_id']. '-' . $import['quantity']. '<br>';
            $product = null;
            $product = Product::find($import['product_id']);
            $product->quantity += $import['quantity'];
            $product->remaining += $import['quantity'];
            $result = $product->save();
            if (!$result) {
                return response()->json([
                    'error' => true,
                    'message' => 'Error'
                ]);
            }
        }
        return response()->json([
            'error' => false,
            'message' => 'Success',
        ]);

        // for ($i = 0; $i < $number; $i++) {
        //     $product = null;
        //     $product = Product::find($parameter['product_id_' . $i]);
        //     $product->quantity += $parameter['quantity_' . $i];
        //     $product->remaining += $parameter['quantity_' . $i];
        //     $result = $product->save();
        //     if (!$result) {
        //         return response()->json([
        //             'error' => true,
        //             'message' => 'Error'
        //         ]);
        //     }
        // }

    }

    public function recycle(Request $request)
    {
        // products
        $products = Product::softDelete()->sortId($request)->search($request)
            ->date($request)->price($request)->remaining($request)
            ->category($request)->producer($request)->status($request);

        // c
        $count_product = 0;
        $view = 0;
        $count_product = $products->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $products = $products->paginate($view);

        // filter
        $sort_id = $request->sort_id;
        $search = $request->search;
        $date_from = $request->date_from;
        $date_to = $request->date_to;
        $price_from = $request->price_from;
        $price_to = $request->price_to;
        $remaining = $request->remaining;
        $category_id = $request->category_id;
        $producer_id = $request->producer_id;
        $status = $request->status;

        $categories = Category::where('is_deleted', 0)->get();
        $producers = Producer::where('is_deleted', 0)->get();
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.product.recycle', [
            //products
            'products' => $products,
            'count_product' => $count_product,
            'view' => $view,
            // filter
            'sort_id' => $sort_id,
            'search' => $search,
            'date_from' => $date_from,
            'date_to' => $date_to,
            'price_from' => $price_from,
            'price_to' => $price_to,
            'remaining' => $remaining,
            'category_id' => $category_id,
            'producer_id' => $producer_id,
            'status' => $status,

            'producers' => $producers,
            'categories' => $categories,
            // user
            'current_user' => $user,

        ]);
    }
    public function doRestore($id)
    {
        $product = Product::find($id);
        $product->is_deleted = 0;
        $product->save();
        if ($product->save()) {
            return back()->with('success', 'Product ' . $product->name . ' has been restored.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDelete($id)
    {
        $product = Product::find($id);
        if ($product->forceDelete()) {
            return back()->with('success', 'Product ' . $product->name . ' has been deleted.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function bulk_action(Request $request)
    {
        if ($request->has('bulk_action') && $request->bulk_action != null) {
            if ($request->has('product_id_list')) {
                $message = null;
                $error = null;
                switch ($request->bulk_action) {
                    case 0: // deactivate
                        $message = 'Product ';
                        foreach ($request->product_id_list as $product_id) {
                            $product = null;
                            $product = Product::find($product_id);
                            $product->is_actived = 0;
                            if ($product->save()) {
                                $message .= ' #' . $product->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred when deactivate product #' . $product->id);
                            }
                        }
                        $message .= 'have been deactivated.';
                        return back()->with('success', $message);
                        break;
                    case 1: // activate
                        $message = 'Product ';
                        foreach ($request->product_id_list as $product_id) {
                            $product = null;
                            $product = Product::find($product_id);
                            $product->is_actived = 1;
                            if ($product->save()) {
                                $message .= ' #' . $product->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred when activate product #' . $product->id);
                            }
                        }
                        $message .= 'have been activated.';
                        return back()->with('success', $message);
                        break;
                    case 2: // remove
                        $message = 'Product';
                        foreach ($request->product_id_list as $product_id) {
                            $product = null;
                            $product = Product::find($product_id);
                            if ($product->remaining > 0) {
                                return back()->with('error', 'Product #' . $product->id . ' has ' . $product->remaining . ' items left in stock.');
                            } else {
                                $count_relative_collection = CollectionProduct::where('product_id', $product_id)->count();
                                if ($count_relative_collection == 0) {
                                    $count_relative_order = OrderDetail::where('product_id', $product->id)->count();
                                    if ($count_relative_order == 0) {
                                        $count_relative_tag = TagProduct::where('product_id', $product->id)->count();
                                        if ($count_relative_tag == 0) {
                                            $count_relative_review = Review::where('product_id', $product->id)->count();
                                            if ($count_relative_review == 0) {
                                                $product->is_deleted = 1;
                                                $product->save();
                                                if ($product->save()) {
                                                    $message .= ' #' . $product->id . ', ';
                                                } else {
                                                    return back()->with('error', 'Error occurred when remove product #' . $product->id);
                                                }
                                            } else {
                                                return back()->with('error', 'Product #' . $product->id . ' has related ' . $count_relative_review . ' reviews.');
                                            }
                                        } else {
                                            return back()->with('error', 'Product #' . $product->id . ' has related ' . $count_relative_tag . ' tags.');
                                        }
                                    } else {
                                        return back()->with('error', 'Product #' . $product->id . ' has related ' . $count_relative_order . ' orders.');
                                    }
                                } else {
                                    return back()->with('error', 'Product #' . $product->id . ' relates to ' . $count_relative_collection . ' collections.');
                                }
                            }
                        }
                        $message .= 'have been removed.';
                        break;
                    case 3: // import
                        $products = Product::notDelete()->whereIn('id', $request->product_id_list)->get();
                        return view('admin.product.import', [
                            'products' => $products,
                            'count' => count($products),
                        ]);
                        break;
                    case 4: // restore
                        $message = 'Product ';
                        foreach ($request->product_id_list as $product_id) {
                            $product = null;
                            $product = Product::find($product_id);
                            $product->is_deleted = 0;
                            if ($product->save()) {
                                $message .= ' #' . $product->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred when restore product #' . $product->id);
                            }
                        }
                        $message .= 'have been restored.';
                        return back()->with('success', $message);
                        break;
                    case 5: // delete
                        $message = 'Product ';
                        foreach ($request->product_id_list as $product_id) {
                            $product = null;
                            $product = Product::find($product_id);
                            if ($product->forceDelete()) {
                                $message .= ' #' . $product->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred when deleted product #' . $product->id);
                            }
                        }
                        $message .= 'have been deleted.';
                        return back()->with('success', $message);
                        break;
                    case 6: // add tag to product
                        if ($request->tag_id_list == null) {
                            return back()->with('error', 'Please select aleast one tag!');
                        } else {
                            foreach ($request->product_id_list as $product_id) {
                                foreach ($request->tag_id_list as $tag_id) {
                                    if (TagProduct::where('tag_id', $tag_id)->where('product_id', $product_id)->count() > 0) {
                                        $error .= 'Product #' . $product_id . ' already has tag #' . $tag_id . '. ';
                                        continue;
                                    } else {
                                        $message .= 'Tag';
                                        $tag_product = null;
                                        $tag_product = new TagProduct();
                                        $tag_product->tag_id = $tag_id;
                                        $tag_product->product_id = $product_id;
                                        $result = $tag_product->save();
                                        if ($result) {
                                            $message .= ' #' . $tag_id . ' ';
                                        } else {
                                            return back()->with('error', 'Error occurred when add tag #' . $tag_id . ' to product #' . $product_id);
                                        }
                                    }
                                    $message .= 'have been added to product #' . $product_id . '. ';
                                }
                            }
                        }
                        break;
                    case 7: // add product to collection
                        if ($request->collection_id_list == null) {
                            return back()->with('error', 'Please select aleast one collection!');
                        } else {
                            foreach ($request->collection_id_list as $collection_id) {
                                foreach ($request->product_id_list as $product_id) {
                                    if (CollectionProduct::where('collection_id', $collection_id)->where('product_id', $product_id)->count() > 0) {
                                        $error .= 'Collection #' . $collection_id . ' already has product #' . $product_id . '. ';
                                        continue;
                                    } else {
                                        $message .= 'Product #';
                                        $collection_product = null;
                                        $collection_product = new CollectionProduct();
                                        $collection_product->collection_id = $collection_id;
                                        $collection_product->product_id = $product_id;
                                        $result = $collection_product->save();
                                        if ($result) {
                                            $message .= ' #' . $product_id . ' ';
                                        } else {
                                            return back()->with('error', 'Error occurred when add product #' . $product_id . ' to collection #' . $collection_id);
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
                return back()->with('error', 'Please select products to take action!');
            }
        } else {
            return back()->with('error', 'Please select an action!');
        }
    }
}
