<?php

namespace App\Http\Controllers\Customer\Info;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Collection;
use App\Models\Producer;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class AdvancedSearchController extends Controller
{
    public function __construct(Product $product, Collection $collection, Producer $producer)
    {
        $this->product = $product;
        $this->collection = $collection;
        $this->producer = $producer;
    }

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

        // user
        $user = null;
        $user = Auth::user();

        // searching
        $search = $request->search;
        $price_from = $request->price_from;
        $price_to = $request->price_to;
        $category_id_list = null;
        $category_id_list = $request->category_id_list;
        $producer_id_list = null;
        $producer_id_list = $request->producer_id_list;

        return view('pages.customer.information.advanced_search.index', [
            // cart
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,
            // user
            'user' => $user,
            //
            // filter
            'search' => $search,
            'price_from' => $price_from,
            'price_to' => $price_to,
            'category_id_list' => $category_id_list,
            'producer_id_list' => $producer_id_list,
        ]);
    }

    public function result(Request $request)
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

        //products
        $products = $this->product->active()->search($request)->sort($request)
        ->massCategory($request)
        ->massProducer($request)
        ->price($request);

        //producer
        $producers = $this->producer->active()->get();
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
        $products = $request->has('view') ? $products->paginate($request->view) : $products->paginate(12);


        // filter
        $search = null;
        $sort = null;
        $view = null;
        $price_from = null;
        $price_to = null;
        $search = $request->search;
        $sort = $request->sort;
        $view = $request->view;
        $price_from = $request->price_from;
        $price_to = $request->price_to;
        $category_id_list = null;
        $category_id_list = $request->category_id_list;
        $producer_id_list = null;
        $producer_id_list = $request->producer_id_list;

        // compare
        $compare = session()->get('compare');
        $count_compare = null;
        if (isset($compare)) {
            foreach ($compare as $product_id => $info) {
                $count_compare += 1;
            }
        }
        return view('pages.customer.information.advanced_search.result', [
            // cart
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,
            // product
            'products' => $products,
            //producer
            'producers_record_count' => $producers_record_count,
            // filter
            'search' => $search,
            'sort' => $sort,
            'view' => $view,
            'price_from' => $price_from,
            'price_to' => $price_to,
            'category_id_list' => $category_id_list,
            'producer_id_list' => $producer_id_list,
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
