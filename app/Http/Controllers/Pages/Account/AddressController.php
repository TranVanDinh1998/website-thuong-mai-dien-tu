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

class AddressController extends Controller
{
    public function __construct()
    {
        $this->middleware('userLogin');
    }

    // address
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
        // recent order
        $user = Auth::user();
        $recent_orders = Order::where('is_deleted', 0)->where('user_id', $user->id)->orderByDesc('create_date')->limit(4)->get();

        // user address
        $addresses = null;
        if ((Auth::user())) {
            $addresses = Address::where('user_id', Auth::user()->id)->get();
        }
        $wards = Ward::get();
        $districts = District::get();
        $provinces = Province::get();
        return view('pages.account.address.index', [
            // user
            'user' => $user,
            // cart
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,
            // order
            'recent_orders' => $recent_orders,
            'addresses' => $addresses,
            'provinces' => $provinces,
            'districts' => $districts,
            'wards' => $wards,
        ]);
    }

    public function add()
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
        // recent order
        $user = Auth::user();

        $wards = Ward::get();
        $districts = District::get();
        $provinces = Province::get();
        return view('pages.account.address.add', [
            // user
            'user' => $user,
            // cart
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,
            // address
            'provinces' => $provinces,
            'districts' => $districts,
            'wards' => $wards,
        ]);
    }

    public function do_add(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'number' => 'required',
            'address' => 'required',
            'province_id' => 'required',
            'district_id' => 'required',
            'ward_id' => 'required',
        ], [
            'required' => ':attribute must be filled',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors(),
            ]);
        } else {
            $address = new Address();
            $address->name = $request->name;
            $address->user_id = Auth::user()->id;
            $address->address = $request->address;
            $address->number = $request->number;
            $address->ward_id = $request->ward_id;
            $address->district_id = $request->district_id;
            $address->province_id = $request->province_id;
            $result = $address->save();
            if ($result) {
                if ($request->shipping == 1) {
                    $user = User::find(Auth::user()->id);
                    $user->shipping_address_id = $address->id;
                    $user->save();
                    return response()->json([
                        'error' => false,
                        'message' => 'The address has been added and be set as primary shipping address',
                    ]);
                }
                return response()->json([
                    'error' => false,
                    'message' => 'Success'
                ]);
            } else {
                $errors = new MessageBag(['errorAdd' => 'Error occurred!']);
                return response()->json([
                    'error' => true,
                    'message' => $errors
                ]);
            }
        }
    }

    public function edit($id)
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
        // recent order
        $user = Auth::user();

        $wards = Ward::get();
        $districts = District::get();
        $provinces = Province::get();

        // current address
        $address = Address::find($id);

        // current address
        return view('pages.account.address.edit', [
            // user
            'user' => $user,
            // cart
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,
            // address
            'provinces' => $provinces,
            'districts' => $districts,
            'wards' => $wards,
            // current address
            'address' => $address,
        ]);
    }
    public function do_edit(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required',
            'number' => 'required',
            'address' => 'required',
            'province_id' => 'required',
            'district_id' => 'required',
            'ward_id' => 'required',
        ], [
            'required' => ':attribute must be filled',
        ]);
        if ($validate->fails()) {
            return response()->json([
                'error' => true,
                'message' => $validate->errors(),
            ]);
        } else {
            $address = Address::find($request->id);
            $address->name = $request->name;
            $address->address = $request->address;
            $address->number = $request->number;
            $address->ward_id = $request->ward_id;
            $address->district_id = $request->district_id;
            $address->province_id = $request->province_id;
            $result = $address->save();
            if ($result) {
                if ($request->shipping == 1) {
                    $user = User::find(Auth::user()->id);
                    $user->shipping_address_id = $address->id;
                    $user->save();
                    return response()->json([
                        'error' => false,
                        'message' => 'The address has been edited and be set as primary shipping address',
                    ]);
                }
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


    public function set_primary_shipping_address($id)
    {
        $user_id = Auth::user()->id;
        $user = User::find($user_id);
        $user->shipping_address_id = $id;
        $result = $user->save();
        if ($result) {
            return back()->with('success', 'Address id ' . $id . ' has been set as primary shipping address.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function remove($id)
    {
        $address = Address::find($id);
        if ($address->forceDelete()) {
            return back()->with('success', 'Address #' . $address->id . ' has been deleted.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }
}
