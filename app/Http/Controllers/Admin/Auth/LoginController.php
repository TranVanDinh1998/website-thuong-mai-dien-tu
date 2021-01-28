<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;



class LoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin');
    }
    public function index()
    {
        return view('admin.auth.login', [
            'admin_login' => true,
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
            // if ($request->remember == 1) {
            //     $login = Auth::guard('admin')->attempt(['email' => $email, 'password' => $password, 'is_actived' => 1, 'is_deleted' => 0],$request->remember);
            // }
            // else {
            $login = Auth::guard('admin')->attempt(['email' => $email, 'password' => $password, 'is_actived' => 1, 'is_deleted' => 0]);
            // }
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
