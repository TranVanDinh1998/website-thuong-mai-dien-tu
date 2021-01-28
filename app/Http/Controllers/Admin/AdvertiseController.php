<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;
use App\Product;
use App\Advertise;

class AdvertiseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(Request $request)
    {
        // advertises
        $advertises = advertise::notDelete()->search($request)->sortId($request)->status($request);
        $count_advertise = 0;
        $view = 0;
        $count_advertise = $advertises->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $advertises = $advertises->paginate($view);
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        // search
        $search = $request->search;
        // user
        $user = Auth::guard('admin')->user();

        return view('admin.advertise.index', [
            // advertises
            'advertises' => $advertises,
            'count_advertise' => $count_advertise,
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
        $advertise = Advertise::find($id);
        $advertise->is_actived = 1;
        $advertise->save();
        if ($advertise->save()) {
            return back()->with('success', 'advertise ' . $advertise->name . ' has been activated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDeactivate($id)
    {
        $advertise = Advertise::find($id);
        $advertise->is_actived = 0;
        $advertise->save();
        if ($advertise->save()) {
            return back()->with('success', 'advertise ' . $advertise->name . ' has been deactivated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function add()
    {
        // user
        $user = Auth::guard('admin')->user();

        $products = Product::where('is_deleted', 0)->get();
        return view('admin.advertise.add', [
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
                'description' => 'required',
                'summary' => 'required',
                'image' => 'required',
                'product_id' => 'required',
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
            $advertise = new Advertise();
            $advertise->name = $request->name;
            $advertise->summary = $request->summary;
            $advertise->description = $request->description;
            $advertise->product_id = $request->product_id;
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
                while (file_exists("/uploads/advertises-images/" . $avatar)) {
                    $avatar = Str::random(4) . "_" . $name;
                }
                $file->move(public_path() . '/uploads/advertises-images/', $avatar);
                $advertise->image = $avatar;
            } else {
                $advertise->image = null;
            }
            $result = $advertise->save();
            if ($result) {
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

        $advertise =  Advertise::find($id);
        $products = Product::where('is_deleted', 0)->get();
        return view('admin.advertise.edit', [
            'advertise' => $advertise,
            'products' => $products,
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
                'summary' => 'required',
                'product_id' => 'required',
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
            $advertise = Advertise::find($request->id);
            $advertise->name = $request->name;
            $advertise->summary = $request->summary;
            $advertise->description = $request->description;
            $advertise->product_id = $request->product_id;
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
                while (file_exists("/uploads/advertises-images/" . $avatar)) {
                    $avatar = Str::random(4) . "_" . $name;
                }
                $file->move(public_path() . '/uploads/advertises-images/', $avatar);
                $advertise->image = $avatar;
            }
            $result = $advertise->save();
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
        $advertise = Advertise::find($id);
        $advertise->is_deleted = 1;
        if ($advertise->save()) {
            return back()->with('success', 'Advertise #' . $advertise->id . ' has been removed.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function recycle(Request $request)
    {
        // advertises
        $advertises = advertise::softDelete()->search($request)->sortId($request)->status($request);
        $count_advertise = 0;
        $view = 0;
        $count_advertise = $advertises->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $advertises = $advertises->paginate($view);
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        // search
        $search = $request->search;
        // user
        $user = Auth::guard('admin')->user();

        return view('admin.advertise.recycle', [
            // advertises
            'advertises' => $advertises,
            'count_advertise' => $count_advertise,
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
        $advertise = Advertise::find($id);
        $advertise->is_deleted = 0;
        if ($advertise->save()) {
            return back()->with('success', 'Advertise #' . $advertise->id . ' has been restored.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDelete($id)
    {
        $advertise = Advertise::find($id);
        if ($advertise->forceDelete()) {
            return back()->with('success', 'Advertise #' . $advertise->id . ' has been deleted.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }
    public function bulk_action(Request $request)
    {
        if ($request->has('bulk_action')) {
            if ($request->has('advertise_id_list')) {
                $message = null;
                switch ($request->bulk_action) {
                    case 0: // deactivate
                        $message = 'advertise ';
                        foreach ($request->advertise_id_list as $advertise_id) {
                            $advertise = null;
                            $advertise = advertise::find($advertise_id);
                            $advertise->is_actived = 0;
                            if ($advertise->save()) {
                                $message .= ' #' . $advertise->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while deactivating advertise #' . $advertise->id);
                            }
                        }
                        $message .= 'have been deactivated.';
                        return back()->with('success', $message);
                        break;
                    case 1: // activate
                        $message = 'advertise ';
                        foreach ($request->advertise_id_list as $advertise_id) {
                            $advertise = null;
                            $advertise = advertise::find($advertise_id);
                            $advertise->is_actived = 1;
                            if ($advertise->save()) {
                                $message .= ' #' . $advertise->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while activating advertise #' . $advertise->id);
                            }
                        }
                        $message .= 'have been activated.';
                        return back()->with('success', $message);
                        break;
                    case 2: // remove
                        $message = 'advertise';
                        foreach ($request->advertise_id_list as $advertise_id) {
                            $advertise = null;
                            $advertise = advertise::find($advertise_id);
                            $advertise->is_deleted = 1;
                            if ($advertise->save()) {
                                $message .= ' #' . $advertise->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while removing advertise #' . $advertise->id);
                            }
                        }
                        $message .= 'have been removed.';
                        break;
                    case 3: // restore
                        $message = 'advertise ';
                        foreach ($request->advertise_id_list as $advertise_id) {
                            $advertise = null;
                            $advertise = advertise::find($advertise_id);
                            $advertise->is_deleted = 0;
                            if ($advertise->save()) {
                                $message .= ' #' . $advertise->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while restoring advertise #' . $advertise->id);
                            }
                        }
                        $message .= 'have been restored.';
                        return back()->with('success', $message);
                        break;
                    case 4: // delete
                        $message = 'advertise ';
                        foreach ($request->advertise_id_list as $advertise_id) {
                            $advertise = null;
                            $advertise = advertise::find($advertise_id);
                            if ($advertise->forceDelete()) {
                                $message .= ' #' . $advertise->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while deleting advertise #' . $advertise->id);
                            }
                        }
                        $message .= 'have been deleted.';
                        return back()->with('success', $message);
                        break;
                }
                return back()->with('success', $message);
            } else {
                return back()->with('error', 'Please select advertises to take action!');
            }
        } else {
            return back()->with('error', 'Please select an action!');
        }
    }
}
