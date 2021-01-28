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

class TagController extends Controller
{
    public function index($tag_id, Request $request)
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

        // current tag
        $current_tag = Tag::find($tag_id);
        $current_tag_id = $current_tag->id;
        $current_tag->view += 1;
        $current_tag->save();

        // products
        $products_array = array();
        $tag_products = TagProduct::where('is_deleted', 0)->where('tag_id', $current_tag_id)->get();
        foreach ($tag_products as $tag_product) {
            $products_array[] = $tag_product->product_id;
        }
        $products = Product::notDelete()->active()->whereIn('id', $products_array)->sort($request)->price($request)->producer($request)->category($request);

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
        return view('pages.tag', [
            // cart
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,
            // collections
            'search_collections' => $collections,
            // tag
            'current_tag' => $current_tag,
            'current_tag_id' => $current_tag_id,
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
            //
            'current_page' => 'tag',
            // compare
            'compare' => $compare,
            'count_compare' => $count_compare,
        ]);
    }
}
