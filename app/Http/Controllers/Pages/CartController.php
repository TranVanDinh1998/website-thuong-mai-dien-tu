<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\Coupon;
use App\Category;
use App\Collection;
use App\CollectionProduct;
use App\ProductImage;
use App\Tag;
use App\WishList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
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
                if ($info['product_discount'] != null && $info['product_discount'] != 0) {
                    $total_cart +=  ($info['product_price'] - ($info['product_price'] * $info['product_discount'] / 100)) * $info['product_quantity'];
                } else {
                    $total_cart += $info['product_price'] * $info['product_quantity'];
                }
            }
        }

        return view('pages.cart', [
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,

            //similar products
            // 'similar_products' => $similar_products,
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
                $cart[$request->id]['product_quantity'] +=  $request->quantity;
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

    public function update_cart(Request $request)
    {
        $data = $request->all();
        $cart = session()->get('cart');

        $shopping_carts = session()->get('cart');
        foreach ($shopping_carts as $product_id => $info) {
            $product = null;
            $product = Product::find($product_id);
            if ($product->remaining > $data['quantity_' . $product_id]) {
                $cart[$product_id]['product_quantity'] = $data['quantity_' . $product_id];
                session()->put('cart', $cart);

                //if applying coupon
                $discount = session()->get('discount');
                if ($discount != null) {
                    $total_cart = null;
                    $shopping_carts = session()->get('cart');
                    if (isset($shopping_carts)) {
                        foreach ($shopping_carts as $product_id => $info) {
                            if ($info['product_discount'] != null && $info['product_discount'] != 0) {
                                $total_cart +=  ($info['product_price'] - ($info['product_price'] * $info['product_discount'] / 100)) * $info['product_quantity'];
                            } else {
                                $total_cart += $info['product_price'] * $info['product_quantity'];
                            }
                        }
                    }
                    $coupon = Coupon::find($discount['coupon_id']);
                    if (!$coupon) {
                        return response()->json([
                            'error' => true,
                            'message' => 'Shopping cart has been updated. But the coupon, which has been applied, is wrong, please check again!',
                        ]);
                    } else {
                        if ($coupon->remaing <= 0 && date('Y-m-d') > $coupon->expire_date) {
                            session()->forget('discount');
                            return response()->json([
                                'error' => true,
                                'message' => 'Shopping cart has been updated. But the coupon, which has been applied, is no longer usable!',
                            ]);
                        } else {
                            if ($total_cart < $coupon->minimum_order_value) {
                                session()->forget('discount');
                                return response()->json([
                                    'error' => true,
                                    'message' => 'Shopping cart has been updated. But the coupon, which has been applied, is only usable for minimum order of ' . $coupon->minimum_order_value . ' vnd',
                                ]);
                            } else {
                                $discount = session()->get('discount');
                                $discount['coupon_id'] = $coupon->id;
                                switch ($coupon->type) {
                                    case 0:
                                        $discount['coupon_discount'] = $coupon->discount;
                                        break;
                                    case 1:
                                        $discount['coupon_discount'] = $total_cart * $coupon->discount / 100;
                                        break;
                                }
                                session()->put('discount', $discount);
                                return response()->json([
                                    'error' => false,
                                    'message' => 'Shopping cart and the coupon has been updated.',
                                ]);
                            }
                        }
                    }
                }

            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'The Warehouse has only ' . $product->quantity . ' items of ' . $product->name . ' left!',
                ]);
            }
        }
        return response()->json([
            'error' => false,
            'message' => 'Updated cart.',
        ]);
    }

    public function apply_coupon(Request $request)
    {
        $total_cart = null;
        $shopping_carts = session()->get('cart');
        if (isset($shopping_carts)) {
            foreach ($shopping_carts as $product_id => $info) {
                if ($info['product_discount'] != null && $info['product_discount'] != 0) {
                    $total_cart +=  ($info['product_price'] - ($info['product_price'] * $info['product_discount'] / 100)) * $info['product_quantity'];
                } else {
                    $total_cart += $info['product_price'] * $info['product_quantity'];
                }
            }
        }
        $coupon = Coupon::notDelete()->active()->where('code', '=', $request->code)->first();
        if (!$coupon) {
            return response()->json([
                'error' => true,
                'message' => 'The coupon code is wrong, please check again!',
            ]);
        } else {
            if ($coupon->remaing <= 0 && date('Y-m-d') > $coupon->expire_date) {
                return response()->json([
                    'error' => true,
                    'message' => 'The coupon code is no longer usable!',
                ]);
            } else {
                if ($total_cart < $coupon->minimum_order_value) {
                    return response()->json([
                        'error' => true,
                        'message' => 'This coupon is only usable for minimum order of ' . $coupon->minimum_order_value . ' vnd',
                    ]);
                } else {
                    $discount = session()->get('discount');
                    $discount['coupon_id'] = $coupon->id;
                    switch ($coupon->type) {
                        case 0:
                            $discount['coupon_discount'] = $coupon->discount;
                            // echo $discount['coupon_discount'];
                            break;
                        case 1:
                            $discount['coupon_discount'] = $total_cart * $coupon->discount / 100;
                            // echo $discount['coupon_discount'];
                            break;
                    }
                    session()->put('discount', $discount);
                    return response()->json([
                        'error' => false,
                        'message' => 'Coupon has been applied.',
                    ]);
                }
            }
        }
    }

    public function remove_coupon()
    {
        if (session()->forget('discount') == false) {
            return response()->json([
                'error' => false,
                'message' => 'The coupon has been removed.',
            ]);
        }
        else {
            return response()->json([
                'error' => true,
                'message' => 'Error occoured!',
            ]);
        }
    }

    public function remove_item_from_cart(Request $request)
    {
        $cart = session()->get('cart');
        $product = Product::find($request->id);
        if (isset($cart[$request->id])) {
            unset($cart[$request->id]);
            session()->put('cart', $cart);
            return response()->json([
                'error' => false,
                'message' => 'Product ' . $product->name . ' has been removed from your cart.',
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Product id is invalid!',
            ]);
        }
    }

    public function remove_cart()
    {
        if (session()->forget('cart') == false) {
            session()->forget('discount');
            return response()->json([
                'error' => false,
                'message' => 'The entire shopping cart has been removed.',
            ]);
        } else {
            return response()->json([
                'error' => true,
                'message' => 'Error occoured!',
            ]);
        }
    }

    public function check()
    {
        // check product
        $shopping_carts = session()->get('cart');
        foreach ($shopping_carts as $product_id => $info) {
            $product = null;
            $product = Product::find($product_id);
            if ($product->remaining < $info['product_quantity']) {
                return response()->json([
                    'error' => true,
                    'message' => 'The Warehouse has only ' . $product->quantity . ' items of ' . $product->name . ' left!',
                ]);
            }
        }
        // check coupon
        $total_cart = null;
        if (isset($shopping_carts)) {
            foreach ($shopping_carts as $product_id => $info) {
                if ($info['product_discount'] != null && $info['product_discount'] != 0) {
                    $total_cart +=  ($info['product_price'] - ($info['product_price'] * $info['product_discount'] / 100)) * $info['product_quantity'];
                } else {
                    $total_cart += $info['product_price'] * $info['product_quantity'];
                }
            }
        }
        $discount = session()->get('discount');
        $coupon = Coupon::find($discount['coupon_id']);
        if ($coupon) {
            if ($coupon->remaing <= 0 && date('Y-m-d') > $coupon->expire_date) {
                return response()->json([
                    'error' => true,
                    'message' => 'The coupon code is no longer usable!',
                ]);
            } else {
                if ($total_cart < $coupon->minimum_order_value) {
                    return response()->json([
                        'error' => true,
                        'message' => 'This coupon is only usable for minimum order of ' . $coupon->minimum_order_value . ' vnd',
                    ]);
                }
            }
        } 
        return response()->json([
            'error' => false,
            'message' => 'Process to next step.',
        ]);
    }
}
