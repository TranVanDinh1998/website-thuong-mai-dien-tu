<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Producer;
use App\Product;
use App\Collection;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;

class ProducerController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(Request $request)
    {
        // producers
        $producers = producer::notDelete()->search($request)->sortId($request)->status($request);
        $count_producer = 0;
        $view = 0;
        $count_producer = $producers->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $producers = $producers->paginate($view);
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        // search
        $search = $request->search;
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.producer.index', [
            // producers
            'producers' => $producers,
            'count_producer' =>$count_producer,
            'view' => $view,
            // filter
            'sort_id' => $sort_id,
            'status' => $status,
            // search
            'search' => $search,
            'current_user' => $user,
        ]);
    }

    public function doActivate($id)
    {
        $producer = Producer::find($id);
        $producer->is_actived = 1;
        if ($producer->save()) {
            return back()->with('success', 'Producer #' . $producer->id . ' has been activated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDeactivate($id)
    {
        $producer = Producer::find($id);
        $producer->is_actived = 0;
        if ($producer->save()) {
            return back()->with('success', 'Producer #' . $producer->id . ' has been deactivated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function add()
    {
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.producer.add', [
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
            $producer = new Producer();
            $producer->name = $request->name;
            $producer->email = $request->email;
            $producer->address = $request->address;
            $producer->number = $request->number;
            $result = $producer->save();
            if ($result) {
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
                    while (file_exists("/uploads/producers-images/" . $avatar)) {
                        $avatar = Str::random(4) . "_" . $name;
                    }
                    $file->move(public_path() . '/uploads/producers-images/', $avatar);
                    $producer->image = $avatar;
                } else {
                    $producer->image = null;
                }
                $result = $producer->save();
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
        $producer =  Producer::find($id);
        return view('admin.producer.edit', [
            'producer' => $producer,
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
            $producer = Producer::find($request->id);
            $producer->name = $request->name;
            $producer->email = $request->email;
            $producer->address = $request->address;
            $producer->number = $request->number;
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
                while (file_exists("/uploads/producers-images/" . $avatar)) {
                    $avatar = Str::random(4) . "_" . $name;
                }
                $file->move(public_path() . '/uploads/producers-images/', $avatar);
                $producer->image = $avatar;
            }
            $result = $producer->save();
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
        $producer = Producer::find($id);
        $count_products = Product::where('producer_id', $producer->id)->count();
        if ($count_products == 0) {
            $producer->is_deleted = 1;
            $producer->save();
            if ($producer->save()) {
                return back()->with('success', 'Producer #' . $producer->id . ' has been removed.');
            } else {
                return back()->with('error', 'Error occurred!');
            }
        } else {
            return back()->with('error', 'This Producer relates to  ' . $count_products . ' products');
        }
    }

    public function recycle(Request $request)
    {
        // producers
        $producers = producer::softDelete()->search($request)->sortId($request)->status($request);
        $count_producer = 0;
        $view = 0;
        $count_producer = $producers->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $producers = $producers->paginate($view);
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        // search
        $search = $request->search;
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.producer.recycle', [
            // producers
            'producers' => $producers,
            'count_producer' =>$count_producer,
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
        $producer = Producer::find($id);
        $producer->is_deleted = 0;
        $producer->save();
        if ($producer->save()) {
            return back()->with('success', 'Producer #' . $producer->id . ' has been restored.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDelete($id)
    {
        $producer = Producer::find($id);
        if ($producer->forceDelete()) {
            return back()->with('success', 'Producer #' . $producer->id . ' has been deleted.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }
    public function bulk_action(Request $request)
    {
        if ($request->has('bulk_action')) {
            if ($request->has('producer_id_list')) {
                $message = null;
                switch ($request->bulk_action) {
                    case 0: // deactivate
                        $message = 'Producer ';
                        foreach ($request->producer_id_list as $producer_id) {
                            $producer = null;
                            $producer = Producer::find($producer_id);
                            $producer->is_actived = 0;
                            if ($producer->save()) {
                                $message .= ' #' . $producer->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred when deactivate producer #' . $producer->id);
                            }
                        }
                        $message .= 'have been deactivated.';
                        return back()->with('success', $message);
                        break;
                    case 1: // activate
                        $message = 'Producer ';
                        foreach ($request->producer_id_list as $producer_id) {
                            $producer = null;
                            $producer = Producer::find($producer_id);
                            $producer->is_actived = 1;
                            if ($producer->save()) {
                                $message .= ' #' . $producer->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred when activate producer #' . $producer->id);
                            }
                        }
                        $message .= 'have been activated.';
                        return back()->with('success', $message);
                        break;
                    case 2: // remove
                        $message = 'Producer';
                        foreach ($request->producer_id_list as $producer_id) {
                            $producer = null;
                            $producer = producer::find($producer_id);
                            $count_relative_product = Product::where('producer_id', $producer_id)->count();
                            if ($count_relative_product == 0) {
                                $producer->is_deleted = 1;
                                if ($producer->save()) {
                                    $message .= ' #' . $producer->id . ', ';
                                } else {
                                    return back()->with('error', 'Error occurred when remove producer #' . $producer->id);
                                }
                            } else {
                                return back()->with('error', 'producer #' . $producer->id . ' relates to ' . $count_relative_product . ' products.');
                            }
                        }
                        $message .= 'have been removed.';
                        break;
                    case 3: // restore
                        $message = 'Producer ';
                        foreach ($request->producer_id_list as $producer_id) {
                            $producer = null;
                            $producer = producer::find($producer_id);
                            $producer->is_deleted = 0;
                            if ($producer->save()) {
                                $message .= ' #' . $producer->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred when restore producer #' . $producer->id);
                            }
                        }
                        $message .= 'have been restored.';
                        return back()->with('success', $message);
                        break;
                    case 4: // delete
                        $message = 'Producer ';
                        foreach ($request->producer_id_list as $producer_id) {
                            $producer = null;
                            $producer = producer::find($producer_id);
                            if ($producer->forceDelete()) {
                                $message .= ' #' . $producer->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred when deleted producer #' . $producer->id);
                            }
                        }
                        $message .= 'have been deleted.';
                        return back()->with('success', $message);
                        break;
                }
                return back()->with('success', $message);
            } else {
                return back()->with('error', 'Please select producers to take action!');
            }
        } else {
            return back()->with('error', 'Please select an action!');
        }
    }
}
