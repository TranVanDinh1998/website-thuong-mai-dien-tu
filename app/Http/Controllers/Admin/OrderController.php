<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Collection;
use App\order;
use App\Product;
use App\User;
use App\Address;
use App\Ward;
use App\District;
use App\OrderDetail;
use App\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(Request $request)
    {
        $orders = Order::notDelete()->notDone()
            ->status($request)->sortId($request)->active()
            ->sortTotal($request)->sortDate($request)->sortPaid($request);
        $count_order = 0;
        $view = 0;
        $count_order = $orders->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $orders = $orders->paginate($view);
        // address
        $addresses = Address::get();
        $wards = Ward::get();
        $districts = District::get();
        $provinces = Province::get();
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        $sort_total = $request->sort_total;
        $sort_date = $request->sort_date;
        $sort_paid = $request->sort_paid;
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.order.index', [
            // order
            'orders' => $orders,
            'count_order' => $count_order,
            'view' => $view,
            // address
            'addresses' => $addresses,
            'wards' => $wards,
            'districts' => $districts,
            'provinces' => $provinces,
            // filter
            'sort_id' => $sort_id,
            'sort_total' => $sort_total,
            'sort_date' => $sort_date,
            'status' => $status,
            'sort_paid' => $sort_paid,
            // current user
            'current_user' => $user,

        ]);
    }
    public function doActivate($id)
    {
        $order = Order::find($id);
        foreach ($order->order_details as $detail) {
            $product = null;
            $product = Product::find($detail->product_id);
            $product->remaining -= $detail->quantity;
            if (!$product->save()) {
                return back()->with('error', 'Error occurred!');
            }
        }
        $order->is_actived = 1;
        if ($order->save()) {
            return back()->with('success', 'Order #' . $order->id . ' has been activated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDeactivate($id)
    {
        $order = Order::find($id);
        if ($order->is_paid == 1) {
            return back()->with('error', 'Order #' . $order->id . ' has already been paid.');
        } else {
            foreach ($order->order_details as $detail) {
                $product = null;
                $product = Product::find($detail->product_id);
                $product->remaining += $detail->quantity;
                if (!$product->save()) {
                    return back()->with('error', 'Error occurred!');
                }
            }
            $order->is_actived = 0;
            if ($order->save()) {
                return back()->with('success', 'Order #' . $order->id . ' has been deactivated.');
            } else {
                return back()->with('error', 'Error occurred!');
            }
        }
    }

    public function doConfirm($id)
    {
        $order = Order::find($id);
        if ($order->is_paid == 0) {
            return back()->with('error', 'Order #' . $order->id . ' hasn\'t been paid yet.');
        } else {
            $order->is_done = 1;
            $order->delivery_date = date('Y-m-d');
            $order->status = 3;
            if ($order->save()) {
                return back()->with('success', 'Order #' . $order->id . ' has been completed.');
            } else {
                return back()->with('error', 'Error occurred!');
            }
        }
    }

    public function doUnConfirm($id)
    {
        $order = Order::find($id);
        $order->delivery_date = null;
        $order->is_done = 0;
        if ($order->save()) {
            return back()->with('success', 'Order #' . $order->id . ' has been delayed.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doPaid($id)
    {
        $order = Order::find($id);
        $order->is_paid = 1;
        if ($order->save()) {
            return back()->with('success', 'Order #' . $order->id . ' has been paid.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doUnPaid($id)
    {
        $order = Order::find($id);
        $order->is_paid = 0;
        if ($order->save()) {
            return back()->with('success', 'Order #' . $order->id . ' hasn\'t been paid yet.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function history(Request $request)
    {
        $orders = Order::notDelete()
            ->status($request)->sortId($request)->done()
            ->sortTotal($request)->sortDate($request)->sortPaid($request);
        $count_order = 0;
        $view = 0;
        $count_order = $orders->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $orders = $orders->paginate($view);
        // address
        $addresses = Address::get();
        $wards = Ward::get();
        $districts = District::get();
        $provinces = Province::get();
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        $sort_total = $request->sort_total;
        $sort_date = $request->sort_date;
        $sort_paid = $request->sort_paid;
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.order.history', [
            // order
            'orders' => $orders,
            'count_order' => $count_order,
            'view' => $view,
            // address
            'addresses' => $addresses,
            'wards' => $wards,
            'districts' => $districts,
            'provinces' => $provinces,
            // filter
            'sort_id' => $sort_id,
            'sort_total' => $sort_total,
            'sort_date' => $sort_date,
            'status' => $status,
            'sort_paid' => $sort_paid,
            'current_user' => $user,

        ]);
    }

    public function cancel(Request $request)
    {
        $orders = Order::notDelete()
            ->status($request)->sortId($request)->inactive()
            ->sortTotal($request)->sortDate($request)->sortPaid($request);
        $count_order = 0;
        $view = 0;
        $count_order = $orders->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $orders = $orders->paginate($view);
        // address
        $addresses = Address::get();
        $wards = Ward::get();
        $districts = District::get();
        $provinces = Province::get();
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        $sort_total = $request->sort_total;
        $sort_date = $request->sort_date;
        $sort_paid = $request->sort_paid;
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.order.cancel', [
            // order
            'orders' => $orders,
            'count_order' => $count_order,
            'view' => $view,
            // address
            'addresses' => $addresses,
            'wards' => $wards,
            'districts' => $districts,
            'provinces' => $provinces,
            // filter
            'sort_id' => $sort_id,
            'sort_total' => $sort_total,
            'sort_date' => $sort_date,
            'status' => $status,
            'sort_paid' => $sort_paid,
            'current_user' => $user,

        ]);
    }

    public function detail($id)
    {
        $order = Order::find($id);
        $user = User::where('is_deleted', 0)->where('id', $order->user_id);
        // address
        $addresses = Address::get();
        $wards = Ward::get();
        $districts = District::get();
        $provinces = Province::get();
        // order detail
        $order_details = OrderDetail::where('order_id', $order->id)->get();
        $order_details_array = array();
        foreach ($order_details as $detail) {
            $order_details_array[] = $detail->product_id;
        }
        $order_detail_products = Product::where('is_deleted', 0)->whereIn('id', $order_details_array)->get();
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.order.detail', [
            // order
            'order' => $order,
            'order_details' => $order_details,
            'order_detail_products' => $order_detail_products,
            'current_user' => $user,
            // address
            'addresses' => $addresses,
            'wards' => $wards,
            'districts' => $districts,
            'provinces' => $provinces,
            //
            'current_user' => $user,

        ]);
    }

    public function update(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'status' => 'required|min:0|max:3',

            ],
            [
                'required' => ':attribute must be filled',
                'min' => ':attribute is invalid',
                'max' => ':attribute is invalid'
            ]
        );
        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors(),
            ]);
        } else {
            $order = Order::find($request->id);
            $order->status = $request->status;
            $order->is_paid = $request->payment;
            $result = $order->save();
            if ($result) {
                return response()->json([
                    'error' => false,
                    'message' => 'Success'
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Error occurred'
                ]);
            }
        }
    }

    public function doRemove($id)
    {
        $order = Order::find($id);
        if ($order->is_done == 0) {
            return back()->with('error', 'Order #' . $order->id . ' has not been finished yet.');
        } else {
            if ($order->is_paid == 1) {
                return back()->with('error', 'Order #' . $order->id . ' has been paid.');
            } else {
                $count_relative_order_detail = OrderDetail::where('order_id', $order->id)->count();
                if ($count_relative_order_detail == 0) {
                    $order->is_deleted = 1;
                    if ($order->save()) {
                        return back()->with('success', 'order ' . $order->id . ' has been removed.');
                    } else {
                        return back()->with('error', 'Error occurred!');
                    }
                } else {
                    return back()->with('error', 'order #' . $order->id . ' has order\'s details.');
                }
            }
        }
    }
    public function recycle(Request $request)
    {
        $orders = Order::softDelete()
            ->status($request)->sortId($request)
            ->sortTotal($request)->sortDate($request)->sortPaid($request);
        $count_order = 0;
        $view = 0;
        $count_order = $orders->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $orders = $orders->paginate($view);
        // address
        $addresses = Address::get();
        $wards = Ward::get();
        $districts = District::get();
        $provinces = Province::get();
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        $sort_total = $request->sort_total;
        $sort_date = $request->sort_date;
        $sort_paid = $request->sort_paid;
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.order.recycle', [
            // order
            'orders' => $orders,
            'count_order' => $count_order,
            'view' => $view,
            // address
            'addresses' => $addresses,
            'wards' => $wards,
            'districts' => $districts,
            'provinces' => $provinces,
            // filter
            'sort_id' => $sort_id,
            'sort_total' => $sort_total,
            'sort_date' => $sort_date,
            'status' => $status,
            'sort_paid' => $sort_paid,
            // current user
            'current_user' => $user,

        ]);
    }
    public function doRestore($id)
    {
        $order = Order::find($id);
        $order->is_deleted = 0;
        $order->save();
        if ($order->save()) {
            return back()->with('success', 'order ' . $order->id . ' has been restored.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDelete($id)
    {
        $order = Order::find($id);
        if ($order->is_done == 0) {
            return back()->with('error', 'Order #' . $order->id . ' has not been finished yet.');
        } else {
            if ($order->is_paid == 1) {
                return back()->with('error', 'Order #' . $order->id . ' has been paid.');
            } else {
                $count_relative_order_detail = OrderDetail::where('order_id', $order->id)->count();
                if ($count_relative_order_detail == 0) {
                    if ($order->forceDelete()) {
                        return back()->with('success', 'order ' . $order->id . ' has been deleted.');
                    } else {
                        return back()->with('error', 'Error occurred!');
                    }
                } else {
                    return back()->with('error', 'order #' . $order->id . ' has order\'s details.');
                }
            }
        }
    }

    public function bulk_action(Request $request)
    {
        if ($request->has('bulk_action')) {
            if ($request->has('order_id_list')) {
                $message = null;
                switch ($request->bulk_action) {
                    case 0: // deactivate
                        $message = 'Order ';
                        foreach ($request->order_id_list as $order_id) {
                            $order = null;
                            $order = Order::find($order_id);
                            $order->is_actived = 0;
                            if ($order->save()) {
                                $message .= ' #' . $order->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while deactivating order #' . $order->id);
                            }
                        }
                        $message .= 'have been deactivated.';
                        return back()->with('success', $message);
                        break;
                    case 1: // activate
                        $message = 'Order ';
                        foreach ($request->order_id_list as $order_id) {
                            $order = null;
                            $order = Order::find($order_id);
                            $order->is_actived = 1;
                            if ($order->save()) {
                                $message .= ' #' . $order->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while activating order #' . $order->id);
                            }
                        }
                        $message .= 'have been activated.';
                        return back()->with('success', $message);
                        break;
                    case 2: // done
                        $message = 'Order ';
                        foreach ($request->order_id_list as $order_id) {
                            $order = null;
                            $order = Order::find($order_id);
                            $order->is_done = 1;
                            if ($order->save()) {
                                $message .= ' #' . $order->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while completing order #' . $order->id);
                            }
                        }
                        $message .= 'have been completed.';
                        return back()->with('success', $message);
                        break;
                    case 3: // un done
                        $message = 'Order ';
                        foreach ($request->order_id_list as $order_id) {
                            $order = null;
                            $order = Order::find($order_id);
                            $order->is_done = 0;
                            if ($order->save()) {
                                $message .= ' #' . $order->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while marking order #' . $order->id . ' as incomplete one');
                            }
                        }
                        $message .= 'have been marking as incomplete one(s).';
                        return back()->with('success', $message);
                        break;
                    case 4: // paid
                        $message = 'Order ';
                        foreach ($request->order_id_list as $order_id) {
                            $order = null;
                            $order = Order::find($order_id);
                            $order->is_paid = 1;
                            if ($order->save()) {
                                $message .= ' #' . $order->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while marking order #' . $order->id . ' as paid one');
                            }
                        }
                        $message .= 'have been marking as paid one(s).';
                        return back()->with('success', $message);
                        break;
                    case 5: // un paid
                        $message = 'order ';
                        foreach ($request->order_id_list as $order_id) {
                            $order = null;
                            $order = Order::find($order_id);
                            $order->is_paid = 0;
                            if ($order->save()) {
                                $message .= ' #' . $order->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while marking order #' . $order->id . ' as unpaid one');
                            }
                        }
                        $message .= 'have been mark as unpaid one(s).';
                        return back()->with('success', $message);
                        break;
                    case 6: // remove
                        $message = 'order';
                        foreach ($request->order_id_list as $order_id) {
                            $order = null;
                            $order = Order::find($order_id);
                            $count_relative_order_detail = OrderDetail::where('order_id', $order_id)->count();
                            if ($count_relative_order_detail == 0) {
                                $order->is_deleted = 1;
                                if ($order->save()) {
                                    $message .= ' #' . $order->id . ', ';
                                } else {
                                    return back()->with('error', 'Error occurred when remove order #' . $order->id);
                                }
                            } else {
                                return back()->with('error', 'order #' . $order->id . ' has order\'s details.');
                            }
                        }
                        $message .= 'have been removed.';
                        break;
                    case 7: // restore
                        $message = 'order ';
                        foreach ($request->order_id_list as $order_id) {
                            $order = null;
                            $order = Order::find($order_id);
                            $order->is_deleted = 0;
                            if ($order->save()) {
                                $message .= ' #' . $order->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred when restore order #' . $order->id);
                            }
                        }
                        $message .= 'have been restored.';
                        return back()->with('success', $message);
                        break;
                    case 8: // delete
                        $message = 'order ';
                        foreach ($request->order_id_list as $order_id) {
                            $order = null;
                            $order = Order::find($order_id);
                            if ($order->forceDelete()) {
                                $message .= ' #' . $order->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred when deleted order #' . $order->id);
                            }
                        }
                        $message .= 'have been deleted.';
                        return back()->with('success', $message);
                        break;
                }
                return back()->with('success', $message);
            } else {
                return back()->with('error', 'Please select orders to take action!');
            }
        } else {
            return back()->with('error', 'Please select an action!');
        }
    }
}
