<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Collection;
use App\Review;
use App\Product;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\File;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Str;



class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(Request $request)
    {
        // reviews
        $reviews = Review::notDelete()->sortId($request)->status($request);
        $count_review = 0;
        $view = 0;
        $count_review = $reviews->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $reviews = $reviews->paginate($view);
        $review_product_array = array();
        $review_user_array = array();
        foreach ($reviews as $review) {
            $review_product_array[] = $review->product_id;
            $review_user_array[] = $review->user_id;
        }
        $products = Product::notDelete()->whereIn('id', $review_product_array)->get();
        $users = User::where('is_deleted', 0)->whereIn('id', $review_user_array)->get();

        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        // user
        $user = Auth::guard('admin')->user();
        // search
        $search = $request->search;
        return view('admin.review.index', [
            // reviews
            'reviews' => $reviews,
            'products' => $products,
            'count_review' =>$count_review,
            'users' => $users,
            'view' => $view,
            // filter
            'sort_id' => $sort_id,
            'status' => $status,
            // search
            'search' => $search,
            // user
            'current_user' => $user,
        ]);
    }

    public function doActivate($id)
    {
        $review = Review::find($id);
        $review->is_actived = 1;
        if ($review->save()) {
            return back()->with('success', 'Review #' . $review->id . ' has been approved.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDeactivate($id)
    {
        $review = Review::find($id);
        $review->is_actived = 0;
        if ($review->save()) {
            return back()->with('success', 'Review #' . $review->id . ' has been disapproved.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }
    public function detail($id)
    {
        // user
        $user = Auth::guard('admin')->user();
        $review =  review::find($id);
        $review_user = User::find($review->user_id);
        return view('admin.review.detail', [
            'review' => $review,
            //
            'current_user' => $user,
            'review_user'=>$review_user,
        ]);
    }
    public function doRemove($id)
    {
        $review = Review::find($id);
        $review->is_deleted = 1;
        if ($review->save()) {
            return back()->with('success', 'Review #' . $review->id . ' has been removed.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }
    public function recycle(Request $request)
    {
        // reviews
        $reviews = review::softDelete()->sortId($request)->status($request);
        $count_review = 0;
        $view = 0;
        $count_review = $reviews->count();
        if ($request->has('view')) {
            $view = $request->view;
        } else {
            $view = 10;
        }
        $reviews = $reviews->paginate($view);
        $review_product_array = array();
        $review_user_array = array();
        foreach ($reviews as $review) {
            $review_product_array[] = $review->product_id;
            $review_user_array[] = $review->user_id;
        }
        $products = Product::where('is_deleted', 0)->whereIn('id', $review_product_array)->get();
        $users = User::where('is_deleted', 0)->whereIn('id', $review_user_array)->get();
        // filter
        $sort_id = $request->sort_id;
        $status = $request->status;
        // search
        $search = $request->search;
        // user
        $user = Auth::guard('admin')->user();
        return view('admin.review.recycle', [
            // reviews
            'reviews' => $reviews,
            'products' => $products,
            'count_review' =>$count_review,
            'users' => $users,
            'view' => $view,
            // filter
            'sort_id' => $sort_id,
            'status' => $status,
            // search
            'search' => $search,
            // user
            'current_user' => $user,
        ]);
    }
    public function doRestore($id)
    {
        $review = review::find($id);
        $review->is_deleted = 0;
        if ($review->save()) {
            return back()->with('success', 'Review #' . $review->id . ' has been restored.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function doDelete($id)
    {
        $review = review::find($id);
        if ($review->forceDelete()) {
            return back()->with('success', 'Review #' . $review->id . ' has been deleted.');
        } else {
            return back()->with('error', 'Error occurred!');
        }
    }

    public function bulk_action(Request $request)
    {
        if ($request->has('bulk_action')) {
            if ($request->has('review_id_list')) {
                $message = null;
                switch ($request->bulk_action) {
                    case 0: // deactivate
                        $message = 'Review ';
                        foreach ($request->review_id_list as $review_id) {
                            $review = null;
                            $review = review::find($review_id);
                            $review->is_actived = 0;
                            if ($review->save()) {
                                $message .= ' #' . $review->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while deactivating review #' . $review->id);
                            }
                        }
                        $message .= 'have been deactivated.';
                        return back()->with('success', $message);
                        break;
                    case 1: // activate
                        $message = 'Review ';
                        foreach ($request->review_id_list as $review_id) {
                            $review = null;
                            $review = review::find($review_id);
                            $review->is_actived = 1;
                            if ($review->save()) {
                                $message .= ' #' . $review->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while activating review #' . $review->id);
                            }
                        }
                        $message .= 'have been activated.';
                        return back()->with('success', $message);
                        break;
                    case 2: // remove
                        $message = 'review';
                        foreach ($request->review_id_list as $review_id) {
                            $review = null;
                            $review = review::find($review_id);
                            $review->is_deleted = 1;
                            if ($review->save()) {
                                $message .= ' #' . $review->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while removing review #' . $review->id);
                            }
                        }
                        $message .= 'have been removed.';
                        break;
                    case 3: // restore
                        $message = 'Review ';
                        foreach ($request->review_id_list as $review_id) {
                            $review = null;
                            $review = review::find($review_id);
                            $review->is_deleted = 0;
                            if ($review->save()) {
                                $message .= ' #' . $review->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while restoring review #' . $review->id);
                            }
                        }
                        $message .= 'have been restored.';
                        return back()->with('success', $message);
                        break;
                    case 4: // delete
                        $message = 'Review ';
                        foreach ($request->review_id_list as $review_id) {
                            $review = null;
                            $review = review::find($review_id);
                            if ($review->forceDelete()) {
                                $message .= ' #' . $review->id . ', ';
                            } else {
                                return back()->with('error', 'Error occurred while deleting review #' . $review->id);
                            }
                        }
                        $message .= 'have been deleted.';
                        return back()->with('success', $message);
                        break;
                }
                return back()->with('success', $message);
            } else {
                return back()->with('error', 'Please select reviews to take action!');
            }
        } else {
            return back()->with('error', 'Please select an action!');
        }
    }
}
