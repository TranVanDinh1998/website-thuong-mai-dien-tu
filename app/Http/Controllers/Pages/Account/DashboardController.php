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

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('userLogin');
    }

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
        // recent order
        $user = Auth::user();
        $recent_orders = Order::where('is_deleted', 0)->where('user_id', $user->id)->orderByDesc('create_date')->limit(4)->get();

        // user address
        $addresses = null;
        if ((Auth::user())) {
            $addresses = Address::where('user_id', Auth::user()->id)->get();
        }
        $wards = Ward::get();
        $districts = District::get();
        $provinces = Province::get();
        return view('pages.account.dashboard', [
            // user
            'user' => $user,
            // cart
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,
            // order
            'recent_orders' => $recent_orders,
            'addresses' => $addresses,
            'provinces' => $provinces,
            'districts' => $districts,
            'wards' => $wards,
        ]);
    }
}
