<?php

namespace App\Http\Controllers\Customer\Info;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Tag;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class SearchTermsController extends Controller
{
    public function __construct(Tag $tag)
    {
        $this->tag = $tag;
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
        $user = Auth::user();

        // tag
        $tags = $this->tag->active()->sort($request);
        $count_tags = $tags->count();
        // paginate
        $tags = $request->has('view') ? $tags->paginate($request->view) : $tags->paginate(15);

        // sort
        $sort = null;
        $sort = $request->sort;
        $view = null;
        $view = $request->view;


        return view('pages.customer.information.search_term', [
            // cart
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,
            // user
            'user' => $user,
            //
            'tags' => $tags,
            'count_tags' => $count_tags,

            // filter
            'sort' => $sort,
            'view' => $view,

        ]);
    }



}
