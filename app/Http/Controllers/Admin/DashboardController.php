<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\Collection;
use App\CollectionProduct;
use App\ProductImage;
use App\Category;
use App\Order;
use App\OrderDetail;
use App\Producer;
use App\TagProduct;
use App\Review;
use App\User;
use App\Address;
use App\Ward;
use App\District;
use App\Province;
use App\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:admin');
    }
    public function index(Request $request)
    {
        $count_view = Product::notDelete()->sum('view');
        $count_user = User::notDelete()->count();
        $count_sale = Order::notDelete()->done()->sum('total');
        $count_order = Order::notDelete()->count();
        $categories = Category::notDelete()->active()->get();
        $collections = Collection::notDelete()->active()->get();
        $products = Product::notDelete()
            ->select(
                'id',
                'name',
                'price',
                'remaining',
                'quantity',
                DB::raw('quantity-remaining as leftover')
            )
            ->orderByDesc('leftover')
            ->limit(10)
            ->get();
        $user = Auth::guard('admin')->user();

        // chart
        // daily
        $daily = array();
        $daily_chart = null;
        $daily_chart =
            '<div id="graph8"></div>
        <script>
            var day_data = [';
        for ($i = 0; $i < 7; $i++) {
            $day = Carbon::now()->startOfWeek()->addDay($i)->toDateString();
            $sum_sale = Order::notDelete()->active()->where('create_date', $day)->done()->sum('total');
            $sum_pending = Order::notDelete()->active()->where('create_date', $day)->notDone()->sum('total');
            $daily[] = array('day' => $day, 'sale' => $sum_sale, 'pending' => $sum_pending);
        }
        // print_r($daily);
        foreach ($daily as $day) {
            $daily_chart .= '{
                "period": "' . $day['day'] . '",
                "sale": ' . $day['sale'] . ',
                "pending": ' . $day['pending'] . '
            },';
        }
        $daily_chart .= "        
            ];
            Morris.Bar({
                element: 'graph8',
                data: day_data,
                xkey: 'period',
                ykeys: ['sale', 'pending'],
                labels: ['Sale', 'Pending'],
                xLabelAngle: 60
            });

        </script> ";
        // month
        $monthly = array();
        $monthly_chart = null;
        $monthly_chart .= '
        <div id="graph7"></div>
        <script>
            // This crosses a DST boundary in the UK.
            Morris.Area(
                {
                element: "graph7",
                data: [ ';
        for ($i = 0; $i < 12; $i++) {
            $begin_day = Carbon::now()->startOfMonth()->addMonth($i)->toDateString();
            $end_day = Carbon::now()->endOfMonth()->addMonth($i)->toDateString();
            // echo $begin_day.'-'.$end_day.'<br>';
            $sum_sale = Order::notDelete()->active()->whereBetween('create_date', [$begin_day, $end_day])->done()->sum('total');
            $sum_cancel = Order::notDelete()->active()->whereBetween('create_date', [$begin_day, $end_day])->inActive()->sum('total');
            $monthly[] = array('day' => $end_day, 'sale' => $sum_sale, 'cancel' => $sum_cancel);
        }
        foreach ($monthly as $month) {
            $monthly_chart .= "
            {
                day: '" . $month['day'] . "',
                sale: " . $month['sale'] . ",
                cancel: " . $month['cancel'] . "
            },
            ";
        }
        $monthly_chart  .= "      
            ],
            xkey: 'day',
            ykeys: ['sale', 'cancel'],
            labels: ['Sale', 'Cancel']
            });

        </script>";
        // year
        $yearly = array();
        $yearly_chart = null;
        $yearly_chart .= '
        <div id="graph9"></div>
        <script>
            var day_data = [
        ';
        for ($i = 5; $i >= 0; $i--) {
            $begin_day = Carbon::now()->startOfYear()->subYear($i)->toDateString();
            $end_day = Carbon::now()->endOfYear()->subYear($i)->toDateString();
            $year = Carbon::now()->subYear($i)->year;
            $sum_sale = Order::notDelete()->active()->whereBetween('create_date', [$begin_day, $end_day])->done()->sum('total');
            $yearly[] = array('year' => $year, 'sale' => $sum_sale);
        }
        foreach ($yearly as $year) {
            $yearly_chart .= '
            {
                "elapsed": "'.$year['year'].'",
                "value": '.$year['sale'].'
            },
            ';
        }
        $yearly_chart .=  "];
            Morris.Line({
                element: 'graph9',
                data: day_data,
                xkey: 'elapsed',
                ykeys: ['value'],
                labels: ['value'],
                parseTime: false
            });
        </script>";

        return view('admin.dashboard', [
            // count
            'count_view' => $count_view,
            'count_user' => $count_user,
            'count_sale' => $count_sale,
            'count_order' => $count_order,
            // user
            'current_user' => $user,
            // products
            'products' => $products,
            // categories
            'categories' => $categories,
            'collections' => $collections,

            // chart
            'daily_chart' => $daily_chart,
            'monthly_chart' => $monthly_chart,
            'yearly_chart' => $yearly_chart,

        ]);
    }
}
