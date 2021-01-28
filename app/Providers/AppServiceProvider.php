<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Category;
use App\Collection;
use App\Producer;
use App\Review;
use App\Order;
use App\Contact;
use App\Address;
use App\Ward;
use App\District;
use App\Province;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Tag;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $categories = Category::notDelete()->active()->get();
        view()->share('categories', $categories);

        $collections = Collection::notDelete()->active()->get();
        view()->share('collections', $collections);

        $producers = Producer::notDelete()->active()->get();
        view()->share('producers', $producers);

        $tags = Tag::where('is_deleted', 0)->orderByDesc('view')->limit(10)->get();
        view()->share('tags', $tags);

        $quick_view = false;
        view()->share('quick_view', $quick_view);

        $admin_login = false;
        view()->share('admin_login',$admin_login);

        $user = Auth::user();
        view()->share('user', $user);


        // admin
        // task
        $task_reviews_count = Review::notDelete()->inactive()->count();
        $task_orders_count = Order::notDelete()->notDone()->count();
        $task_contacts_count = Contact::notDelete()->unRead()->count();
        //
        $task_reviews = Review::notDelete()->inactive()->orderByDesc('create_date')->limit(4)->get();
        $task_contacts = Contact::notDelete()->unRead()->orderByDesc('create_date')->limit(4)->get();
        $task_orders = Order::notDelete()->notDone()->orderByDesc('create_date')->limit(4)->get();
        // address
        $addresses = Address::get();
        $wards = Ward::get();
        $districts = District::get();
        $provinces = Province::get();
        $users = User::get();
        view()->share('task_reviews_count',$task_reviews_count);
        view()->share('task_orders_count',$task_orders_count);
        view()->share('task_contacts_count',$task_contacts_count);
        view()->share('task_reviews',$task_reviews);
        view()->share('task_contacts',$task_contacts);
        view()->share('task_orders',$task_orders);
        view()->share('addresses', $addresses);
        view()->share('wards' , $wards);
        view()->share('districts' , $districts);
        view()->share('provinces' , $provinces);
        view()->share('users' , $users);

    }
}
