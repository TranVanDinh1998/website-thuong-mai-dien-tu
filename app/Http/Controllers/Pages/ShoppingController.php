<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\Category;
use App\Collection;
use App\CollectionProduct;
use App\ProductImage;
use App\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShoppingController extends Controller
{
    public function index()
    {
        // cart
        $shopping_carts = session()->get('cart');
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
        return view('pages.cart', [
            'shopping_carts' => $shopping_carts,
            'total_cart' => $total_cart,
        ]);
    }

    public function add_to_cart(Request $request)
    {
        $product = Product::find($request->id);
        // add product to shopping cart
        if ($product->remaining == 0 || $product->is_actived == 0) {
            return response()->json([
                'error' => true,
                'message' => 'The product ' . $product->name . ' is out of stock!'
            ]);
        } else {
            $cart = session()->get('cart');

            // if cart is empty then this is the first product of the cart
            if (!$cart) {
                $cart = [
                    $request->id => [
                        'product_name' => $product->name,
                        'product_image' => $product->image,
                        'product_quantity' => $request->quantity,
                        'product_price' => $product->price,
                        'product_discount' => $product->discount,
                    ]
                ];
                session()->put('cart', $cart);
                return response()->json([
                    'error' => false,
                    'message' => 'The product ' . $product->name . ' was added to the shopping cart.'
                ]);
            }

            // if cart is not empty then check if this product exist before then change the chapter
            if (isset($cart[$request->id])) {
                $cart[$request->id]['product_quantity'] += $request->quantity;
                session()->put('cart', $cart);
                return response()->json([
                    'error' => false,
                    'message' => 'Increase the quantity of product ' . $product->name . ' in the shopping cart.'
                ]);
            }

            //if product not exist in cart then add product into cart
            $cart[$request->id] = [
                'product_name' => $product->name,
                'product_image' => $product->image,
                'product_quantity' => $request->quantity,
                'product_price' => $product->price,
                'product_discount' => $product->discount,
            ];
            session()->put('cart', $cart);
            return response()->json([
                'error' => false,
                'message' => 'The product ' . $product->name . ' was added to the shopping cart.'
            ]);
        }
    }
}
