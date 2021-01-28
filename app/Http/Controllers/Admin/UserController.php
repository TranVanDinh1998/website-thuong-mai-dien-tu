<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\User;
use App\Order;
use App\Collection;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Review;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(Request $request)
    {
        // users
        $users = User::notDelete()->search($request)->sortId($request)->role($request)->status($request);
        $count_user = 0;
        $view = 0;
        $count_user = $users->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $users = $users->paginate($view);
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        $role = $request->role;
        // search
        $search = $request->search;
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.user.index', [
            // users
            'users' => $users,
            'count_user' => $count_user,
            'view' => $view,
            // search
            'search' => $search,
            // filter
            'sort_id' => $sort_id,
            'status' => $status,
            'role' => $role,
            //
            'current_user' => $user,
        ]);
    }

    public function doActivate($id)
    {
        $user = user::find($id);
        $user->is_actived = 1;
        if ($user->save()) {
            return back()->with('success', 'User #' . $user->id . ' has been activated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDeactivate($id)
    {
        $user = user::find($id);
        $user->is_actived = 0;
        if ($user->save()) {
            return back()->with('success', 'User #' . $user->id . ' has been deactivated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doPromote($id)
    {
        $user = User::find($id);
        $find_admin = Admin::where('email',$user->email)->count();
        if ($find_admin == 0 ) {
            $admin = new Admin();
            $admin->name = $user->name;
            $admin->email = $user->email;
            $admin->password = $user->password;
            $admin->image = $user->image;
            $admin->create_date = date('Y-m-d');
            $admin->is_actived = 1;
            $admin->is_deleted = 0;
            if ($admin->save()) {
                return back()->with('success', 'User #' . $user->id . ' has been promoted as administrator.');
            } else {
                return back()->with('error', 'Error occurred!');
            }
        }
        else {
            return back()->with('error','The email ' .$user->email .' has already been used as administrator account. Unable to promote user #'.$user->id);
        }

    }

    public function doRemove($id)
    {
        $user = User::find($id);
        $count_relative_orders = Order::where('user_id', $user->id)->count();
        if ($count_relative_orders == 0) {
            $count_relative_reviews = Review::where('user_id', $user->id)->count();
            if ($count_relative_reviews == 0) {
                $user->is_deleted = 1;
                $user->save();
                if ($user->save()) {
                    return back()->with('success', 'User #' . $user->id . ' has been removed.');
                } else {
                    return back()->with('error', 'Error occurred!');
                }
            } else {
                return back()->with('error', 'This user relates to  ' . $count_relative_reviews . ' reviews');
            }
        } else {
            return back()->with('error', 'This user relates to  ' . $count_relative_orders . ' orders');
        }
    }

    public function recycle(Request $request)
    {
        // users
        $users = user::softDelete()->search($request)->sortId($request)->status($request)->role($request);
        $count_user = 0;
        $view = 0;
        $count_user = $users->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $users = $users->paginate($view);
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        $role = $request->role;
        // search
        $search = $request->search;
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.user.recycle', [
            // users
            'users' => $users,
            'count_user' => $count_user,
            'view' => $view,
            // search
            'search' => $search,
            // filter
            'sort_id' => $sort_id,
            'status' => $status,
            'role' => $role,
            //
            'current_user' => $user,
        ]);
    }

    public function doRestore($id)
    {
        $user = user::find($id);
        $user->is_deleted = 0;
        if ($user->save()) {
            return back()->with('success', 'User #' . $user->id . ' has been restored.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDelete($id)
    {
        $user = User::find($id);
        if ($user->forceDelete()) {
            return back()->with('success', 'User #' . $user->id . ' has been deleted.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function bulk_action(Request $request)
    {
        if ($request->has('bulk_action')) {
            if ($request->has('user_id_list')) {
                $message = null;
                switch ($request->bulk_action) {
                    case 0: // deactivate
                        $message = 'user ';
                        foreach ($request->user_id_list as $user_id) {
                            $user = null;
                            $user = user::find($user_id);
                            $user->is_actived = 0;
                            if ($user->save()) {
                                $message .= ' #' . $user->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while deactivate user #' . $user->id);
                            }
                        }
                        $message .= 'have been deactivated.';
                        return back()->with('success', $message);
                        break;
                    case 1: // activate
                        $message = 'user ';
                        foreach ($request->user_id_list as $user_id) {
                            $user = null;
                            $user = user::find($user_id);
                            $user->is_actived = 1;
                            if ($user->save()) {
                                $message .= ' #' . $user->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while activate user #' . $user->id);
                            }
                        }
                        $message .= 'have been activated.';
                        return back()->with('success', $message);
                        break;
                    case 2: // promote
                        $message = 'user ';
                        foreach ($request->user_id_list as $user_id) {
                            $user = null;
                            $user = user::find($user_id);
                            $user->is_admin = 1;
                            if ($user->save()) {
                                $message .= ' #' . $user->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while promote user #' . $user->id);
                            }
                        }
                        $message .= 'have been promoted.';
                        return back()->with('success', $message);
                        break;
                    case 4: // remove
                        $message = 'user';
                        foreach ($request->user_id_list as $user_id) {
                            $user = null;
                            $user = user::find($user_id);
                            $count_relative_orders = Order::where('user_id', $user->id)->count();
                            if ($count_relative_orders == 0) {
                                $count_relative_reviews = Review::where('user_id', $user->id)->count();
                                if ($count_relative_reviews == 0) {
                                    $user->is_deleted = 1;
                                    $user->save();
                                    if ($user->save()) {
                                        $message .= ' #' . $user->id . ', ';
                                    } else {
                                        return back()->with('error', 'Error occurred!');
                                    }
                                } else {
                                    return back()->with('error', 'This user #.' . $user->id . ' relates to  ' . $count_relative_reviews . ' reviews');
                                }
                            } else {
                                return back()->with('error', 'This user #.' . $user->id . ' relates to  ' . $count_relative_orders . ' orders');
                            }
                        }
                        $message .= 'have been removed.';
                        break;
                    case 5: // restore
                        $message = 'user ';
                        foreach ($request->user_id_list as $user_id) {
                            $user = null;
                            $user = user::find($user_id);
                            $user->is_deleted = 0;
                            if ($user->save()) {
                                $message .= ' #' . $user->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while restore user #' . $user->id);
                            }
                        }
                        $message .= 'have been restored.';
                        return back()->with('success', $message);
                        break;
                    case 6: // delete
                        $message = 'user ';
                        foreach ($request->user_id_list as $user_id) {
                            $user = null;
                            $user = user::find($user_id);
                            if ($user->forceDelete()) {
                                $message .= ' #' . $user->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while deleted user #' . $user->id);
                            }
                        }
                        $message .= 'have been deleted.';
                        return back()->with('success', $message);
                        break;
                }
                return back()->with('success', $message);
            } else {
                return back()->with('error', 'Please select users to take action!');
            }
        } else {
            return back()->with('error', 'Please select an action!');
        }
    }
}
