<?php

namespace App\Http\Controllers\Admin;

use App\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Order;
use App\Collection;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use App\Review;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(Request $request)
    {
        // admins
        $admins = admin::notDelete()->search($request)->sortId($request)->status($request);
        $count_admin = 0;
        $view = 0;
        $count_admin = $admins->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $admins = $admins->paginate($view);
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        $role = $request->role;
        // search
        $search = $request->search;
        // admin
        $user = Auth::guard('admin')->user();
        return view('admin.admin.index', [
            // admins
            'admins' => $admins,
            'count_admin' => $count_admin,
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
        $admin = admin::find($id);
        $admin->is_actived = 1;
        if ($admin->save()) {
            return back()->with('success', 'admin #' . $admin->id . ' has been activated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDeactivate($id)
    {
        $admin = admin::find($id);
        $admin->is_actived = 0;
        if ($admin->save()) {
            return back()->with('success', 'admin #' . $admin->id . ' has been deactivated.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doRemove($id)
    {
        $admin = admin::find($id);
        $admin->is_deleted = 1;
        if ($admin->save()) {
            return back()->with('success', 'admin #' . $admin->id . ' has been removed.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function recycle(Request $request)
    {
        // admins
        $admins = admin::softDelete()->search($request)->sortId($request)->status($request);
        $count_admin = 0;
        $view = 0;
        $count_admin = $admins->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $admins = $admins->paginate($view);
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        $role = $request->role;
        // search
        $search = $request->search;
        // admin
        $user = Auth::guard('admin')->user();
        return view('admin.admin.recycle', [
            // admins
            'admins' => $admins,
            'count_admin' => $count_admin,
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
        $admin = admin::find($id);
        $admin->is_deleted = 0;
        if ($admin->save()) {
            return back()->with('success', 'admin #' . $admin->id . ' has been restored.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDelete($id)
    {
        $admin = admin::find($id);
        if ($admin->forceDelete()) {
            return back()->with('success', 'admin #' . $admin->id . ' has been deleted.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function bulk_action(Request $request)
    {
        if ($request->has('bulk_action')) {
            if ($request->has('admin_id_list')) {
                $message = null;
                switch ($request->bulk_action) {
                    case 0: // deactivate
                        $message = 'admin ';
                        foreach ($request->admin_id_list as $admin_id) {
                            $admin = null;
                            $admin = admin::find($admin_id);
                            $admin->is_actived = 0;
                            if ($admin->save()) {
                                $message .= ' #' . $admin->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred when deactivate admin #' . $admin->id);
                            }
                        }
                        $message .= 'have been deactivated.';
                        return back()->with('success', $message);
                        break;
                    case 1: // activate
                        $message = 'admin ';
                        foreach ($request->admin_id_list as $admin_id) {
                            $admin = null;
                            $admin = admin::find($admin_id);
                            $admin->is_actived = 1;
                            if ($admin->save()) {
                                $message .= ' #' . $admin->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred when activate admin #' . $admin->id);
                            }
                        }
                        $message .= 'have been activated.';
                        return back()->with('success', $message);
                        break;
                    case 4: // remove
                        $message = 'admin';
                        foreach ($request->admin_id_list as $admin_id) {
                            $admin = null;
                            $admin = admin::find($admin_id);
                            $admin->is_deleted = 1;
                            if ($admin->save()) {
                                $message .= ' #' . $admin->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred!');
                            }
                        }
                        $message .= 'have been removed.';
                        break;
                    case 5: // restore
                        $message = 'admin ';
                        foreach ($request->admin_id_list as $admin_id) {
                            $admin = null;
                            $admin = admin::find($admin_id);
                            $admin->is_deleted = 0;
                            if ($admin->save()) {
                                $message .= ' #' . $admin->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred when restore admin #' . $admin->id);
                            }
                        }
                        $message .= 'have been restored.';
                        return back()->with('success', $message);
                        break;
                    case 6: // delete
                        $message = 'admin ';
                        foreach ($request->admin_id_list as $admin_id) {
                            $admin = null;
                            $admin = admin::find($admin_id);
                            if ($admin->forceDelete()) {
                                $message .= ' #' . $admin->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred when deleted admin #' . $admin->id);
                            }
                        }
                        $message .= 'have been deleted.';
                        return back()->with('success', $message);
                        break;
                }
                return back()->with('success', $message);
            } else {
                return back()->with('error', 'Please select admins to take action!');
            }
        } else {
            return back()->with('error', 'Please select an action!');
        }
    }
}
