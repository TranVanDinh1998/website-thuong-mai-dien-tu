<?php

namespace App\Http\Controllers\Pages;

use App\Address;
use App\Coupon;
use App\Ward;
use App\District;
use App\Province;
use App\Order;
use App\User;
use App\Product;
use App\Http\Controllers\Controller;
use App\OrderDetail;
use App\Payment;
use App\ShippingAddress;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Validator;



class CheckOutController extends Controller
{

    public function index()
    {
        // cart
        $shopping_carts = session()->get('cart');
        $discount_cart = session()->get('discount');
        $count_cart = null;
        if (isset($shopping_carts)) {
            foreach ($shopping_carts as $product_id => $info) {
                $count_cart += 1;
            }
        }
        $total_cart = null;
        if (isset($shopping_carts)) {
            foreach ($shopping_carts as $product_id => $info) {
                if ($info['product_discount'] != null) {
                    $total_cart +=  ($info['product_price'] - ($info['product_price'] * $info['product_discount']) / 100) * $info['product_quantity'];
                } else {
                    $total_cart += $info['product_price'] * $info['product_quantity'];
                }
            }
        }

        // user address
        $addresses = null;
        if ((Auth::user())) {
            $addresses = Address::where('user_id', Auth::user()->id)->get();
        }
        $wards = Ward::get();
        $districts = District::get();
        $provinces = Province::get();

        // check out 
        // session()->forget('check_out');
        $check_out = null;
        $check_out = session()->get('check_out');
        // print_r($check_out);

        // print_r($addresses[1]);

        return view('pages.check_out', [
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,
            'addresses' => $addresses,
            'wards' => $wards,
            'districts' => $districts,
            'provinces' => $provinces,
            'check_out' => $check_out,
        ]);
    }

    // shipping check out
    public function get_shipping_address()
    {
        $check_out = session()->get('check_out');
        $shipping_address = null;
        if (isset($check_out['shipping_address'])) {
            $shipping_address = $check_out['shipping_address'];
        }
        if ($shipping_address == null) {
            return response()->json([
                'error' => true,
                'message' => 'None',
            ]);
        } else {
            $address =
                '<address> ' .
                $shipping_address["name"] . ' <br> ' .
                $shipping_address["address"] . ' <br>' .
                $shipping_address["ward"] . ' , ' .
                $shipping_address["district"] . ' , ' .
                $shipping_address["province"] . ' <br>
             T: ' . $shipping_address["number"] . ' <br>
            </address>';
            return response()->json([
                'error' => false,
                'message' => $address,
            ]);
        }
    }

