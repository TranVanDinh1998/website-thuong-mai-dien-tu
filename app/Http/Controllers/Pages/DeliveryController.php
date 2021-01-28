<?php

namespace App\Http\Controllers\Pages;

use App\Address;
use App\Ward;
use App\District;
use App\Province;
use App\Order;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;



class DeliveryController extends Controller
{

    public function index($id)
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
        $order = Order::find($id);
        return view('pages.delivery', [
            // shopping car
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,
            // your order
            'order' => $order,
        ]);
    }

}
