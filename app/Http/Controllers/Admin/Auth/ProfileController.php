<?php

namespace App\Http\Controllers\Admin\Auth;

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
use App\Admin;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    public function index()
    {
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.auth.profile', [
            // user
            'user' => $user,
        ]);
    }

    public function doEdit(Request $request)
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
            $user_id = Auth::guard('admin')->user()->id;
            $user = Admin::find($user_id);
            $user->name = $request->name;
            $user->update_date = date('Y-m-d');
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
