<?php

namespace App\Http\Controllers\Customer\Account;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\Collection;
use App\Models\CollectionProduct;
use App\Models\Order;
use App\Models\ProductImage;
use App\Models\Address;
use App\Models\District;
use App\Models\Province;
use App\Models\Ward;
use App\Models\Review;
use App\Models\WishList;
use App\Models\OrderDetail;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    public function __construct(WishList $wishList, Product $product)
    {
        $this->middleware('auth');
        $this->wishList = $wishList;
        $this->product = $product;
    }


    // wish list

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
                if ($info['product_discount'] != null) {
                    $total_cart +=  ($info['product_price'] - ($info['product_price'] * $info['product_discount']) / 100) * $info['product_quantity'];
                } else {
                    $total_cart += $info['product_price'] * $info['product_quantity'];
                }
            }
        }

        // wishlist
        $user = Auth::user();
        $wishLists = $user->wishLists;

        return view('pages.customer.account.wishList', [
            // cart
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,

            // wish list
            'wishLists' => $wishLists,

        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $product = $this->product->find($request->id);
        $wishLists = $user->wishLists->where('product_id', $request->id)->get();
        if (count($wishLists) > 0) {
            return response()->json([
                'error' => true,
                'message' => 'Sản phẩm '.$product->name.' đã có trong danh sách ưu thích của bạn.',
            ]);
        } else {
            $wishList = $this->wishList->create(['user_id'=>$user->id,'product_id'=>$product->id,'quantity'=>1]);
            if ($wishList) {
                return response()->json([
                    'error' => false,
                    'message' => 'Sản phẩm '.$product->name.' đã được thêm vào danh sách ưu thích của bạn.',
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => 'Xảy ra lỗi trong quá trình thêm sản phẩm vào danh sách ưu thích.',
                ]);
            }
        }
    }

    public function update(Request $request)
    {
        if ($request->id == null) {
            return back()->with('error', 'Mã sản phẩm ưu thích không hợp lệ.');
        }
        $wishList = $this->wishList->find($request->id);
        $result = $wishList->update(['note'=>$request->note,'quantity'=>$request->quantity]);
        if ($result) {
            return back()->with('success', 'Thành phần #' . $wishList->id . ' trong danh sách ưu thích đã được cập nhật.');
        } else {
            return back()->with('error', 'Xảy ra lỗi trong quá trình cập nhật danh sách ưu thích.');
        }
    }

    public function destroy($id)
    {
        $wishList = $this->wishList->find($id);
        if ($wishList->forceDelete()) {
            return back()->with('success', 'Thành phần #' . $wishList->id . ' đã bị xóa khỏi danh sách ưu thích.');
        } else {
            return back()->with('error', 'Xảy ra lỗi trong quá trình xóa thành phần trong danh sách ưu thích.');
        }
    }

    public function cart()
    {
        // wishlist
        $wishLists = $this->wishList->where('user_id', Auth::user()->id)->get();

        foreach ($wishLists as $wishList) {
            $product = Product::find($wishList->product_id);
            // add product to shopping cart
            if ($product->remaining == 0 || $product->is_actived == 0) {
                return back()->with('error', 'The product ' . $product->name . ' is out of stock!');
            } else {
                $cart = session()->get('cart');
                // if cart is empty then this is the first product of the cart
                if (!$cart) {
                    $cart = [
                        $wishList->product_id => [
                            'product_name' => $product->name,
                            'product_image' => $product->image,
                            'product_quantity' => $wishList->quantity,
                            'product_price' => $product->price,
                            'product_discount' => $product->discount,
                        ]
                    ];
                    session()->put('cart', $cart);
                }
                // if cart is not empty then check if this product exist before then change the chapter
                if (isset($cart[$wishList->product_id])) {
                    $cart[$wishList->product_id]['product_quantity'] = $cart[$wishList->product_id]['product_quantity'] + $wishList->quantity;
                    session()->put('cart', $cart);
                }
                //if product not exist in cart then add product into cart
                $cart[$wishList->product_id] = [
                    'product_name' => $product->name,
                    'product_image' => $product->image,
                    'product_quantity' => $wishList->quantity,
                    'product_price' => $product->price,
                    'product_discount' => $product->discount,
                ];
                session()->put('cart', $cart);
            }
            $wishList->forceDelete();
        }
        return back()->with('success', 'All products in wish list were added to the shopping cart.');
    }

}
