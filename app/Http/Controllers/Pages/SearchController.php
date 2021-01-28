<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\Producer;
use App\Collection;
use App\Tag;
use App\CollectionProduct;
use App\Http\Controllers\Admin\CollectionProductController;
use App\TagProduct;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SearchController extends Controller
{
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
        //collections
        $collections = Collection::where('is_deleted', 0)->where('is_actived', 1)->get();
        // view
        $recent_views = session()->get('recent_views');

        // products
        $products = Product::notDelete()->active()->search($request)->sort($request)->price($request)->producer($request)->category($request);

        // producer
        $producers = Producer::where('is_deleted', 0)->where('is_actived', 1)->get();
        $producers_record_count = array();
        foreach ($producers as $producer) {
            $producers_record_count[$producer->id] = 0;
        }
        $search_products = $products->get();
        $search_producers_record_count = array();
        foreach ($search_products as $product) {
            if (isset($search_producers_record_count[$product->producer_id])) {
                $search_producers_record_count[$product->producer_id] += 1;
            } else {
                $search_producers_record_count[$product->producer_id] = 1;
            }
        }
        foreach ($producers_record_count as $producer_id => $count) {
            foreach ($search_producers_record_count as $search_producer_id => $search_count) {
                if ($producer_id == $search_producer_id) {
                    $producers_record_count[$producer_id] = $search_count;
                }
            }
        }

        // paginate
        if ($request->has('view')) {
            $products = $products->paginate($request->view);
        } else {
            $products = $products->paginate(12);
        }

        // filter
        $search = $request->search;
        $sort = null;
        $view = null;
        $producer_id = null;
        $price_from = null;
        $price_to = null;
        $sort = $request->sort;
        $view = $request->view;
        $producer_id = $request->producer_id;
        $price_from = $request->price_from;
        $price_to = $request->price_to;

        // compare
        $compare = session()->get('compare');
        $count_compare = null;
        if (isset($compare)) {
            foreach ($compare as $product_id => $info) {
                $count_compare += 1;
            }
        }
        return view('pages.search', [
            // cart
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,
            // collections
            'search_collections' => $collections,
            // product
            'products' => $products,
            //producer
            'producers_record_count' => $producers_record_count,
            // filter
            'search' => $search,
            'sort' => $sort,
            'view' => $view,
            'producer_id' => $producer_id,
            'price_from' => $price_from,
            'price_to' => $price_to,
            // view
            'recent_views' => $recent_views,
            // action
            'current_page' => 'search',

            // compare
            'compare' => $compare,            
            'count_compare' => $count_compare,
        ]);
    }
}
