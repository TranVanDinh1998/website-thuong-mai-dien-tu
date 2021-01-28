<?php

namespace App\Http\Controllers\Pages\Account;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\Category;
use App\Collection;
use App\CollectionProduct;
use App\Order;
use App\ProductImage;
use App\Address;
use App\District;
use App\Province;
use App\Ward;
use App\Review;
use App\WishList;
use App\OrderDetail;
use App\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function __construct()
    {
        $this->middleware('userLogin');
    }

    // order
    public function index(Request $request)
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
        // order
        $user = Auth::user();
        $all_orders = Order::notDelete()->where('user_id', $user->id)->orderByDesc('create_date');
        $count_orders = $all_orders->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 15;
        }
        $all_orders = $all_orders->paginate($view);

        // user address
        $addresses = null;
        if ((Auth::user())) {
            $addresses = Address::where('user_id', Auth::user()->id)->get();
        }
        $wards = Ward::get();
        $districts = District::get();
        $provinces = Province::get();
        return view('pages.account.order.index', [
            // user
            'user' => $user,
            // cart
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,
            // order
            'all_orders' => $all_orders,
            'addresses' => $addresses,
            'provinces' => $provinces,
            'districts' => $districts,
            'wards' => $wards,
            // view
            'count_orders' => $count_orders,
            'view' => $view,
        ]);
    }

    public function detail($id)
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
        // order
        $order = Order::find($id);
        $order_details = OrderDetail::where('order_id', $order->id)->get();
        $order_details_array = array();
        foreach ($order_details as $detail) {
            $order_details_array[] = $detail->product_id;
        }
        $order_detail_products = Product::notDelete()->whereIn('id', $order_details_array)->get();
        $user = Auth::user();

        // user address
        $addresses = null;
        if ((Auth::user())) {
            $addresses = Address::where('user_id', Auth::user()->id)->get();
        }
        $wards = Ward::get();
        $districts = District::get();
        $provinces = Province::get();
        return view('pages.account.order.details', [
            // user
            'user' => $user,
            // cart
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,
            // order
            'order' => $order,
            'order_details' => $order_details,
            'order_detail_products' => $order_detail_products,
            'addresses' => $addresses,
            'provinces' => $provinces,
            'districts' => $districts,
            'wards' => $wards,
        ]);
    }

    public function cancel($id)
    {
        $order = Order::find($id);
        if ($order->status < 1) {
            if ($order->is_paid == 0) {
                $order->is_actived = 0;
                if ($order->save()) {
                    return back()->with('success', 'Order #' . $order->id . ' was cancel.');
                } else {
                    return back()->with('error', 'Error occurred!');
                }
            }
            else {
                return  back()->with('error', 'The order is paid, unable to cancel.');
            }
        } else {
            return  back()->with('error', 'The order is already in proccess, unable to cancel.');
        }
    }

    public function re_order($id)
    {
        $order = Order::find($id);
        $order_details = OrderDetail::where('order_id', $order->id)->get();

        foreach ($order_details as $detail) {
            $product = Product::find($detail->product_id);
            // add product to shopping cart
            if ($product->remaining == 0 || $product->is_actived == 0) {
                return back()->with('error', 'The product ' . $product->name . ' is out of stock!');
            } else {
                $cart = session()->get('cart');
                // if cart is empty then this is the first product of the cart
                if (!$cart) {
                    $cart = [
                        $detail->product_id => [
                            'product_name' => $product->name,
                            'product_image' => $product->image,
                            'product_quantity' => $detail->quantity,
                            'product_price' => $product->price,
                            'product_discount' => $product->discount,
                        ]
                    ];
                    session()->put('cart', $cart);
                }
                // if cart is not empty then check if this product exist before then change the chapter
                if (isset($cart[$detail->product_id])) {
                    $cart[$detail->product_id]['product_quantity'] += $detail->quantity;
                    session()->put('cart', $cart);
                }
                //if product not exist in cart then add product into cart
                $cart[$detail->product_id] = [
                    'product_name' => $product->name,
                    'product_image' => $product->image,
                    'product_quantity' => $detail->quantity,
                    'product_price' => $product->price,
                    'product_discount' => $product->discount,
                ];
                session()->put('cart', $cart);
            }
        }
        return back()->with('success', 'All products in order #' . $order->id . ' were added to the shopping cart.');
    }

}
