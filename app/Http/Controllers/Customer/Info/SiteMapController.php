<?php

namespace App\Http\Controllers\Customer\Info;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class SiteMapController extends Controller
{
    public function __construct(Product $product, Category $category)
    {
        $this->product = $product;
        $this->category = $category;
    }
    public function category(Request $request)
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
        $user = Auth::user();
        // category
        $categories = $this->category->active()->sort($request);
        $count_categories = $categories->count();
        // paginate
        $categories = $request->has('view') ? $categories->paginate($request->view) : $categories->paginate(12);
        // sort
        $sort = null;
        $sort = $request->sort;
        $view = null;
        $view = $request->view;


        return view('pages.customer.information.site_map.category', [
            // cart
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,
            // user
            'user' => $user,
            //
            'categories' => $categories,
            'count_categories' => $count_categories,

            // filter
            'sort' => $sort,
            'view' => $view,

        ]);
    }

    public function product(Request $request)
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
        $user = Auth::user();
        // category
        $products = $this->product->active()->sort($request);
        $count_products = $products->count();
        // paginate
        $products = $request->has('view') ? $products->paginate($request->view) : $products->paginate(15);
        // sort
        $sort = null;
        $sort = $request->sort;
        $view = null;
        $view = $request->view;

        return view('pages.customer.information.site_map.product', [
            // cart
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,
            // user
            'user' => $user,
            //
            'products' => $products,
            'count_products' => $count_products,

            // filter
            'sort' => $sort,
            'view' => $view,

        ]);
    }


}
