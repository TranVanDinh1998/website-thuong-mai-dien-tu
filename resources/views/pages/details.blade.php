@extends('layout')
@section('title', 'Details - Electronical Store')
@section('content')
    <!-- breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <ul>
                    <li class="home"> <a href="{{ URL::to('/') }}" title="Go to Home Page">Home</a><span>&mdash;›</span>
                    </li>
                    <li class="category13"> <a href="{{ URL::to('filter/' . $relative_category->id) }}"
                            title="Go to Home Page">{{ $relative_category->name }}</a><span>&mdash;›</span></li>
                    <li class=""><strong> {{ $product->name }} </strong></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- end breadcrumbs -->
    <!-- main-container -->

    <section class="main-container col1-layout">
        <div class="main container">
            <div class="col-main">
                <div class="row">
                    <div class="product-view wow">
                        <div class="product-essential">
                            <div class="product-img-box col-lg-6 col-sm-6 col-xs-12">
                                <ul class="moreview" id="moreview">
                                    <li class="moreview_thumb thumb_1 moreview_thumb_active">
                                        <img class="moreview_thumb_image"
                                            src="{{ url('uploads/products-images/' . $product->id . '/' . $product->image) }}">
                                        <img class="moreview_source_image"
                                            src="{{ url('uploads/products-images/' . $product->id . '/' . $product->image) }}"
                                            alt="">
                                        <span class="roll-over">Roll over image to zoom in</span>
                                        <img style="position: absolute;" class="zoomImg"
                                            src="{{ url('uploads/products-images/' . $product->id . '/' . $product->image) }}">
                                    </li>
                                    @for ($i = 0; $i < $count_images; $i++)
                                        <li class="moreview_thumb thumb_{{ $i + 2 }}">
                                            <img class="moreview_thumb_image"
                                                src="{{ url('uploads/products-images/' . $product->id . '/' . $product_images[$i]->image) }}">
                                            <img class="moreview_source_image"
                                                src="{{ url('uploads/products-images/' . $product->id . '/' . $product_images[$i]->image) }}"
                                                alt="">
                                            <span class="roll-over">Roll over image to zoom in</span>
                                            <img style="position: absolute;" class="zoomImg"
                                                src="{{ url('uploads/products-images/' . $product->id . '/' . $product_images[$i]->image) }}">
                                        </li>
                                    @endfor

                                </ul>
                                <div class="moreview-control"> <a style="right: 42px;" href="javascript:void(0)"
                                        class="moreview-prev"></a> <a style="right: 42px;" href="javascript:void(0)"
                                        class="moreview-next"></a> </div>
                            </div>

                            <!-- end: more-images -->

                            <div class="product-shop col-lg-6 col-sm-6 col-xs-12">
                                {{-- <div class="product-next-prev"> <a class="product-next"
                                        href="#"><span></span></a> <a class="product-prev" href="#"><span></span></a> </div>
                                --}}
                                <div class="product-name">
                                    <h1>{{ $product->name }}</h1>
                                </div>
                                <div class="ratings">
                                    <div class="rating-box">
                                        <div style="width: {{ $product->rating * 20 }}%" class="rating"></div>
                                    </div>
                                    <p class="rating-links"> <a href="#">{{ count($reviews) }} Review(s)</a> <span
                                            class="separator">|</span> <a href="#product-detail-tab">Add Your Review</a>
                                    </p>
                                </div>
                                <p class="availability in-stock"><span>
                                        @if ($product->remaining != 0)
                                            In Stock
                                        @else
                                            Out of Stock
                                        @endif
                                    </span></p>
                                <div class="price-block">
                                    <div class="price-box">
                                        @if ($product->discount != null)
                                            <p class="old-price"> <span class="price-label">Regular Price:</span> <span
                                                    class="price"> {{ $product->price }} vnd </span> </p>
                                            <p class="special-price"> <span class="price-label">Special Price</span> <span
                                                    class="price">
                                                    {{ $product->price - ($product->price * $product->discount) / 100 }} vnd
                                                </span> </p>
                                        @else
                                            <p class="regular-price"> <span class="price-label">Price :</span> <span
                                                    class="price"> {{ $product->price }} vnd </span> </p>
                                        @endif
                                    </div>
                                </div>
                                <div class="price-block">
                                    <div class="price-box">
                                        <p>Category :
                                            @foreach ($categories as $category)
                                                @if ($category->id == $product->category_id)
                                                    {{-- {{ $category->name }}
                                                    --}}
                                                    <a href="{{ URL::to('filter/' . $category->id) }}">
                                                        {{ $category->name }} </a>
                                                @endif
                                            @endforeach
                                        </p>
                                        <p>Producer :
                                            @foreach ($producers as $producer)
                                                @if ($producer->id == $product->producer_id)
                                                    {{ $producer->name }}
                                                @endif
                                            @endforeach
                                        </p>
                                        <p>Sold : {{ $product->quantity - $product->remaining }}</p>
                                        <p>Remaining : {{ $product->remaining }}</p>
                                    </div>
                                </div>
                                <div class="short-description">
                                    <h2>Quick Overview</h2>
                                    {{-- <p>{!! $product->description !!} </p>
                                    --}}
                                </div>
                                <div class="add-to-box">
                                    {{-- <form method="get" id="add_to_cart_form">
                                        <div class="add-to-cart">
                                            <input type="hidden" value="{{ $product->id }}" id="product_id" name="id">
                                            <label for="qty">Quantity:</label>
                                            <div class="pull-left">
                                                <div class="custom pull-left">
                                                    <button id="btn_increase" class="items-count" type="button"><i
                                                            class="icon-plus">&nbsp;</i></button>
                                                    <input type="number" class="input-text qty" title="Qty" value="1"
                                                        min="1" id="qty" name="quantity">
                                                    <button id="btn_decrease" class="items-count" type="button"><i
                                                            class="icon-minus">&nbsp;</i></button>
                                                </div>
                                            </div>
                                            <button type="submit" class="button btn-cart" title="Add to Cart"
                                                type="button"><span><i class="icon-basket"></i> Add to

                                                    Cart</span></button>
                                        </div>
                                    </form> --}}
                                    <div>
                                        <button type="button" onclick="add_to_cart({{ $product->id }});" title="Add to Cart"
                                            class="button btn-cart"><span>Add to Cart</span>
                                        </button>
                                    </div>

                                    <div class="email-addto-box">
                                        <ul class="add-to-links">
                                            <li> <a class="link-wishlist"
                                                    onclick="add_to_wish_list({{ $product->id }});"><span>Add to
                                                        Wishlist</span></a></li>
                                            <li><span class="separator">|</span> <a class="link-compare" onclick="return add_to_compare({{$product->id}});"><span>Add
                                                        to Compare</span></a></li>
                                        </ul>
                                        {{-- <p class="email-friend"><a href="#" class=""><span>Email to Friend</span></a> --}}

                                        </p>
                                    </div>
                                </div>
                                {{-- <div class="custom-box">
                                    <div class="inner-text">
                                        <h3>{{ $relative_collection->name }} Collection</h3>
                                    </div>
                                    <img alt="banner"
                                        src="{{ url('uploads/categories-images/' . $relative_collection->category_id . '/' . $relative_collection->image) }}">
                                </div> --}}
                            </div>
                        </div>
                        <div class="product-collateral">
                            <div class="col-sm-12 wow">
                                <ul id="product-detail-tab" class="nav nav-tabs product-tabs">
                                    <li class="active"> <a href="#product_tabs_description" data-toggle="tab"> Product
                                            Description </a> </li>
                                    <li><a href="#product_tabs_tags" data-toggle="tab">Tags</a></li>
                                    <li> <a href="#reviews_tabs" data-toggle="tab">Reviews</a> </li>
                                </ul>
                                <div id="productTabContent" class="tab-content">
                                    <div class="tab-pane fade in active" id="product_tabs_description">
                                        <div class="std">
                                            <p>{!! $product->description !!}</p>

                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="product_tabs_tags">
                                        <div class="box-collateral box-tags">
                                            <div class="tags">
                                                <p class="note">You can use these tags to search for similar products.
                                                </p>
                                                {{-- <form id="addTagForm" action="#"
                                                    method="get">
                                                    <div class="form-add-tags">
                                                        <label for="productTagName">Add Tags:</label>
                                                        <div class="input-box">
                                                            <input class="input-text required-entry" name="productTagName"
                                                                id="productTagName" type="text" style="width:35%;">
                                                            <button type="button" title="Add Tags" class=" button btn-add"
                                                                onClick="submitTagForm()"> <span>Add Tags</span> </button>
                                                        </div>
                                                        <!--input-box-->
                                                    </div>
                                                </form> --}}
                                                @foreach ($tags as $tag)
                                                    <a href="{{ route('tag', ['tag_id' => $tag->id]) }}"
                                                        class="button">{{ $tag->name }}</a>
                                                @endforeach
                                            </div>
                                            <!--tags-->

                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="reviews_tabs">
                                        <div class="box-collateral box-reviews" id="customer-reviews">
                                            <div class="box-reviews1">
                                                <div class="form-add">
                                                    <form id="review_form" action="{{ URL::to('/account/review') }}"
                                                        method="post">
                                                        @csrf
                                                        <h3>Write Your Own Review</h3>
                                                        <div id="errorMessage"></div>
                                                        <fieldset>
                                                            <h4>How do you rate this product? <em class="required">*</em>
                                                            </h4>
                                                            <span id="input-message-box"></span>
                                                            <table id="product-review-table" class="data-table">
                                                                <colgroup>
                                                                    <col>
                                                                    <col width="1">
                                                                    <col width="1">
                                                                    <col width="1">
                                                                    <col width="1">
                                                                    <col width="1">
                                                                </colgroup>
                                                                <thead>
                                                                    <tr class="first last">
                                                                        <th>&nbsp;</th>
                                                                        <th><span class="nobr">1 *</span></th>
                                                                        <th><span class="nobr">2 *</span></th>
                                                                        <th><span class="nobr">3 *</span></th>
                                                                        <th><span class="nobr">4 *</span></th>
                                                                        <th><span class="nobr">5 *</span></th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <tr class="first odd">
                                                                        <th>Price</th>
                                                                        <td class="value"><input type="radio" class="radio"
                                                                                value="1" id="Price_1" name="price_rate">
                                                                        </td>
                                                                        <td class="value"><input type="radio" class="radio"
                                                                                value="2" id="Price_2" name="price_rate">
                                                                        </td>
                                                                        <td class="value"><input type="radio" class="radio"
                                                                                value="3" id="Price_3" name="price_rate">
                                                                        </td>
                                                                        <td class="value"><input type="radio" class="radio"
                                                                                value="4" id="Price_4" name="price_rate">
                                                                        </td>
                                                                        <td class="value last"><input type="radio"
                                                                                class="radio" value="5" id="Price_5"
                                                                                name="price_rate"></td>
                                                                    </tr>
                                                                    <tr class="even">
                                                                        <th>Value</th>
                                                                        <td class="value"><input type="radio" class="radio"
                                                                                value="1" id="Value_1" name="value_rate">
                                                                        </td>
                                                                        <td class="value"><input type="radio" class="radio"
                                                                                value="2" id="Value_2" name="value_rate">
                                                                        </td>
                                                                        <td class="value"><input type="radio" class="radio"
                                                                                value="3" id="Value_3" name="value_rate">
                                                                        </td>
                                                                        <td class="value"><input type="radio" class="radio"
                                                                                value="4" id="Value_4" name="value_rate">
                                                                        </td>
                                                                        <td class="value last"><input type="radio"
                                                                                class="radio" value="5" id="Value_5"
                                                                                name="value_rate"></td>
                                                                    </tr>
                                                                    <tr class="last odd">
                                                                        <th>Quality</th>
                                                                        <td class="value"><input type="radio" class="radio"
                                                                                value="1" id="Quality_1"
                                                                                name="quality_rate">
                                                                        </td>
                                                                        <td class="value"><input type="radio" class="radio"
                                                                                value="2" id="Quality_2"
                                                                                name="quality_rate">
                                                                        </td>
                                                                        <td class="value"><input type="radio" class="radio"
                                                                                value="3" id="Quality_3"
                                                                                name="quality_rate">
                                                                        </td>
                                                                        <td class="value"><input type="radio" class="radio"
                                                                                value="4" id="Quality_4"
                                                                                name="quality_rate">
                                                                        </td>
                                                                        <td class="value last"><input type="radio"
                                                                                class="radio" value="5" id="Quality_5"
                                                                                name="quality_rate"></td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                            <input type="hidden" value="{{ $product->id }}"
                                                                class="validate-rating" name="product_id">
                                                            <div class="review1">
                                                                <ul class="form-list">
                                                                    <li>
                                                                        <label class="required"
                                                                            for="summary_field">Summary<em>*</em></label>
                                                                        <div class="input-box">
                                                                            <input type="text"
                                                                                class="input-text required-entry"
                                                                                id="summary_field" name="summary">
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                            <div class="review2">
                                                                <ul>
                                                                    <li>
                                                                        <label class="required label-wide"
                                                                            for="review_field">Review<em>*</em></label>
                                                                        <div class="input-box">
                                                                            <textarea class="required-entry" rows="3"
                                                                                cols="5" name="description"></textarea>
                                                                        </div>
                                                                    </li>
                                                                </ul>
                                                                <div class="buttons-set">
                                                                    <button class="button submit" title="Submit Review"
                                                                        type="submit"><span>Submit Review</span></button>
                                                                </div>
                                                            </div>
                                                        </fieldset>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="box-reviews2">
                                                <h3>Customer Reviews</h3>
                                                <div class="box visible">
                                                    <ul>
                                                        @foreach ($reviews as $review)
                                                            <li>
                                                                <table class="ratings-table">
                                                                    <colgroup>
                                                                        <col width="1">
                                                                        <col>
                                                                    </colgroup>
                                                                    <tbody>
                                                                        <tr>
                                                                            <th>Value</th>
                                                                            <td>
                                                                                <div class="rating-box">
                                                                                    <div class="rating"
                                                                                        style="width:{{ $review->value_rate * 20 }}%;">
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Quality</th>
                                                                            <td>
                                                                                <div class="rating-box">
                                                                                    <div class="rating"
                                                                                        style="width:{{ $review->quality_rate * 20 }}%;">
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Price</th>
                                                                            <td>
                                                                                <div class="rating-box">
                                                                                    <div class="rating"
                                                                                        style="width:{{ $review->price_rate * 20 }}%;">
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                        </tr>
                                                                    </tbody>
                                                                </table>
                                                                <div class="review">
                                                                    <h6><a href="">{{ $review->summary }}</a>
                                                                    </h6>
                                                                    <small>Review by
                                                                        <span>
                                                                            @foreach ($review_users as $user)
                                                                                @if ($user->id == $review->user_id)
                                                                                    {{ $user->name }}
                                                                                @endif
                                                                            @endforeach
                                                                        </span>on {{ $review->create_date }}
                                                                    </small>
                                                                    <div class="review-txt"> {{ $review->description }}
                                                                    </div>
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                                <div class="col-sm-7 text-right text-center-xs">
                                                    <ul class="pagination pagination-sm m-t-none m-b-none">
                                                        {!! $reviews->links() !!}
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12">
                                <div class="box-additional">
                                    <div class="related-pro wow">
                                        <div class="slider-items-products">
                                            <div class="new_title center">
                                                <h2>Same collection</h2>
                                            </div>
                                            <div id="related-products-slider" class="product-flexslider hidden-buttons">
                                                <div class="slider-items slider-width-col4">
                                                    @if ($same_collection_products != null)
                                                        @foreach ($same_collection_products as $product)
                                                            <!-- Item -->
                                                            <div class="item">
                                                                <div class="col-item">
                                                                    <div class="sale-label sale-top-right">Sale</div>
                                                                    <div class="images-container"> <a class="product-image"
                                                                            title="Sample Product" href="">
                                                                            <img src="{{ url('uploads/products-images/' . $product->id . '/' . $product->image) }}"
                                                                                class="img-responsive" alt="a" /> </a></a>
                                                                        <div class="actions">
                                                                            <div class="actions-inner">
                                                                                <button type="button"
                                                                                    onclick="add_to_cart({{ $product->id }});"
                                                                                    title="Add to Cart"
                                                                                    class="button btn-cart"><span>Add to
                                                                                        Cart</span>
                                                                                </button>
                                                                                <ul class="add-to-links">
                                                                                    <li>
                                                                                        @if (Auth::user())
                                                                                            <a onclick="add_to_wish_list({{ $product->id }});"
                                                                                                title="Add to Wishlist"
                                                                                                class="link-wishlist">
                                                                                                <span>Add to Wishlist</span>
                                                                                            </a>
                                                                                        @else
                                                                                            <a onclick="alert('You need to login to add this product to the wish list!');"
                                                                                                title="Add to Wishlist"
                                                                                                class="link-wishlist">
                                                                                                <span>Add to
                                                                                                    Wishlist</span></a>
                                                                                        @endif
                                                                                    </li>
                                                                                    <li><a title="Add to Compare"
                                                                                            class="link-compare"
                                                                                            onclick="return add_to_compare({{$product->id}});"><span>Add to
                                                                                                Compare</span></a></li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>
                                                                        <div class="qv-button-container"> <a
                                                                                class="qv-e-button btn-quickview-1"
                                                                                onclick="return quick_view({{ $product->id }});">
                                                                                <span><span>Quick View</span></span>
                                                                            </a> </div>
                                                                    </div>
                                                                    <div class="info">
                                                                        <div class="info-inner">
                                                                            <div class="item-title"> <a
                                                                                    href="{{ URL('product-details/' . $product->id) }}"
                                                                                    title=" Sample Product">
                                                                                    {{ $product->name }} </a>
                                                                            </div>
                                                                            <!--item-title-->
                                                                            <div class="item-content">
                                                                                <div class="ratings">
                                                                                    <div class="rating-box">
                                                                                        <div style="width:{{ $product->rating }}%"
                                                                                            class="rating">
                                                                                        </div>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="price-box">
                                                                                    <p class="special-price"> <span
                                                                                            class="price">
                                                                                            $45.00 </span> </p>
                                                                                    <p class="old-price"> <span
                                                                                            class="price-sep">-</span> <span
                                                                                            class="price">
                                                                                            {{ $product->price }} </span>
                                                                                    </p>
                                                                                </div>
                                                                            </div>
                                                                            <!--item-content-->
                                                                        </div>
                                                                        <!--info-inner-->

                                                                        <!--actions-->

                                                                        <div class="clearfix"> </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- End Item -->
                                                        @endforeach
                                                    @else
                                                        <div class="inner-text">
                                                            <h3>None<h3>
                                                        </div>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="upsell-pro wow">
                                        <div class="slider-items-products">
                                            <div class="new_title center">
                                                <h2>Same category</h2>
                                            </div>
                                            <div id="upsell-products-slider" class="product-flexslider hidden-buttons">
                                                <div class="slider-items slider-width-col4">

                                                    @foreach ($same_category_products as $product)
                                                        <!-- Item -->
                                                        <div class="item">
                                                            <div class="col-item">
                                                                <div class="sale-label sale-top-right">Sale</div>
                                                                <div class="images-container"> <a class="product-image"
                                                                        title="Sample Product" href="product-detail.html">
                                                                        <img src="{{ url('uploads/products-images/' . $product->id . '/' . $product->image) }}"
                                                                            class="img-responsive" alt="a" /> </a></a>
                                                                    <div class="actions">
                                                                        <div class="actions-inner">
                                                                            <button type="button"
                                                                                onclick="add_to_cart({{ $product->id }});"
                                                                                title="Add to Cart"
                                                                                class="button btn-cart"><span>Add to
                                                                                    Cart</span>
                                                                            </button>
                                                                            <ul class="add-to-links">
                                                                                <li>
                                                                                    @if (Auth::user())
                                                                                        <a onclick="add_to_wish_list({{ $product->id }});"
                                                                                            title="Add to Wishlist"
                                                                                            class="link-wishlist">
                                                                                            <span>Add to Wishlist</span>
                                                                                        </a>
                                                                                    @else
                                                                                        <a onclick="alert('You need to login to add this product to the wish list!');"
                                                                                            title="Add to Wishlist"
                                                                                            class="link-wishlist">
                                                                                            <span>Add to Wishlist</span></a>
                                                                                    @endif
                                                                                </li>
                                                                                <li><a title="Add to Compare"
                                                                                        class="link-compare"
                                                                                        onclick="return add_to_compare({{$product->id}});"><span>Add to
                                                                                            Compare</span></a></li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>
                                                                    <div class="qv-button-container"> <a
                                                                            class="qv-e-button btn-quickview-1"
                                                                            onclick="return quick_view({{ $product->id }});">
                                                                            <span><span>Quick View</span></span>
                                                                        </a> </div>
                                                                </div>
                                                                <div class="info">
                                                                    <div class="info-inner">
                                                                        <div class="item-title"> <a
                                                                                href="{{ URL('product-details/' . $product->id) }}"
                                                                                title=" Sample Product">
                                                                                {{ $product->name }} </a>
                                                                        </div>
                                                                        <!--item-title-->
                                                                        <div class="item-content">
                                                                            <div class="ratings">
                                                                                <div class="rating-box">
                                                                                    <div style="width:{{ $product->rating * 20 }}%"
                                                                                        class="rating">
                                                                                    </div>
                                                                                </div>
                                                                            </div>
                                                                            <div class="price-box">
                                                                                <p class="special-price"> <span
                                                                                        class="price">
                                                                                        $45.00 </span> </p>
                                                                                <p class="old-price"> <span
                                                                                        class="price-sep">-</span> <span
                                                                                        class="price"> {{ $product->price }}
                                                                                    </span> </p>
                                                                            </div>
                                                                        </div>
                                                                        <!--item-content-->
                                                                    </div>
                                                                    <!--info-inner-->

                                                                    <!--actions-->

                                                                    <div class="clearfix"> </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <!-- End Item -->
                                                    @endforeach

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--End main-container -->
@endsection
