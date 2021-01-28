<?php

namespace App\Http\Controllers\Pages\Account;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\Category;
use App\Collection;
use App\CollectionProduct;
use App\Order;
use App\ProductImage;
use App\Address;
use App\District;
use App\Province;
use App\Ward;
use App\Review;
use App\WishList;
use App\OrderDetail;
use App\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    public function __construct()
    {
        $this->middleware('userLogin');
    }

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
        // order
        $user = Auth::user();
        return view('pages.account.password', [
            // user
            'user' => $user,
            // cart
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,

        ]);
    }
    public function change_password(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'old_password' => 'required|min:6',
                'new_password' => 'required|min:6',
                'confirm_password' => 'required|min:6|same:new_password',
            ],
            [
                'required' => ':attribute must be filled',
                'min' => ':attribute must has at least 6 characters',
                'same' => 'two passwords must be the same'
            ]
        );
        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors(),
            ]);
        } else {
            $old_password = $request->old_password;
            $new_password = $request->new_password;
            $user_password = Auth::user()->password;
            if (Hash::check($old_password, $user_password)) {
                $user_id = Auth::user()->id;
                $user = User::find($user_id);
                $user->password = Hash::make($new_password);
                $result = $user->save();
                if ($result) {
                    return response()->json([
                        'error' => false,
                        'message' => 'Success',
                    ]);
                } else {
                    return response()->json([
                        'error' => true,
                        'message' => 'Error occurred!'
                    ]);
                }
            } else {
                $errors = new MessageBag(['old_password_incorrect' => 'Old password is incorrect!']);
                return response()->json([
                    'error' => true,
                    'message' => $errors
                ]);
            }
        }
    }
}