    public function shipping_check_out(Request $request)
    {
        // current user
        $user = null;
        $user = Auth::user();

        // select an existed address
        if ($request->shipping_address_id != 0 && $request->shipping_address_id != null) {
            $check_out = session()->get('check_out');
            $address = Address::find($request->shipping_address_id);
            $ward = Ward::find($address->ward_id);
            $district = District::find($address->district_id);
            $province = Province::find($address->province_id);

            // if check out is null
            if (!$check_out) {
                $check_out = [
                    'shipping_address' => [
                        'name' => $address->name,
                        'number' => $address->number,
                        'address' => $address->address,
                        'ward_id' => $ward->ward_id,
                        'ward' => $ward->name,
                        'district_id' => $district->district_id,
                        'district' => $district->name,
                        'province_id' => $province->province_id,
                        'province' => $province->name,
                    ]
                ];
                session()->put('check_out', $check_out);
                return response()->json([
                    'error' => false,
                    'message' => 'Shipping address has been saved.',
                ]);
            }
            // if shipping address is exist

            // shipping check out
            $check_out['shipping_address']['name'] = $address->name;
            $check_out['shipping_address']['number'] = $address->number;
            $check_out['shipping_address']['address'] = $address->address;
            $check_out['shipping_address']['ward_id'] = $ward->id;
            $check_out['shipping_address']['ward'] = $ward->name;
            $check_out['shipping_address']['district_id'] = $district->id;
            $check_out['shipping_address']['district'] = $district->name;
            $check_out['shipping_address']['province_id'] = $province->id;
            $check_out['shipping_address']['province'] = $province->name;

            session()->put('check_out', $check_out);
            return response()->json([
                'error' => false,
                'message' => 'Shipping address has been saved.',
            ]);
        } else {
            $validate = Validator::make(
                $request->all(),
                [
                    'name' => 'required',
                    'address' => 'required',
                    'number' => 'required|min:10',
                    'ward_id' => 'required',
                    'district_id' => 'required',
                    'province_id' => 'required',
                ],
                [
                    'required' => ':attribute must be filled',
                    'min' => ':attribute is not smaller than :min characters',
                ]
            );
            if ($validate->fails()) {
                return response()->json([
                    'error' => true,
                    'message' => $validate->errors(),
                ]);
            } else {
                // get session check out
                $check_out = session()->get('check_out');
                $ward = Ward::find($request->ward_id);
                $district = District::find($request->district_id);
                $province = Province::find($request->province_id);
                // if check out is null
                if (!$check_out) {
                    $check_out = [
                        'shipping_address' => [
                            'name' => $request->name,
                            'number' => $request->number,
                            'address' => $request->address,
                            'ward_id' => $ward->ward_id,
                            'ward' => $ward->name,
                            'district_id' => $district->district_id,
                            'district' => $district->name,
                            'province_id' => $province->province_id,
                            'province' => $province->name,
                        ],
                    ];
                    session()->put('check_out', $check_out);
                    return response()->json([
                        'error' => false,
                        'message' => 'Shipping address has been saved.',
                    ]);
                }

                // if shipping address is exist
                $check_out['shipping_address']['name'] = $request->name;
                $check_out['shipping_address']['number'] = $request->number;
                $check_out['shipping_address']['address'] = $request->address;
                $check_out['shipping_address']['ward_id'] = $ward->id;
                $check_out['shipping_address']['ward'] = $ward->name;
                $check_out['shipping_address']['district_id'] = $district->id;
                $check_out['shipping_address']['district'] = $district->name;
                $check_out['shipping_address']['province_id'] = $province->id;
                $check_out['shipping_address']['province'] = $province->name;
                session()->put('check_out', $check_out);
                if ($request->save_address == 1) {
                    if ($user == null) {
                        return response()->json([
                            'error' => true,
                            'message' => new MessageBag(['errorUser' => 'You need an account to save addresses!']),
                        ]);
                    }
                    $address = new Address();
                    $address->name = $request->name;
                    $address->user_id = $user->id;
                    $address->address = $request->address;
                    $address->number = $request->number;
                    $address->ward_id = $request->ward_id;
                    $address->district_id = $request->district_id;
                    $address->province_id = $request->province_id;
                    $result = $address->save();
                    if ($result) {
                        return response()->json([
                            'error' => false,
                            'message' => 'A new address has been added and the shipping address have been saved.',
                        ]);
                    } else {
                        return response()->json([
                            'error' => true,
                            'message' => new MessageBag(['errorAddress' => 'Error occurred!'])
                        ]);
                    }
                }
                return response()->json([
                    'error' => false,
                    'message' => 'The shipping address have been saved.',
                ]);
            }
        }
    }

    // payment check out
    public function get_payment()
    {
        $check_out = session()->get('check_out');
        $payment_method = null;
        if (isset($check_out['payment'])) {
            $payment_method = $check_out['payment'];
        }
        if ($payment_method == null) {
            return response()->json([
                'error' => true,
                'message' => 'None',
            ]);
        } else {
            if ($payment_method['credit'] == 0) {
                $payment = 'Check / Money order';
            } else {
                $payment = 'Credit card <br>';
                $payment .= 'Owner: ' . $payment_method['name'] . '<br>';
                switch ($payment_method['type']) {
                    case 1:
                        $payment .= 'Type: American Express' . '<br>';
                        break;
                    case 2:
                        $payment .= 'Type: Visa' . '<br>';
                        break;
                    case 3:
                        $payment .= 'Type: MasterCard' . '<br>';
                        break;
                    case 4:
                        $payment .= 'Type: Discover' . '<br>';
                        break;
                }
                $payment .= 'Expiration time: ' . $payment_method['expire_month'] . '/' . $payment_method['expire_year'] . '<br>';
            }

            return response()->json([
                'error' => false,
                'message' => $payment,
            ]);
        }
    }

