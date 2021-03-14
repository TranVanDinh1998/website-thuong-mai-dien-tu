<?php

namespace App\Http\Controllers\Customer\Account;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReviewRequest;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Review;
use App\Models\User;
use Illuminate\Auth\Events\Validated;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class ReviewController extends Controller
{
    public function __construct(Product $product, Order $order, User $user, Review $review, OrderDetail $orderDetail)
    {
        $this->orderDetail = $orderDetail;
        $this->review = $review;
        $this->product = $product;
        $this->order = $order;
        $this->user = $user;
        $this->middleware('auth');
    }

    // review
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
        // review
        $reviews = $this->review->whereUser_id($user->id);
        $reviews = $reviews->paginate(10);

        return view('pages.customer.account.reviews', [
            // user
            'user' => $user,
            // cart
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            'discount_cart' => $discount_cart,
            // review
            'reviews' => $reviews,

        ]);
    }

    public function store(ReviewRequest $request)
    {
        //
        $orders = $this->order->active()->where('user_id', Auth::user()->id)->get();
        $orders_array = array();
        foreach ($orders as $order) {
            $orders_array[] = $order->id;
        }
        $orderDetails = $this->orderDetail->whereIn('order_id', $orders_array)->where('product_id', $request->product_id)->get();
        if (count($orderDetails) > 0) {
            $result = $this->review->create([
                'description' => $request->description,
                'summary' => $request->summary,
                'price_rate' => $request->price_rate,
                'value_rate' => $request->value_rate,
                'quality_rate' => $request->quality_rate,
                'product_id' => $request->product_id,
                'user_id' => Auth::user()->id,
            ]);
            if ($result) {
                $product = $this->product->find($request->product_id);
                $sum = 0;
                foreach ($product->reviews as $review) {
                    $sum += $review->price_rate + $review->value_rate + $review->quality_rate;
                }
                $rating = $sum / (3 * 5);
                $result2 = $product->update(['rating' => $rating]);
                if ($result2) {
                    return back()->withSuccess('Cảm ơn bạn đã đưa ra đánh giá sản phẩm.');
                } else {
                    return back()->withError('Xảy ra lỗi trong quá trình cập nhật xếp hạng của sản phẩm');
                }
            } else {
                return back()->withError('Xảy ra lỗi trong quá trình xử lý đánh giá của bạn');
            }
        }
        else {
            return back()->withError('Bạn cần phải mua sản phẩm này thì mới được phép đánh giá.');
        }
    }
}
