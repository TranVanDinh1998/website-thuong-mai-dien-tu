<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\Collection;
use App\Producer;
use App\Category;
use App\CollectionProduct;
use App\Http\Controllers\Admin\CollectionProductController;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;

class FilterController extends Controller
{
    public function index($category_id, $collection_id = null, Request $request)
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
        // view
        $recent_views = session()->get('recent_views');
        // print_r($recent_views);

        // current category
        $current_category = null;
        $current_category = Category::find($category_id);
        $current_category_id = null;
        $current_category_id = $current_category->id;

        // products and collection
        $products = null;
        $current_collection = null;
        $current_collection_id = null;
        if ($collection_id == null) {
            $products = Product::notDelete()->active()->where('category_id', $category_id)->sort($request)->price($request)->producer($request);
        } else {
            $current_collection = Collection::find($collection_id);
            $current_collection_id = $current_collection->id;
            $current_collection_details = CollectionProduct::where('is_deleted', 0)->where('collection_id', $current_collection->id)->get();
            $current_collection_array = array();
            foreach ($current_collection_details as $detail) {
                $current_collection_array[] = $detail->product_id;
            }
            $products = Product::notDelete()->active()->whereIn('id', $current_collection_array)->sort($request)->price($request)->producer($request);
        }

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

        return view('pages.filter', [
            // cart
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,
            // category
            'current_category' => $current_category,
            'current_category_id' => $current_category_id,
            'current_collection' => $current_collection,
            'current_collection_id' => $current_collection_id,
            // product
            'products' => $products,
            //producer
            'producers_record_count' => $producers_record_count,
            // filter
            'sort' => $sort,
            'view' => $view,
            'producer_id' => $producer_id,
            'price_from' => $price_from,
            'price_to' => $price_to,
            // view
            'recent_views' => $recent_views,
            // page
            'current_page' => 'filter',

            // compare
            'compare' => $compare,
            'count_compare' => $count_compare,

        ]);
    }
}