    public function payment_check_out(Request $request)
    {
        // select payment method
        switch ($request->payment_method) {
            case 0:
                $check_out = session()->get('check_out');
                // if check out is null
                if (!$check_out) {
                    $check_out = [
                        'payment' => [
                            'credit' => 0
                        ]
                    ];
                    session()->put('check_out', $check_out);
                    return response()->json([
                        'error' => false,
                        'message' => 'Payment method has been saved.',
                    ]);
                }
                // if payment is exist
                // payment check out
                $check_out['payment']['credit'] = 0;
                session()->put('check_out', $check_out);
                return response()->json([
                    'error' => false,
                    'message' => 'Payment method has been set as Check / Money order.',
                ]);
                break;
            case 1:
                $validate = Validator::make(
                    $request->all(),
                    [
                        'name' => 'required',
                        'type' => 'required',
                        'card_number' => 'required',
                        'expire_month' => 'required',
                        'expire_year' => 'required',
                        'verification_number' => 'required',
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
                    // get session check out
                    $check_out = session()->get('check_out');
                    // if check out is null
                    if (!$check_out) {
                        $check_out = [
                            'payment' => [
                                'credit' => 1,
                                'name' => $request->name,
                                'type' => $request->type,
                                'card_number' => $request->card_number,
                                'expire_month' => $request->expire_month,
                                'expire_year' => $request->expire_year,
                                'verification_number' => $request->verification_number,
                            ],
                        ];
                        session()->put('check_out', $check_out);
                        return response()->json([
                            'error' => false,
                            'message' => 'Payment method has been saved.',
                        ]);
                    }
                    // if payment is exist
                    $check_out['payment']['credit'] = 1;
                    $check_out['payment']['name'] = $request->name;
                    $check_out['payment']['type'] = $request->type;
                    $check_out['payment']['card_number'] = $request->card_number;
                    $check_out['payment']['expire_month'] = $request->expire_month;
                    $check_out['payment']['expire_year'] = $request->expire_year;
                    $check_out['payment']['verification_number'] = $request->verification_number;
                    session()->put('check_out', $check_out);
                    return response()->json([
                        'error' => false,
                        'message' => 'Payment method has been set as Credit card (saved).',
                    ]);
                }
                break;
        }
    }

    public function final_check()
    {
        // current user
        $user = null;
        if (Auth::user()) {
            $user = Auth::user();
        } else {
            $user = User::notDelete()->active()->guest()->first();
        }
        // cart
        $shopping_carts = session()->get('cart');
        $discount = session()->get('discount');
        $check_out = session()->get('check_out');
        $shipping_address = null;
        if (isset($check_out['shipping_address'])) {
            $shipping_address = $check_out['shipping_address'];
        }
        $payment = null;
        if (isset($check_out['payment'])) {
            $payment = $check_out['payment'];
        }
        $total_cart = null;
        if (isset($shopping_carts)) {
            foreach ($shopping_carts as $product_id => $info) {
                if ($info['product_discount'] != null) {
                    $total_cart +=  ($info['product_price'] - ($info['product_price'] * $info['product_discount']) / 100) * $info['product_quantity'];
                } else {
                    $total_cart += $info['product_price'] * $info['product_quantity'];
                }
            }
        }

        if ($shopping_carts == null) {
            return back()->with('error', 'Your shopping cart is empty!');
        } else {
            $shopping_carts = session()->get('cart');
            foreach ($shopping_carts as $product_id => $info) {
                $product = null;
                $product = Product::find($product_id);
                if ($product->remaining < $info['product_quantity']) {
                    return back()->with('error', 'The Warehouse has only ' . $product->quantity . ' items of ' . $product->name . ' left!');
                }
            }
            if ($check_out == null) {
                return back()->with('error', 'You have not completed check out process yet!');
            } else {
                if ($shipping_address == null) {
                    return back()->with('error', 'You have not appointed the shipping address yet!');
                } else {
                    if ($payment == null) {
                        return back()->with('error', 'You have not appointed the payment method yet!');
                    } else {
                        $order = new Order();
                        $order->user_id = $user->id;
                        $order->sub_total = $total_cart;
                        if ($discount != null) {
                            $order->discount = $discount['coupon_discount'];
                            $order->total = $total_cart - $discount['coupon_discount'];
                        } else {
                            $order->discount = null;
                            $order->total = $total_cart;
                        }
                        // switch ($payment['credit']) {
                        //     case 0:
                        //         $order->is_paid = 0;
                        //         break;
                        //     case 1:
                        //         $order->is_paid = 1;
                        //         break;
                        // }
                        $order->is_paid = 0;
                        $order->status = 0;
                        $result = $order->save();
                        if ($result) {
                            // coupon
                            if ($discount != null) {
                                $coupon = Coupon::find($discount['coupon_id']);
                                if ($coupon) {
                                    if ($coupon->remaing <= 0 && date('Y-m-d') > $coupon->expire_date) {
                                        return back()->with('error', 'The coupon code is no longer usable!');
                                    } else {
                                        if ($total_cart < $coupon->minimum_order_value) {
                                            return back()->with('error', 'This coupon is only usable for minimum order of ' . $coupon->minimum_order_value . ' vnd');
                                        }
                                        else {
                                            $coupon->remaining -= 1;
                                            if (!$coupon->save()) {
                                                return back()->with('error', 'Error occured while applying coupon!');
                                            }
                                        }
                                    }
                                } 
                                else {
                                    return back()->with('error', 'The coupon is invalid!');
                                }
                            }
                            // shipping address
                            $shipping = new ShippingAddress();
                            $shipping->order_id = $order->id;
                            $shipping->name = $shipping_address['name'];
                            $shipping->number = $shipping_address['number'];
                            $shipping->address = $shipping_address['address'];
                            $shipping->ward = $shipping_address['ward'];
                            $shipping->district = $shipping_address['district'];
                            $shipping->province = $shipping_address['province'];
                            $shipping->save();
                            // payment
                            if ($payment['credit'] == 1) {
                                $transaction = new Payment();
                                $transaction->order_id = $order->id;
                                $transaction->name = $payment['name'];
                                $transaction->type = $payment['type'];
                                $transaction->card_number = $payment['card_number'];
                                $transaction->expire_month = $payment['expire_month'];
                                $transaction->expire_year = $payment['expire_year'];
                                $transaction->verification_number = $payment['verification_number'];
                                $transaction->save();
                            }
                            // order detail
                            foreach ($shopping_carts as $product_id => $info) {
                                $order_detail = null;
                                $order_detail = new OrderDetail();
                                $order_detail->order_id = $order->id;
                                $order_detail->product_id = $product_id;
                                $order_detail->product_discount = $info['product_discount'];
                                $order_detail->price = $info['product_price'];
                                $order_detail->quantity = $info['product_quantity'];
                                $sub_result = $order_detail->save();
                                $product = null;
                                $product = Product::find($product_id);
                                $product->remaining -= $info['product_quantity'];
                                $product->save();
                                if (!$sub_result) {
                                    return back()->with('error', 'Error occured when add order detail!');
                                }
                            }
                            session()->forget('cart');
                            session()->forget('discount');
                            session()->forget('check_out');
                            return redirect()->route('delivery', ['id' => $order->id]);
                        } else {
                            return back()->with('error', 'Error occured when add order!');
                        }
                    }
                }
            }
        }
    }
}
