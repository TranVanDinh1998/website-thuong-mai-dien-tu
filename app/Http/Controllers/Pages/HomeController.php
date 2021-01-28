<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\Collection;
use App\Advertise;
use App\CollectionProduct;
use App\Coupon;
use App\Order;
use App\OrderDetail;
use App\Tag;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;



class HomeController extends Controller
{
    public function index()
    {
        // advertise
        $advertises = Advertise::notDelete()->active()->get();

        // all products
        $products = Product::active()->notDelete()->orderByDesc('create_date')->get();


        // best sell products
        // $delivered_orders = Order::notDelete()->where('status', 3)->get();
        // $delivered_orders_array = array();
        // foreach ($delivered_orders as $delivered_order) {
        //     $delivered_orders_array[] = $delivered_order->id;
        // }
        // $order_details = OrderDetail::whereIn('order_id', $delivered_orders_array)->get();
        // $seller_record = array();
        // foreach ($order_details as $order_detail) {
        //     if (isset($seller_record[$order_detail->product_id])) {
        //         $seller_record[$order_detail->product_id] += $order_detail->quantity;
        //     }
        //     else{
        //         $seller_record[$order_detail->product_id] = $order_detail->quantity;
        //     }
        // }
        // $seller_record = collect($seller_record)->sortDesc()->toArray();
        // $best_seller_record = array();
        // foreach($seller_record as $product_id => $value) {
        //     $best_seller_record[] = $product_id;
        // }
        // $best_seller_products = Product::active()->notDelete()->whereIn('id', $best_seller_record)->limit(10)->get();
        $best_seller_products = Product::notDelete()
        ->active()
        ->select('id','name','price','description','image','is_actived','is_deleted',DB::raw('quantity-remaining as leftover'))
        ->orderByDesc('leftover')
        ->limit(10)
        ->get();

        // new products
        $newest_products = Product::active()->notDelete()->orderByDesc('create_date')->limit(10)->get();
        // feature products
        $feature_products = Product::active()->notDelete()->orderByDesc('rating')->limit(10)->get();
        // sale products
        $sale_products = Product::active()->notDelete()->whereNotNull('discount')->limit(10)->get();

        // most imfortant collections
        $three_most_priority_collections = Collection::notDelete()->active()->orderByDesc('priority')->limit(3)->get();
        // second best collections
        $three_second_priority_collections = Collection::notDelete()->active()->orderByDesc('priority')->skip(3)->limit(3)->get();

        // newest collections and products respectively 
        $two_newest_collections = Collection::notDelete()->active()->orderByDesc('create_date')->limit(2)->get();

        // discount 
        $coupons = Coupon::notDelete()->active()->orderByDesc("create_date")->limit(3)->get();
        // cart
        $shopping_carts = session()->get('cart');
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

        return view('pages.home', [
            // advertise
            'advertises' => $advertises,
            // product
            // all product
            'products' => $products,
            // best sell products
            'best_seller_products' => $best_seller_products,
            // newest products
            'newest_products' => $newest_products,
            // feature products
            'feature_products' => $feature_products,
            // coupon
            'coupons' => $coupons,
            // sale products
            'sale_products' => $sale_products,

            // collection

            // newest collections
            'two_newest_collections' => $two_newest_collections,
            // 'two_newest_collections_products' => $two_newest_collection_products,
            
            // 

            'three_most_priority_collections' => $three_most_priority_collections,
            'three_second_priority_collections' => $three_second_priority_collections,

            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
        ]);
    }


}
