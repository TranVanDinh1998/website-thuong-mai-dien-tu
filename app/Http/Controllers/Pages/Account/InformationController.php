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

class InformationController extends Controller
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

        // user
        $user = Auth::user();

        return view('pages.account.information', [
            // cart
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,
            // user
            'user' => $user,
        ]);
    }

    public function edit(Request $request)
    {

        $validate = Validator::make(
            $request->all(),
            [
                'name' => 'required',
                // 'email' => 'required|email|unique:users',
            ],
            [
                'required' => ':attribute must be filled!',
                // 'email' => ':attribute is invalid',
                // 'same' => ':attribute existed'
            ]
        );
        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors(),
            ]);
        } else {
            $user_id = Auth::user()->id;
            $user = User::find($user_id);
            $user->name = $request->name;
            if ($request->hasFile('image')) {
                $file = $request->file('image');
                $format = $file->getClientOriginalExtension();
                if ($format != 'jpg' && $format != 'png' && $format != 'jpeg') {
                    $errors = new MessageBag(['errorImage' => 'File is not an image!']);
                    return response()->json([
                        'error' => true,
                        'message' => $errors,
                    ]);
                }
                $name = $file->getClientOriginalName();
                $avatar = Str::random(4) . "_" . $name;
                while (file_exists("/uploads/users-images/" . $avatar)) {
                    $avatar = Str::random(4) . "_" . $name;
                }
                $file->move(public_path() . '/uploads/users-images/', $avatar);
                $user->image = $avatar;
            }
            $result = $user->save();
            if ($result) {
                return response()->json([
                    'error' => false,
                    'message' => 'Success'
                ]);
            } else {
                $errors = new MessageBag(['errorEdit' => 'Error occurred!']);
                return response()->json([
                    'error' => true,
                    'message' => $errors
                ]);
            }
        }
    }

}
