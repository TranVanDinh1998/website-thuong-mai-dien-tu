<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Category;
use App\Collection;
use App\CollectionProduct;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;



class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(Request $request)
    {
        // categories
        $categories = Category::notDelete()->search($request)->sortId($request)->status($request);
        $count_category = 0;
        $view = 0;
        $count_category = $categories->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $categories = $categories->paginate($view);
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        // search
        $search = $request->search;
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.category.index', [
            // categories
            'categories' => $categories,
            'count_category' => $count_category,
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
    public function doActivate($id)
    {
        $category = Category::find($id);
        $category->is_actived = 1;
        if ($category->save()) {
            return back()->with('success', 'Category #' . $category->id . ' has been activated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDeactivate($id)
    {
        $category = Category::find($id);
        $category->is_actived = 0;
        if ($category->save()) {
            return back()->with('success', 'Category #' . $category->id . ' has been deactivated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function add()
    {
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.category.add', [
            'current_user' => $user,

        ]);
    }

    public function doAdd(Request $request)
    {
        // return $request->all();

        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'description' => 'required',
                'image' => 'required|image',
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
            $category = new Category();
            $category->name = $request->name;
            $category->description = $request->description;
            $result = $category->save();
            if ($result) {
                $path = public_path('uploads/categories-images/' . $category->id);
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
                    while (file_exists("/uploads/categories-images/" . $category->id . "/" . $avatar)) {
                        $avatar = Str::random(4) . "_" . $name;
                    }
                    $file->move(public_path() . '/uploads/categories-images/' . $category->id, $avatar);
                    $category->image = $avatar;
                } else {
                    $category->image = null;
                }
                $result = $category->save();
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
        // $validate = Validator::make(
        //     $request->all(),
        //     [
        //         'name' => 'required',
        //         'description' => 'required',
        //         'image' => 'required|image',
        //     ],
        //     [
        //         'required' => ':attribute must be filled',
        //         'image' => ':attribute must be an image'
        //     ]
        // );
        // if ($validate->fails()) {
        //     return response()->json([
        //         'error' => true,
        //         'message' => $validate->errors(),
        //     ]);
        // } else {
        //     $category = Category::add($request->all());
        //     if ($category) {
        //         $path = public_path('uploads/categories-images/' . $category->id);
        //         if (!File::isDirectory($path)) {
        //             File::makeDirectory($path, 0777, true, true);
        //         }
        //         if ($request->hasFile('image')) {
        //             $file = $request->file('image');
        //             $format = $file->getClientOriginalExtension();
        //             if ($format != 'jpg' && $format != 'png' && $format != 'jpeg') {
        //                 $errors = new MessageBag(['errorImage' => 'File is not an image!']);
        //                 return response()->json([
        //                     'error' => true,
        //                     'message' => $errors,
        //                 ]);
        //             }
        //             $name = $file->getClientOriginalName();
        //             $avatar = Str::random(4) . "_" . $name;
        //             while (file_exists("/uploads/categories-images/" . $category->id . "/" . $avatar)) {
        //                 $avatar = Str::random(4) . "_" . $name;
        //             }
        //             $file->move(public_path() . '/uploads/categories-images/' . $category->id, $avatar);
        //             $category->image = $avatar;
        //         } else {
        //             $category->image = null;
        //         }
        //         $result = $category->save();
        //         return response()->json([
        //             'error' => false,
        //             'message' => 'Success'
        //         ]);
        //     } else {
        //         $errors = new MessageBag(['errorAdd' => 'Error occurred!']);
        //         return response()->json([
        //             'error' => true,
        //             'message' => $errors
        //         ]);
        //     }
        // }
    }

    public function edit($id)
    {
        // user
        $user = Auth::guard('admin')->user();
        $category =  Category::find($id);
        return view('admin.category.edit', [
            'category' => $category,
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
            $category = Category::find($request->id);
            $category->name = $request->name;
            $category->description = $request->description;
            $path = public_path('uploads/categories-images/' . $request->id);
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
                while (file_exists("/uploads/categories-images/" . $request->id . "/" . $avatar)) {
                    $avatar = Str::random(4) . "_" . $name;
                }
                $file->move(public_path() . '/uploads/categories-images/' . $request->id, $avatar);
                $category->image = $avatar;
            }
            $result = $category->save();
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
        $category = Category::find($id);
        $count_products = Product::where('category_id', $category->id)->count();
        if ($count_products == 0) {
            $count_collection = Collection::where('category_id', $category->id)->count();
            if ($count_collection == 0) {
                $category->is_deleted = 1;
                if ($category->save()) {
                    return back()->with('success', 'Category #' . $category->id . ' has been removed.');
                } else {
                    return back()->with('error', 'Error occurred!');
                }
            } else {
                return back()->with('error', 'Category #' . $category->id . ' relates to  ' . $count_collection . ' collections. Unable to remove');
            }
        } else {
            return back()->with('error', 'Category #' . $category->id . ' relates to  ' . $count_products . ' products. Unable to remove');
        }
    }
    public function recycle(Request $request)
    {
        // categories
        $categories = Category::softDelete()->search($request)->sortId($request)->status($request);
        $count_category = 0;
        $view = 0;
        $count_category = $categories->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $categories = $categories->paginate($view);
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        // search
        $search = $request->search;
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.category.recycle', [
            // categories
            'categories' => $categories,
            'count_category' => $count_category,
            'view' => $view,
            // filter
            'sort_id' => $sort_id,
            'status' => $status,
            // search
            'search' => $search,
            //
            'current_user' => $user,
        ]);
    }
    public function doRestore($id)
    {
        $category = Category::find($id);
        $category->is_deleted = 0;
        $category->save();
        if ($category->save()) {
            return back()->with('success', 'Category #' . $category->id . ' has been restored.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDelete($id)
    {
        $category = Category::find($id);
        $count_products = Product::where('category_id', $category->id)->count();
        if ($count_products == 0) {
            $count_collection = Collection::where('category_id', $category->id)->count();
            if ($count_collection == 0) {
                $category->is_deleted = 1;
                if ($category->forceDelete()) {
                    return back()->with('success', 'Category #' . $category->id . ' has been deleted.');
                } else {
                    return back()->with('error', 'Error occurred!');
                }
            } else {
                return back()->with('error', 'Category #' . $category->id . ' relates to  ' . $count_collection . ' collections. Unable to delete');
            }
        } else {
            return back()->with('error', 'Category #' . $category->id . ' relates to  ' . $count_products . ' products. Unable to delete');
        }
    }

    public function bulk_action(Request $request)
    {
        if ($request->has('bulk_action')) {
            if ($request->has('category_id_list')) {
                $message = null;
                switch ($request->bulk_action) {
                    case 0: // deactivate
                        $message = 'Category ';
                        foreach ($request->category_id_list as $category_id) {
                            $category = null;
                            $category = category::find($category_id);
                            $category->is_actived = 0;
                            if ($category->save()) {
                                $message .= ' #' . $category->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while deactivating category #' . $category->id);
                            }
                        }
                        $message .= 'have been deactivated.';
                        return back()->with('success', $message);
                        break;
                    case 1: // activate
                        $message = 'Category ';
                        foreach ($request->category_id_list as $category_id) {
                            $category = null;
                            $category = category::find($category_id);
                            $category->is_actived = 1;
                            if ($category->save()) {
                                $message .= ' #' . $category->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while activating category #' . $category->id);
                            }
                        }
                        $message .= 'have been activated.';
                        return back()->with('success', $message);
                        break;
                    case 2: // remove
                        $message = 'category';
                        foreach ($request->category_id_list as $category_id) {
                            $category = null;
                            $category = category::find($category_id);
                            $count_relative_product = Product::where('category_id', $category_id)->count();
                            if ($count_relative_product == 0) {
                                $count_relative_collection = Collection::where('category_id', $category->id)->count();
                                if ($count_relative_collection == 0) {
                                    $category->is_deleted = 1;
                                    $category->save();
                                    if ($category->save()) {
                                        $message .= ' #' . $category->id . ', ';
                                    } else {
                                        return back()->with('error', 'Error occurred while removing category #' . $category->id);
                                    }
                                } else {
                                    return back()->with('error', 'category #' . $category->id . ' has related ' . $count_relative_collection . ' collections.');
                                }
                            } else {
                                return back()->with('error', 'category #' . $category->id . ' relates to ' . $count_relative_product . ' products.');
                            }
                        }
                        $message .= 'have been removed.';
                        break;
                    case 3: // restore
                        $message = 'category ';
                        foreach ($request->category_id_list as $category_id) {
                            $category = null;
                            $category = Category::find($category_id);
                            $category->is_deleted = 0;
                            if ($category->save()) {
                                $message .= ' #' . $category->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while restoring category #' . $category->id);
                            }
                        }
                        $message .= 'have been restored.';
                        return back()->with('success', $message);
                        break;
                    case 4: // delete
                        $message = 'category';
                        foreach ($request->category_id_list as $category_id) {
                            $category = null;
                            $category = category::find($category_id);
                            $count_relative_product = Product::where('category_id', $category_id)->count();
                            if ($count_relative_product == 0) {
                                $count_relative_collection = Collection::where('category_id', $category->id)->count();
                                if ($count_relative_collection == 0) {
                                    if ($category->forceDelete()) {
                                        $message .= ' #' . $category->id . ', ';
                                    } else {
                                        return back()->with('error', 'Error occurred while removing category #' . $category->id);
                                    }
                                } else {
                                    return back()->with('error', 'category #' . $category->id . ' has related ' . $count_relative_collection . ' collections. Unable to delete');
                                }
                            } else {
                                return back()->with('error', 'category #' . $category->id . ' relates to ' . $count_relative_product . ' products. Unable to delete');
                            }
                        }
                        $message .= 'have been deleted.';
                        break;
                }
                return back()->with('success', $message);
            } else {
                return back()->with('error', 'Please select categories to take action!');
            }
        } else {
            return back()->with('error', 'Please select an action!');
        }
    }
}
