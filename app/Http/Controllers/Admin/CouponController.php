<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Coupon;
use App\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(Request $request)
    {
        // coupons
        $coupons = Coupon::notDelete()->search($request)->sortId($request)->status($request);
        $count_coupon = 0;
        $view = 0;
        $count_coupon = $coupons->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $coupons = $coupons->paginate($view);
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        // search
        $search = $request->search;
        // user
        $user = Auth::guard('admin')->user();

        return view('admin.coupon.index', [
            // coupons
            'coupons' => $coupons,
            'count_coupon' => $count_coupon,
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

    public function doActivate($id)
    {
        $coupon = Coupon::find($id);
        $coupon->is_actived = 1;
        if ($coupon->save()) {
            return back()->with('success', 'Coupon #' . $coupon->id . ' has been activated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDeactivate($id)
    {
        $coupon = Coupon::find($id);
        $coupon->is_actived = 0;
        if ($coupon->save()) {
            return back()->with('success', 'Coupon #' . $coupon->id . ' has been deactivated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function add()
    {
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.coupon.add', [
            // user
            'current_user' => $user,
        ]);
    }

    public function doAdd(Request $request)
    {
        // return $request->all();
        switch ($request->type) {
            case 0:
                $validate = Validator::make(
                    $request->all(),
                    [
                        'name' => 'required',
                        'code' => 'required',
                        'quantity' => 'required',
                        'expire_date' => 'required|date',
                        'minimum_order_value' => 'required|min:0',
                        'type' => 'required|',
                        'discount' => 'required|min:0'
                    ],
                    [
                        'required' => ':attribute must be filled',
                        'min' => ':attribute is not smaller than :min ',
                        'date' => ':attribute must be a date',
                    ]
                );
                break;
            case 1:
                $validate = Validator::make(
                    $request->all(),
                    [
                        'name' => 'required',
                        'code' => 'required',
                        'quantity' => 'required',
                        'expire_date' => 'required|date',
                        'minimum_order_value' => 'required|min:0',
                        'type' => 'required|min:0|max:1',
                        'discount' => 'required|min:0|max:100'
                    ],
                    [
                        'required' => ':attribute must be filled',
                        'min' => ':attribute must not be smaller than :min ',
                        'max' => ':attribute must not be bigger than :max',
                        'date' => ':attribute must be a date',
                    ]
                );
                break;
        }
        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors(),
            ]);
        } else {
            $coupon = new Coupon();
            $coupon->name = $request->name;
            $coupon->code = $request->code;
            $coupon->quantity = $request->quantity;
            $coupon->remaining = $request->quantity;
            $coupon->expire_date = $request->expire_date;
            $coupon->minimum_order_value = $request->minimum_order_value;
            $coupon->type = $request->type;
            $coupon->discount = $request->discount;
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
                while (file_exists("/uploads/coupons-images/" . $avatar)) {
                    $avatar = Str::random(4) . "_" . $name;
                }
                $file->move(public_path() . '/uploads/coupons-images/', $avatar);
                $coupon->image = $avatar;
            } else {
                $coupon->image = null;
            }
            $result = $coupon->save();
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
        $coupon =  Coupon::find($id);
        return view('admin.coupon.edit', [
            'coupon' => $coupon,
            // user
            'current_user' => $user,
        ]);
    }
    public function doEdit(Request $request)
    {
        switch ($request->type) {
            case 0:
                $validate = Validator::make(
                    $request->all(),
                    [
                        'name' => 'required',
                        'code' => 'required',
                        'quantity' => 'required',
                        'expire_date' => 'required|date',
                        'minimum_order_value' => 'required|min:0',
                        'type' => 'required|',
                        'discount' => 'required|min:0'
                    ],
                    [
                        'required' => ':attribute must be filled',
                        'min' => ':attribute is not smaller than :min ',
                        'date' => ':attribute must be a date',
                    ]
                );
                break;
            case 1:
                $validate = Validator::make(
                    $request->all(),
                    [
                        'name' => 'required',
                        'code' => 'required',
                        'quantity' => 'required',
                        'expire_date' => 'required|date',
                        'minimum_order_value' => 'required|min:0',
                        'type' => 'required|min:0|max:1',
                        'discount' => 'required|min:0|max:100'
                    ],
                    [
                        'required' => ':attribute must be filled',
                        'min' => ':attribute must not be smaller than :min ',
                        'max' => ':attribute must not be bigger than :max',
                        'date' => ':attribute must be a date',
                    ]
                );
                break;
        }
        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors(),
            ]);
        } else {
            $coupon = Coupon::find($request->id);
            $coupon->name = $request->name;
            $coupon->code = $request->code;
            $coupon->quantity = $request->quantity;
            $coupon->remaining = $request->remaining;
            $coupon->expire_date = $request->expire_date;
            $coupon->minimum_order_value = $request->minimum_order_value;
            $coupon->type = $request->type;
            $coupon->discount = $request->discount;
            $result = $coupon->save();
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
        $coupon = Coupon::find($id);
        if ($coupon->expire_date < date('Y-m-d')){
            return back()->with('error', 'Coupon is still available.');
        }
        else{
            if ($coupon->remaining != 0) {
                return back()->with('error', 'Coupon is still available.');
            }
            else {
                $coupon->is_deleted = 1;
                if ($coupon->save()) {
                    return back()->with('success', 'Coupon #' . $coupon->id . ' has been removed.');
                } else {
                    return back()->with('error', 'Error occurred!');
                }
            }
        }

    }

    public function recycle(Request $request)
    {
        // coupons
        $coupons = coupon::softDelete()->search($request)->sortId($request)->status($request);
        $count_coupon = 0;
        $view = 0;
        $count_coupon = $coupons->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $coupons = $coupons->paginate($view);
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        // search
        $search = $request->search;
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.coupon.recycle', [
            // coupons
            'coupons' => $coupons,
            'count_coupon' => $count_coupon,
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
        $coupon = Coupon::find($id);
        $coupon->is_deleted = 0;
        if ($coupon->save()) {
            return back()->with('success', 'Coupon #' . $coupon->id . ' has been restored.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDelete($id)
    {
        $coupon = Coupon::find($id);
        if ($coupon->expire_date < date('Y-m-d')){
            return back()->with('error', 'Coupon is still available.');
        }
        else{
            if ($coupon->remaining != 0) {
                return back()->with('error', 'Coupon is still available.');
            }
            else {
                if ($coupon->forceDelete()) {
                    return back()->with('success', 'Coupon #' . $coupon->id . ' has been deleted.');
                } else {
                    return back()->with('error', 'Error occurred!');
                }
            }
        }
    }

    public function bulk_action(Request $request)
    {
        if ($request->has('bulk_action')) {
            if ($request->has('coupon_id_list')) {
                $message = null;
                switch ($request->bulk_action) {
                    case 0: // deactivate
                        $message = 'Coupon ';
                        foreach ($request->coupon_id_list as $coupon_id) {
                            $coupon = null;
                            $coupon = Coupon::find($coupon_id);
                            $coupon->is_actived = 0;
                            if ($coupon->save()) {
                                $message .= ' #' . $coupon->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred when deactivate coupon #' . $coupon->id);
                            }
                        }
                        $message .= 'have been deactivated.';
                        return back()->with('success', $message);
                        break;
                    case 1: // activate
                        $message = 'Coupon ';
                        foreach ($request->coupon_id_list as $coupon_id) {
                            $coupon = null;
                            $coupon = Coupon::find($coupon_id);
                            $coupon->is_actived = 1;
                            if ($coupon->save()) {
                                $message .= ' #' . $coupon->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred when activate coupon #' . $coupon->id);
                            }
                        }
                        $message .= 'have been activated.';
                        return back()->with('success', $message);
                        break;
                    case 2: // remove
                        $message = 'Coupon';
                        foreach ($request->coupon_id_list as $coupon_id) {
                            $coupon = null;
                            $coupon = Coupon::find($coupon_id);
                            if ($coupon->expire_date < date('Y-m-d')){
                                return back()->with('error', 'Coupon is still available.');
                            }
                            else{
                                if ($coupon->remaining != 0) {
                                    return back()->with('error', 'Coupon is still available.');
                                }
                                else {
                                    $coupon->is_deleted = 1;
                                    if ($coupon->save()) {
                                        $message .= ' #' . $coupon->id . ', ';
                                    } else {
                                    return back()->with('error', 'Error occurred while removing coupon #' . $coupon->id);
                                    }
                                }
                            }
                        }
                        $message .= 'have been removed.';
                        break;
                    case 3: // restore
                        $message = 'Coupon ';
                        foreach ($request->coupon_id_list as $coupon_id) {
                            $coupon = null;
                            $coupon = Coupon::find($coupon_id);
                            $coupon->is_deleted = 0;
                            if ($coupon->save()) {
                                $message .= ' #' . $coupon->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred when restore coupon #' . $coupon->id);
                            }
                        }
                        $message .= 'have been restored.';
                        return back()->with('success', $message);
                        break;
                    case 4: // delete
                        $message = 'Coupon';
                        foreach ($request->coupon_id_list as $coupon_id) {
                            $coupon = null;
                            $coupon = Coupon::find($coupon_id);
                            if ($coupon->expire_date < date('Y-m-d')){
                                return back()->with('error', 'Coupon is still available.');
                            }
                            else{
                                if ($coupon->remaining != 0) {
                                    return back()->with('error', 'Coupon is still available.');
                                }
                                else {
                                    if ($coupon->forceDelete()) {
                                        $message .= ' #' . $coupon->id . ', ';
                                    } else {
                                    return back()->with('error', 'Error occurred while deleting coupon #' . $coupon->id);
                                    }
                                }
                            }
                        }
                        $message .= 'have been deleted.';
                        break;
                    case 5: // extend period 
                        $message = 'Coupon ';
                        foreach ($request->coupon_id_list as $coupon_id) {
                            $coupon = null;
                            $coupon = Coupon::find($coupon_id);
                            if ($request->has('quantity') && $request->quantity != null) {
                                $coupon->quantity += $request->quantity;
                                $coupon->remaining += $request->quantity;
                            }
                            if ($request->has('expire_date') && $request->expire_date != null) {
                                $coupon->expire_date = $request->expire_date;
                            }
                            if ($coupon->save()) {
                                $message .= ' #' . $coupon->id . ' ';
                            } else {
                                return back()->with('error', 'Error occurred when deactivate coupon #' . $coupon->id);
                            }
                        }
                        $message .= 'have been extended.';
                        break;
                }
                return back()->with('success', $message);
            } else {
                return back()->with('error', 'Please select coupons to take action!');
            }
        } else {
            return back()->with('error', 'Please select an action!');
        }
    }
}
