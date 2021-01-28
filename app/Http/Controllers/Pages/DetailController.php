<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Product;
use App\Category;
use App\Collection;
use App\CollectionProduct;
use App\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Review;
use App\TagProduct;
use App\Tag;
use App\User;

class DetailController extends Controller
{
    public function index($id)
    {
        // detail of the product
        $product = Product::find($id);
        $product->view += 1;
        $product->save();
        // relative category
        $relative_category = Category::find($product->category_id)->first();

        //relative collection
        $relative_collection_product = CollectionProduct::where('is_deleted', 0)->where('product_id', '=', $product->id)->first();
        // print_r($relative_collection_product);
        // echo $real
        $relative_collection = Collection::where('is_deleted', 0)->where('id', $relative_collection_product->collection_id)->first();
        // print_r($relative_collection);

        // images of product
        $product_images = ProductImage::where('is_deleted', 0)->where('product_id', $id)->get();
        $count_images = $product_images->count();

        // relative products in same collection
        $same_collection_products = null;
        $same_collection_products = DB::table('products')->join('collection_products', 'products.id', '=', 'collection_products.product_id')
            ->select(DB::raw('products.id, products.name,products.image, products.price, products.quantity, products.remaining, products.create_date, products.rating, products.category_id, products.producer_id, products.discount, products.is_actived, products.is_deleted, collection_products.collection_id'))
            ->where('collection_products.collection_id', $relative_collection->id)
            ->get();

        // tags
        $tag_products = TagProduct::where('is_deleted', 0)->where('product_id', $product->id)->get();
        $tag_products_array = array();
        foreach ($tag_products as $tag_product) {
            $tag_products_array[] = $tag_product->tag_id;
        }
        $tag_products_array = collect($tag_products_array)->unique()->toArray();
        $tags = Tag::where('is_deleted', 0)->whereIn('id', $tag_products_array)->get();

        // products in same category
        $same_category_products = Product::where('is_deleted', 0)->where('is_actived', 1)->where('category_id', $relative_category->id)->get();

        // cart
        $shopping_carts = session()->get('cart');
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

        // review
        $reviews = Review::where('product_id', $id)->where('is_actived', 1)->where('is_deleted', 0)->paginate(10);
        $user_array = array();
        foreach ($reviews as $review) {
            $user_array[] = $review->user_id;
        }
        $review_users = User::where('is_deleted', 0)->whereIn('id', $user_array)->get();


        // save product to session view
        // add product to view
        $view = session()->get('recent_views');
        // if cart is empty then this is the first product of the cart
        if (!$view) {
            $view = [
                $product->id => [
                    'product_name' => $product->name,
                    'product_image' => $product->image,
                ]
            ];
        }
        // if view is not empty then check if this product exist before then change the chapter
        if (isset($view[$product->id])) {
            session()->put('recent_views', $view);
        }
        //if product not exist in view then add product into view
        $view[$product->id] = [
            'product_name' => $product->name,
            'product_image' => $product->image,
        ];
        session()->put('recent_views', $view);

        return view('pages.details', [
            // product
            'product' => $product,
            'tags' => $tags,
            'relative_category' => $relative_category,
            'relative_collection' => $relative_collection,
            'product_images' => $product_images,
            'count_images' => $count_images,
            'same_category_products' => $same_category_products,
            'same_collection_products' => $same_collection_products,
            // cart
            'shopping_carts' => $shopping_carts,
            'count_cart' => $count_cart,
            'total_cart' => $total_cart,
            // review
            'reviews' => $reviews,
            'review_users' => $review_users,

        ]);
    }

    public function quick_view(Request $request)
    {
        // product
        $quick_view_product = Product::find($request->product_id);
        // images of product
        $quick_view_product_images = ProductImage::where('is_deleted', 0)->where('product_id', $request->product_id)->get();
        $quick_view_count_images = $quick_view_product_images->count();
        // review
        $reviews = Review::where('product_id', $request->product_id)->where('is_actived', 1)->where('is_deleted', 0)->paginate(10);
        return view('pages.quick_view', [
            // product
            'quick_view_product' => $quick_view_product,
            'quick_view_product_images' => $quick_view_product_images,
            'quick_view_count_images' => $quick_view_count_images,
            // review
            'reviews' => $reviews,
            // 
            'quick_view' => true,
        ]);
    }
}
