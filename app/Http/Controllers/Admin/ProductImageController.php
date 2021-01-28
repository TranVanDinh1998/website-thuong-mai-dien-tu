<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\product_image;
use App\Collection;
use App\ProductImage;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;



class ProductImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index($id, Request $request)
    {
        $product =  Product::find($id);
        $product_images = ProductImage::notDelete()->where('product_id', $id)
            ->sortId($request)->status($request);

        $count_image = 0;
        $view = 0;
        $count_image = $product_images->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $product_images = $product_images->paginate($view);

        // user
        $user = Auth::guard('admin')->user();
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;

        return view('admin.gallery.index', [
            'product' => $product,
            'count_image' => $count_image,
            'product_images' => $product_images,
            'view' => $view,
            //
            'current_user' => $user,
            // filter
            'sort_id' => $sort_id,
            'status' => $status,
        ]);
    }

    public function doActivate($id, $image_id)
    {
        $product_image = ProductImage::find($image_id);
        $product_image->is_actived = 1;
        if ($product_image->save()) {
            return back()->with('success', 'Image #' . $product_image->id . ' has been activated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDeactivate($id, $image_id)
    {
        $product_image = ProductImage::find($image_id);
        $product_image->is_actived = 0;
        if ($product_image->save()) {
            return back()->with('success', 'Image #' . $product_image->id . ' has been deactivated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function add($id)
    {
        $product =  Product::find($id);
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.gallery.add', [
            'product' => $product,
            //
            'current_user' => $user,
        ]);
    }

    public function doAdd($id,Request $request)
    {
        // return $request->all();

        $validate = Validator::make(
            $request->all(),
            [
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
            $product_image = new ProductImage();
            $product_image->product_id = $id;
            $result = $product_image->save();
            if ($result) {
                $path = public_path('uploads/products-images/' .  $product_image->product_id);
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
                    while (file_exists("/uploads/products-images/" . $product_image->product_id . "/" . $avatar)) {
                        $avatar = Str::random(4) . "_" . $name;
                    }
                    $file->move(public_path() . '/uploads/products-images/' . $product_image->product_id, $avatar);
                    $product_image->image = $avatar;
                } else {
                    $product_image->image = null;
                }
                $result = $product_image->save();
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

    public function edit($id,$image_id)
    {
        $product =  Product::find($id);
        $product_image = ProductImage::find($image_id);
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.gallery.edit', [
            'product' => $product,
            'product_image'=> $product_image,
            //
            'current_user' => $user,
        ]);
    }

    public function doEdit($id,Request $request)
    {
        // return $request->all();

        $validate = Validator::make(
            $request->all(),
            [
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
            $product_image = ProductImage::find($request->image_id);
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
                while (file_exists("/uploads/products-images/" . $product_image->product_id . "/" . $avatar)) {
                    $avatar = Str::random(4) . "_" . $name;
                }
                $file->move(public_path() . '/uploads/products-images/' . $product_image->product_id, $avatar);
                $product_image->image = $avatar;
            }
            $result = $product_image->save();
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

    public function doRemove($id, $image_id)
    {
        $product_image = ProductImage::find($image_id);
        $product_image->is_deleted = 1;
        if ($product_image->save()) {
            return back()->with('success', 'Image #' . $product_image->id . ' has been removed.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }
    public function recycle($id,Request $request)
    {
        $product =  Product::find($id);
        $product_images = ProductImage::softDelete()->where('product_id', $id)
            ->sortId($request)->status($request);

        $count_image = 0;
        $view = 0;
        $count_image = $product_images->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $product_images = $product_images->paginate($view);

        // user
        $user = Auth::guard('admin')->user();
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;

        return view('admin.gallery.recycle', [
            'product' => $product,
            'count_image' => $count_image,
            'product_images' => $product_images,
            'view' => $view,
            //
            'current_user' => $user,
            // filter
            'sort_id' => $sort_id,
            'status' => $status,
        ]);
    }
    public function doRestore($id, $image_id)
    {
        $product_image = ProductImage::find($image_id);
        $product_image->is_deleted = 0;
        $product_image->save();
        if ($product_image->save()) {
            return back()->with('success', 'Image #' . $product_image->id . ' has been restored.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDelete($id, $image_id)
    {
        $product_image = ProductImage::find($image_id);
        if ($product_image->forceDelete()) {
            return back()->with('success', 'Product_image #' . $product_image->id . ' has been deleted.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function bulk_action(Request $request)
    {
        if ($request->has('bulk_action') && $request->bulk_action != null) {
            if ($request->has('product_image_id_list')) {
                $message = null;
                $error = null;
                switch ($request->bulk_action) {
                    case 0: // deactivate
                        $message = 'Image ';
                        foreach ($request->product_image_id_list as $image_id) {
                            $image = null;
                            $image = ProductImage::find($image_id);
                            $image->is_actived = 0;
                            if ($image->save()) {
                                $message .= ' #' . $image->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while deactivating image #' . $image->id);
                            }
                        }
                        $message .= 'have been deactivated.';
                        return back()->with('success', $message);
                        break;
                    case 1: // activate
                        $message = 'Image ';
                        foreach ($request->product_image_id_list as $image_id) {
                            $image = null;
                            $image = ProductImage::find($image_id);
                            $image->is_actived = 1;
                            if ($image->save()) {
                                $message .= ' #' . $image->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while activating image #' . $image->id);
                            }
                        }
                        $message .= 'have been activated.';
                        return back()->with('success', $message);
                        break;
                    case 2: // remove
                        $message = 'Image ';
                        foreach ($request->product_image_id_list as $image_id) {
                            $image = null;
                            $image = ProductImage::find($image_id);
                            $image->is_deleted = 1;
                            if ($image->save()) {
                                $message .= ' #' . $image->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while deleting image #' . $image->id);
                            }
                        }
                        $message .= 'have been removed.';
                        return back()->with('success', $message);
                        break;
                    case 3: // restore
                        $message = 'Image ';
                        foreach ($request->product_image_id_list as $image_id) {
                            $image = null;
                            $image = ProductImage::find($image_id);
                            $image->is_deleted = 0;
                            if ($image->save()) {
                                $message .= ' #' . $image->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while restoring image #' . $image->id);
                            }
                        }
                        $message .= 'have been restored.';
                        return back()->with('success', $message);
                        break;
                    case 4: // delete
                        $message = 'Image ';
                        foreach ($request->product_image_id_list as $image_id) {
                            $image = null;
                            $image = ProductImage::find($image_id);
                            if ($image->forceDelete()) {
                                $message .= ' #' . $image->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while deleting image #' . $image->id);
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
                return back()->with('error', 'Please select products to take action!');
            }
        } else {
            return back()->with('error', 'Please select an action!');
        }
    }
}
