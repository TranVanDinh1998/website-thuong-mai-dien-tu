<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;



class LoginController extends Controller
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
                if ($info['product_discount'] != null) {
                    $total_cart +=  ($info['product_price'] - ($info['product_price'] * $info['product_discount']) / 100) * $info['product_quantity'];
                } else {
                    $total_cart += $info['product_price'] * $info['product_quantity'];
                }
            }
        }
        return view('pages.login', [
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,
        ]);
    }
    public function doLogin(Request $request)
    {
        $validate = Validator::make(
            $request->all(),
            [
                'email' => 'required|email',
                'password' => 'required|min:6|max:32',
            ],
            [
                'email.required' => 'Email is required',
                'email.email' => 'Email format is incorrect',
                'password.required' => 'Password is required',
                'password.min' => 'Password must have aleast 6 characters',
                'password.max' => 'Password must have amost 32 characters',
            ]
        );
        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors(),
            ]);
        } else {
            $email = $request->email;
            $password = $request->password;

            $login = Auth::attempt(['email' => $email, 'password' => $password, 'is_actived' => 1, 'is_deleted' => 0]);
            if ($login) {
                return response()->json([
                    'error' => false,
                    'message' => 'Success'
                ]);
            } else {
                $errors = new MessageBag(['errorLogin' => 'Email or password is not right!']);
                return response()->json([
                    'error' => true,
                    'message' => $errors
                ]);
            }
        }
    }
}
