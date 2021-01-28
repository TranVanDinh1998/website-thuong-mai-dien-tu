<?php

    namespace App\Http\Controllers\Admin\Auth;
    use App\Http\Controllers\Controller;
    use Illuminate\Support\Facades\Auth;
    // use Auth;

    class LogoutController extends Controller {
        public function index() {
            Auth::guard('admin')->logout();
            // session()->flush();
            return back();
        }
    } 
?>