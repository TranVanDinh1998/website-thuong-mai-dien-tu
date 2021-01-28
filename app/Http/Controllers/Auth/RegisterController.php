<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\User;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Str;


class RegisterController extends Controller
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
        return view('pages.register', [
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,
        ]);
    }

    public function doRegister(Request $request)
    {
        // $parameter = $request->all();
        // print_r($parameter);
        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                'email' => 'required|unique:users|email',
                'password' => 'required|min:6',
                're_password' => 'required|min:6|same:password'
            ],
            [
                'required' => ':attribute must be filled',
                'email' => ':attribute email format is incorrect',
                'max' => ':attribute is not greater than :max characters',
                'min' => ':attribute is not smaller than :min characters',
                'unique' => ':attribute existed',
                'same:password' => 'two passwords must be matched',
            ]
        );
        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors(),
            ]);
        } else {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->is_admin = 0;
            $user->is_deleted = 0;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $format = $file->getClientOriginalExtension();
                if ($format != 'jpg' && $format != 'png' && $format != 'gif') {
                    $errorMessage = new MessageBag(['errorImage' => 'File is not an image!']);
                    return response()->json([
                        'error' => true,
                        'message' => $errorMessage
                    ]);
                }
                $name = $file->getClientOriginalName();
                $avatar = Str::random(4) . '_' . $name;
                while (file_exists('uploads/users-images/' . $avatar)) {
                    $avatar = Str::random(4) . '_' . $name;
                }
                $file->move(public_path() . '/uploads/users-images/', $avatar);
                $user->image = $avatar;
            } else {
                $user->image = null;
            }
            $result = $user->save();
            if ($result) {
                return response()->json([
                    'error' => false,
                ]);
            } else {
                return response()->json([
                    'error' => true,
                    'message' => new MessageBag(['errorRegister' => 'Error occurred!'])
                ]);
            }
        }
    }
}
