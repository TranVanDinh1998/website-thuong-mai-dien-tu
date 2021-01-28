@extends('layout')
@section('title', 'Home - Electronical Store')
@section('content')

    <!-- Slider -->
    <div id="magik-slideshow" class="magik-slideshow">
        <div class="wow">
            <div id='rev_slider_4_wrapper' class='rev_slider_wrapper fullwidthbanner-container'>
                <div id='rev_slider_4' class='rev_slider fullwidthabanner'>
                    <ul>
                        @foreach ($advertises as $advertise)
                            <li data-transition='random' data-slotamount='7' data-masterspeed='1000' data-thumb=''><img
                                    src='{{ url('uploads/advertises-images/' . $advertise->image) }}'
                                    data-bgposition='left top' data-bgfit='cover' data-bgrepeat='no-repeat' alt="banner" />
                                <div class='tp-caption ExtraLargeTitle sft  tp-resizeme ' data-x='15' data-y='80'
                                    data-endspeed='500' data-speed='500' data-start='1100' data-easing='Linear.easeNone'
                                    data-splitin='none' data-splitout='none' data-elementdelay='0.1'
                                    data-endelementdelay='0.1' style='z-index:2; white-space:nowrap;'>
                                    {{ $advertise->summary }}
                                </div>
                                <div class='tp-caption LargeTitle sfl  tp-resizeme ' data-x='15' data-y='135'
                                    data-endspeed='500' data-speed='500' data-start='1300' data-easing='Linear.easeNone'
                                    data-splitin='none' data-splitout='none' data-elementdelay='0.1'
                                    data-endelementdelay='0.1' style='z-index:3; white-space:nowrap;'>{{ $advertise->name }}
                                </div>
                                <div class='tp-caption sfb  tp-resizeme ' data-x='15' data-y='360' data-endspeed='500'
                                    data-speed='500' data-start='1500' data-easing='Linear.easeNone' data-splitin='none'
                                    data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1'
                                    style='z-index:4; white-space:nowrap;'><a
                                        href='{{ URL::to('product-details/' . $advertise->product_id) }}'
                                        class="view-more">View More</a>
                                    <button onclick="add_to_cart({{ $advertise->product_id }});" class="buy-btn">Buy
                                        Now</button>
                                </div>
                                <div class='tp-caption Title sft  tp-resizeme ' data-x='15' data-y='230' data-endspeed='500'
                                    data-speed='500' data-start='1500' data-easing='Power2.easeInOut' data-splitin='none'
                                    data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1'
                                    style='z-index:4; white-space:nowrap;'>{!! $advertise->description !!}</div>
                                <div class='tp-caption Title sft  tp-resizeme ' data-x='15' data-y='400' data-endspeed='500'
                                    data-speed='500' data-start='1500' data-easing='Power2.easeInOut' data-splitin='none'
                                    data-splitout='none' data-elementdelay='0.1' data-endelementdelay='0.1'
                                    style='z-index:4; white-space:nowrap;font-size:11px'></div>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tp-bannertimer"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- end Slider -->
    <!-- header service -->
    <div class="header-service">
        <div class="container">
            <div class="row">
                {{-- <div class="col-lg-3 col-sm-6 col-xs-12">
                    <div class="content">
                        <div class="icon-truck">&nbsp;</div>
                        <span><strong>FREE SHIPPING</strong> on order over $99</span>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-xs-12">
                    <div class="content">
                        <div class="icon-support">&nbsp;</div>
                        <span><strong>Customer Support</strong> Service</span>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-xs-12">
                    <div class="content">
                        <div class="icon-money">&nbsp;</div>
                        <span><strong>3 days Money Back</strong> Guarantee</span>
                    </div>
                </div>
                <div class="col-lg-3 col-sm-6 col-xs-12">
                    <div class="content">
                        <div class="icon-dis">&nbsp;</div>
                        <span><strong class="orange">5% discount</strong> on order over $199</span>
                    </div>
                </div> --}}
            </div>
        </div>
    </div>
    <!-- end header service -->

    <!-- offer banner section -->
    <div class="offer-banner-section">
        <div class="container">
            <div class="row">
                @foreach ($three_most_priority_collections as $collection)
                    <div class="col-md-4"><a title="{{ $collection->name }}"
                            href="{{ URL::to('filter/' . $collection->category_id . '/' . $collection->id) }}"><img
                                class="img-responsive" alt="{{ $collection->name }}"
                                src="{{ url('uploads/categories-images/' . $collection->category_id . '/' . $collection->image) }}"></a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- end offer banner section -->
    <!-- main container -->
    {{-- <section class="main-container col1-layout home-content-container">
        <div class="container">
            <div class="row">
                <div class="std">
                    <div class="col-lg-8 col-xs-12 col-sm-8 best-seller-pro wow">
                        <div class="slider-items-products">
                            <div class="new_title center">
                                <h2>Best Seller</h2>
                            </div>
                            <div id="best-seller-slider" class="product-flexslider hidden-buttons">
                                <div class="slider-items slider-width-col4">
                                    @foreach ($best_seller_products as $product)
                                        <!--Item -->
                                        <div class="item">
                                            <div class="col-item">
                                                @if((abs(strtotime(date('Y-m-d')) -
                                                strtotime($product->create_date))/60/60/24) < 10) <div
                                                    class="new-label new-top-left">New</div>

                                    @endif
                                    @if ($product->discount != null)
                                        <div class="sale-label sale-top-right">Sale </div>
                                    @endif
                                    <div class="images-container"> <a class="product-image" title="{{ $product->name }}"
                                            href="{{ URL('product-details/' . $product->id) }}"> <img
                                                src="{{ url('uploads/products-images/' . $product->id . '/' . $product->image) }}"
                                                class="img-responsive" alt="{{ $product->image }}" /> </a>
                                        <div class="actions">
                                            <div class="actions-inner">
                                                <button type="button" onclick="add_to_cart({{ $product->id }});"
                                                    title="Add to Cart" class="button btn-cart"><span>Add to
                                                        Cart</span></button>
                                                <ul class="add-to-links">
                                                    <li><a @if (Auth::user())
                                                            onclick="add_to_wish_list({{ $product->id }});"
                                                            @else
                                                            onclick="alert('You need to login to add this product to the
                                                            wish list!');"
                                                            @endif
                                                            title="Add to Wishlist" class="link-wishlist"><span>Add to
                                                                Wishlist</span></a>
                                                    </li>
                                                    <li><a href="" title="Add to Compare"
                                                            class="link-compare "><span>Add to
                                                                Compare</span></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="qv-button-container"> <a class="qv-e-button btn-quickview-1"
                                                onclick="return quick_view({{ $product->id }});"><span><span>Quick
                                                        View</span></span></a> </div>
                                    </div>
                                    <div class="info">
                                        <div class="info-inner">
                                            <div class="item-title"> <a title="{{ $product->name }}"
                                                    href="{{ URL('product-details/' . $product->id) }}">
                                                    {{ $product->name }} </a> </div>
                                            <!--item-title-->
                                            <div class="item-content">
                                                <div class="ratings">
                                                    <div class="rating-box">
                                                        <div style="width:{{ $product->rating * 20 }}%" class="rating">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="price-box">
                                                    @if ($product->discount != null)
                                                        <p class="special-price"> <span class="price">
                                                                {{ $product->price - ($product->price * $product->discount) / 100 }}
                                                                <small>vnd</small>
                                                            </span>
                                                        </p>
                                                        <p class="old-price"> <span class="price-sep">-</span> <span
                                                                class="price"> {{ $product->price }} <small>vnd</small>
                                                            </span> </p>
                                                        @else
                                                        <p class="regular-price"> <span class="price"> {{ $product->price }}
                                                                <small>vnd</small>
                                                            </span>
                                                        </p>
                                                    @endif

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
                            <!-- end of Item -->
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-xs-12 col-sm-4 wow latest-pro small-pr-slider">
                <div class="slider-items-products">
                    <div class="new_title center">
                        <h2>Latest Products</h2>
                    </div>
                    <div id="latest-deals-slider" class="product-flexslider hidden-buttons latest-item">
                        <div class="slider-items slider-width-col4">
                            @foreach ($newest_products as $product)
                                <!-- Item -->
                                <div class="item">
                                    <div class="col-item">
                                        @if((abs(strtotime(date('Y-m-d')) -
                                        strtotime($product->create_date))/60/60/24) < 10) <div
                                            class="new-label new-top-left">New</div>

                            @endif
                            @if ($product->discount != null)
                                <div class="sale-label sale-top-right">Sale </div>
                            @endif
                            <div class="images-container"> <a class="product-image" title="{{ $product->name }}"
                                    href="{{ URL('product-details/' . $product->id) }}"> <img
                                        src="{{ url('uploads/products-images/' . $product->id . '/' . $product->image) }}"
                                        class="img-responsive" alt="product-image" /> </a>
                                <div class="actions">
                                    <div class="actions-inner">
                                        <ul class="add-to-links">
                                            <li><a @if (Auth::user())
                                                    onclick="add_to_wish_list({{ $product->id }});"
                                                    @else
                                                    onclick="alert('You need to login to add this product to the wish
                                                    list!');"
                                                    @endif
                                                    title="Add to Wishlist" class="link-wishlist"><span>Add to
                                                        Wishlist</span></a>
                                            </li>
                                            <li><a href="" title="Add to Compare"
                                                    class="link-compare "><span>Add to
                                                        Compare</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="qv-button-container"> <a class="qv-e-button btn-quickview-1"
                                        onclick="return quick_view({{ $product->id }});"><span><span>Quick
                                                View</span></span></a> </div>
                            </div>
                            <div class="info">
                                <div class="info-inner">
                                    <div class="item-title"> <a title="{{ $product->name }}"
                                            href="{{ URL('product-details/' . $product->id) }}">
                                            {{ $product->name }} </a> </div>
                                    <!--item-title-->
                                    <div class="item-content">
                                        <div class="ratings">
                                            <div class="rating-box">
                                                <div style="width:{{ $product->rating * 20 }}%" class="rating">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="price-box">
                                            @if ($product->discount != null)
                                                <p class="special-price"> <span class="price">
                                                        {{ $product->price - ($product->price * $product->discount) / 100 }}
                                                        <small>vnd</small>
                                                    </span>
                                                </p>
                                                <p class="old-price"> <span class="price-sep">-</span> <span class="price">
                                                        {{ $product->price }} <small>vnd</small>
                                                    </span> </p>
                                                @else
                                                <p class="regular-price"> <span class="price"> {{ $product->price }}
                                                        <small>vnd</small>
                                                    </span>
                                                </p>
                                            @endif

                                        </div>
                                    </div>
                                    <!--item-content-->
                                </div>
                                <!--info-inner-->
                                <div class="actions">
                                    <button onclick="add_to_cart({{ $product->id }});" class="button btn-cart"
                                        title="Add to Cart" type="button"><span>Add to Cart</span></button>
                                </div>
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
    </section> --}}
    <!-- End main container -->
    <!-- main container -->
    <section class="main-container col1-layout home-content-container">
        <div class="container">
            <div class="row">
                <div class="std">
                    <div class="col-lg-8 col-xs-12 col-sm-8 best-seller-pro wow">
                        <div class="slider-items-products">
                            <div class="new_title center">
                                <h2>Best Seller</h2>
                            </div>
                            <div id="best-seller-slider" class="product-flexslider hidden-buttons">
                                <div class="slider-items slider-width-col4">
                                    @foreach ($best_seller_products as $product)
                                        <!-- Item -->
                                        <div class="item">
                                            <div class="col-item">
                                                @if((abs(strtotime(date('Y-m-d'))-strtotime($product->create_date))/60/60/24)
                                                < 10) <div class="new-label new-top-left">
                                                    New
                                            </div>
                                    @endif
                                    @if ($product->discount != null)
                                        <div class="sale-label sale-top-right">Sale</div>
                                    @endif
                                    <div class="images-container">
                                        <a class="product-image" title="{{ $product->name }}"
                                            href="{{ URL('product-details/' . $product->id) }}"> <img
                                                src="{{ url('uploads/products-images/' . $product->id . '/' . $product->image) }}"
                                                class="img-responsive" alt="a" />
                                        </a>
                                        <div class="actions">
                                            <div class="actions-inner">
                                                <button type="button" onclick="add_to_cart({{ $product->id }});"
                                                    title="Add to Cart" class="button btn-cart"><span>Add to Cart</span>
                                                </button>
                                                <ul class="add-to-links">
                                                    <li>
                                                        @if (Auth::user())
                                                            <a onclick="add_to_wish_list({{ $product->id }});"
                                                                title="Add to Wishlist" class="link-wishlist">
                                                                <span>Add to Wishlist</span>
                                                            </a>
                                                        @else
                                                            <a onclick="alert('You need to login to add this product to the wish list!');"
                                                                title="Add to Wishlist" class="link-wishlist">
                                                                <span>Add to Wishlist</span></a>
                                                        @endif
                                                    </li>
                                                    <li><a onclick="add_to_compare({{$product->id}})" title="Add to Compare" class="link-compare ">
                                                            <span>Add to Compare</span></a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="qv-button-container">
                                            <a class="qv-e-button btn-quickview-1"
                                                onclick="return quick_view({{ $product->id }});">
                                                <span><span>Quick View</span></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div class="info">
                                        <div class="info-inner">
                                            <div class="item-title">
                                                <a title="{{ $product->name }}"
                                                    href="{{ URL('product-details/' . $product->id) }}">
                                                    {{ $product->name }}
                                                </a>
                                            </div>
                                            <!--item-title-->
                                            <div class="item-content">
                                                <div class="ratings">
                                                    <div class="rating-box">
                                                        <div style="width:{{ $product->rating * 20 }}%" class="rating">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="price-box">
                                                    @if ($product->discount != null)
                                                        <p class="special-price">
                                                            <span class="price">
                                                                {{ $product->price - ($product->price * $product->discount) / 100 }}
                                                                <small>vnd</small>
                                                            </span>
                                                        </p>
                                                        <p class="old-price">
                                                            <span class="price-sep">-</span>
                                                            <span class="price">{{ $product->price }} <small>vnd</small>
                                                            </span>
                                                        </p>
                                                    @else
                                                        <p class="regular-price">
                                                            <span class="price"> {{ $product->price }}<small>vnd</small>
                                                            </span>
                                                        </p>
                                                    @endif

                                                </div>
                                            </div>
                                            <!--item-content-->
                                        </div>
                                        <!--info-inner-->
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
            <div class="col-lg-4 col-xs-12 col-sm-4 wow latest-pro small-pr-slider">
                <div class="slider-items-products">
                    <div class="new_title center">
                        <h2>Latest Products</h2>
                    </div>
                    <div id="latest-deals-slider" class="product-flexslider hidden-buttons latest-item">
                        <div class="slider-items slider-width-col4">

                            @foreach ($newest_products as $product)
                                <!-- Item -->
                                <div class="item">
                                    <div class="col-item">
                                        <div class="images-container">
                                            <a class="product-image" title="{{ $product->name }}"
                                                href="{{ URL('product-details/' . $product->id) }}"> <img
                                                    src="{{ url('uploads/products-images/' . $product->id . '/' . $product->image) }}"
                                                    class="img-responsive" alt="a" />
                                            </a>
                                            <div class="actions">
                                                <div class="actions-inner">
                                                    <ul class="add-to-links">
                                                        <li>
                                                            @if (Auth::user())
                                                                <a onclick="add_to_wish_list({{ $product->id }});"
                                                                    title="Add to Wishlist" class="link-wishlist">
                                                                    <span>Add to Wishlist</span>
                                                                </a>
                                                            @else
                                                                <a onclick="alert('You need to login to add this product to the wish list!');"
                                                                    title="Add to Wishlist" class="link-wishlist">
                                                                    <span>Add to Wishlist</span></a>
                                                            @endif
                                                        </li>
                                                        <li><a onclick="add_to_compare({{$product->id}})" title="Add to Compare" class="link-compare ">
                                                                <span>Add to Compare</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="qv-button-container">
                                                <a class="qv-e-button btn-quickview-1"
                                                    onclick="return quick_view({{ $product->id }});">
                                                    <span><span>Quick View</span></span>
                                                </a>
                                            </div>
                                        </div>
                                        <div class="info">
                                            <div class="info-inner">
                                                <div class="item-title">
                                                    <a title="{{ $product->name }}"
                                                        href="{{ URL('product-details/' . $product->id) }}">
                                                        {{ $product->name }}
                                                    </a>
                                                </div>
                                                <!--item-title-->
                                                <div class="item-content">
                                                    <div class="ratings">
                                                        <div class="rating-box">
                                                            <div style="width:{{ $product->rating * 20 }}%" class="rating">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="price-box">
                                                        @if ($product->discount != null)
                                                            <p class="special-price">
                                                                <span class="price">
                                                                    {{ $product->price - ($product->price * $product->discount) / 100 }}
                                                                    <small>vnd</small>
                                                                </span>
                                                            </p>
                                                            <p class="old-price">
                                                                <span class="price-sep">-</span>
                                                                <span class="price">{{ $product->price }} <small>vnd</small>
                                                                </span>
                                                            </p>
                                                        @else
                                                            <p class="regular-price">
                                                                <span class="price"> {{ $product->price }}<small>vnd</small>
                                                                </span>
                                                            </p>
                                                        @endif

                                                    </div>
                                                </div>
                                                <!--item-content-->
                                            </div>
                                            <!--info-inner-->
                                            <div class="actions">
                                                <button class="button btn-cart" onclick="return add_to_cart({{$product->id}});" title="Add to Cart" type="button"><span>Add to Cart</span></button>
                                              </div>
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
    </section>
    <!-- End main container -->

    <!-- recommend slider -->
    <section class="recommend container">
        <div class="new-pro-slider small-pr-slider" style="overflow:visible">
            <div class="slider-items-products">
                <div class="new_title center">
                    <h2>FEATURED PRODUCTS</h2>
                </div>
                <div id="recommend-slider" class="product-flexslider hidden-buttons">
                    <div class="slider-items slider-width-col3">
                        @foreach ($feature_products as $product)
                            <!-- Item -->
                            <div class="item">
                                <div class="col-item">
                                    @if((abs(strtotime(date('Y-m-d')) -
                                    strtotime($product->create_date))/60/60/24) < 10) <div class="new-label new-top-left">
                                        New</div>

                        @endif
                        @if ($product->discount != null)
                            <div class="sale-label sale-top-right">Sale </div>
                        @endif
                        <div class="images-container"> <a class="product-image" title="{{ $product->name }}"
                                href="{{ URL('product-details/' . $product->id) }}"> <img
                                    src="{{ url('uploads/products-images/' . $product->id . '/' . $product->image) }}"
                                    class="img-responsive" alt="a" /> </a>
                            <div class="actions">
                                <div class="actions-inner">
                                    <ul class="add-to-links">
                                        <li><a @if (Auth::user())
                                                onclick="add_to_wish_list({{ $product->id }});"
                                            @else
                                                onclick="alert('You need to login to add this product to the wish list!');"
                                                @endif
                                                title="Add to Wishlist" class="link-wishlist"><span>Add to
                                                    Wishlist</span></a>
                                        </li>
                                        <li><a onclick="add_to_compare({{$product->id}})" title="Add to Compare" class="link-compare "><span>Add to
                                                    Compare</span></a></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="qv-button-container"> <a onclick="return quick_view({{ $product->id }});"
                                    class="qv-e-button btn-quickview-1"><span><span>Quick View</span></span></a>
                            </div>
                        </div>
                        <div class="info">
                            <div class="info-inner">
                                <div class="item-title"> <a title="{{ $product->name }}"
                                        href="{{ URL('product-details/' . $product->id) }}">
                                        {{ $product->name }} </a> </div>
                                <!--item-title-->
                                <div class="item-content">
                                    <div class="ratings">
                                        <div class="rating-box">
                                            <div style="width:{{ $product->rating*20 }}%" class="rating"></div>
                                        </div>
                                    </div>
                                    <div class="price-box">
                                        @if ($product->discount != null)
                                            <p class="special-price"> <span class="price">
                                                    {{ $product->price - ($product->price * $product->discount) / 100 }}
                                                    <small>vnd</small>
                                                </span>
                                            </p>
                                            <p class="old-price"> <span class="price-sep">-</span> <span class="price">
                                                    {{ $product->price }} <small>vnd</small>
                                                </span> </p>
                                        @else
                                            <p class="regular-price"> <span class="price"> {{ $product->price }}
                                                    <small>vnd</small>
                                                </span>
                                            </p>
                                        @endif

                                    </div>
                                </div>
                                <!--item-content-->
                            </div>
                            <!--info-inner-->
                            <div class="actions">
                                <button type="button" onclick="add_to_cart({{ $product->id }});" title="Add to Cart"
                                    class="button btn-cart"><span>Add to
                                        Cart</span></button>
                            </div>
                            <!--actions-->
                            <div class="clearfix"> </div>
                        </div>
                    </div>
                </div>
                <!-- End Item -->
                @endforeach

            </div>
        </div>
        {{-- </div>
        </div> --}}
    </section>
    <!-- End Recommend slider -->
    <!-- banner section -->
    {{-- <div class="top-offer-banner wow">
        <div class="container">
            <div class="row">
                <div class="offer-inner col-lg-12">
                    <!--newsletter-wrap-->
                    <div class="left">
                        <div class="col-1">
                            <div class="block-subscribe">
                                <div class="newsletter">
                                    <form>
                                        <h4><span>Subscribe Newsletter</span></h4>
                                        <h5> Get the latest news & updates from Inspire</h5>
                                        <input type="text" placeholder="Enter your email address"
                                            class="input-text required-entry validate-email"
                                            title="Sign up for our newsletter" id="newsletter1" name="email">
                                        <button class="subscribe" title="Subscribe"
                                            type="submit"><span>Subscribe</span></button>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="col mid">
                            <div class="inner-text">
                                <h3>{{ $three_most_priority_collections[1]->name }}</h3>
                            </div>
                            <a href="#"><img 
                                    src="{{ url('images/offer-banner2.jpg') }}"
                                    alt="offer banner2"></a>
                        </div>
                        <div class="col last">
                            <div class="inner-text">
                                <h3>{{ $three_most_priority_collections[2]->name }}</h3>
                            </div>
                            <a href="#"><img src="{{ url('images/offer-banner3.jpg') }}" 
                                    alt="offer banner2"></a>
                        </div>
                    </div>
                    <div class="right">
                        <div class="col">
                            <div class="inner-text">
                                <h4>Top COLLECTION</h4>
                                <h3>{{ $three_most_priority_collections[0]->name }}</h3>
                                <a href="#" class="shop-now1">Shop now</a>
                            </div>
                            <a href="#" title=""><img src="{{ url('images/offer-banner4.jpg') }}"
                                    alt=""></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> --}}

    <!-- End banner section -->

    <!-- middle slider -->
    <section class="middle-slider container">
        <div class="row">
            <div class="col-sm-4 custom-slider">
                <div>
                    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <li data-target="#carousel-example-generic" data-slide-to="0" class="active"></li>
                            <li data-target="#carousel-example-generic" data-slide-to="1"></li>
                        </ol>

                        <!-- Wrapper for slides -->
                        <div class="carousel-inner" role="listbox">
                            <div class="item active"> <img
                                    src="{{ url('uploads/categories-images/' . $two_newest_collections[0]->category_id . '/' . $two_newest_collections[0]->image) }}"
                                    alt="slide1">
                                <div class="carousel-caption">
                                    <h3><a href=""
                                            title="{{ $two_newest_collections[0]->name }}">{{ $two_newest_collections[0]->name }}</a>
                                    </h3>
                                    <p>{!! $two_newest_collections[0]->description !!}</p>
                                </div>
                            </div>
                            <div class="item"> <img
                                    src="{{ url('uploads/categories-images/' . $two_newest_collections[0]->category_id . '/' . $two_newest_collections[1]->image) }}"
                                    alt="slide1">
                                <div class="carousel-caption">
                                    <h3><a href=""
                                            title="{{ $two_newest_collections[1]->name }}">{{ $two_newest_collections[1]->name }}</a>
                                    </h3>
                                    <p>{!! $two_newest_collections[1]->description !!}</p>
                                </div>
                            </div>
                        </div>

                        <!-- Controls -->
                        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
                            <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span> <span
                                class="sr-only">Previous</span> </a> <a class="right carousel-control"
                            href="#carousel-example-generic" role="button" data-slide="next"> <span
                                class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span> <span
                                class="sr-only">Next</span> </a>
                    </div>
                </div>
            </div>
            @foreach ($two_newest_collections as $collection)
                <div class="col-sm-4 pro-block">
                    <div class="inner-div">
                        <h2 class="category-pro-title"><span>{{ $collection->name }} Collection</span></h2>
                        <div class="category-products">
                            <div class="products small-list">
                                @for($i = 0; $i < $collection->collection_products()->count() ; $i++)
                                <div class="item">
                                    <div class="item-area">
                                        <div class="product-image-area"> <a
                                                href="{{ URL::to('product-details/' . $collection->collection_products[$i]->product->id) }}"
                                                class="product-image"> <img
                                                    src="{{ url('uploads/products-images/' . $collection->collection_products[$i]->product->id . '/' . $collection->collection_products[$i]->product->image) }}"
                                                    alt="products images"> </a> </div>
                                        <div class="details-area">
                                            <h2 class="product-name"><a
                                                    href="{{ URL::to('product-details/' . $collection->collection_products[$i]->product->id) }}">{{ $collection->collection_products[$i]->product->name }}</a>
                                            </h2>
                                            <div class="ratings">
                                                <div class="rating-box">
                                                    <div style="width:{{ $collection->collection_products[$i]->product->rating*20 }}%" class="rating"></div>
                                                </div>
                                            </div>
                                            <div class="price-box">
                                                @if ($collection->collection_products[$i]->product->discount != null)
                                                    <p class="special-price"> <span class="price">
                                                            {{ $collection->collection_products[$i]->product->price - ($collection->collection_products[$i]->product->price * $collection->collection_products[$i]->product->discount) / 100 }}
                                                            <small>vnd</small>
                                                        </span>
                                                    </p>
                                                    <p class="old-price"> <span class="price-sep">-</span> <span
                                                            class="price">
                                                            {{ $collection->collection_products[$i]->product->price }} <small>vnd</small>
                                                        </span> </p>
                                                @else
                                                    <p class="regular-price"> <span class="price">
                                                            {{ $collection->collection_products[$i]->product->price }}
                                                            <small>vnd</small>
                                                        </span>
                                                    </p>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @break($i == 2)
                                @endfor
                                {{-- @foreach ($collection->collection_products as $collection_product)
                                    <div class="item">
                                        <div class="item-area">
                                            <div class="product-image-area"> <a
                                                    href="{{ URL::to('product-details/' . $collection_product->product->id) }}"
                                                    class="product-image"> <img
                                                        src="{{ url('uploads/products-images/' . $collection_product->product->id . '/' . $collection_product->product->image) }}"
                                                        alt="products images"> </a> </div>
                                            <div class="details-area">
                                                <h2 class="product-name"><a
                                                        href="{{ URL::to('product-details/' . $collection_product->product->id) }}">{{ $collection_product->product->name }}</a>
                                                </h2>
                                                <div class="ratings">
                                                    <div class="rating-box">
                                                        <div style="width:{{ $collection_product->product->rating*20 }}%" class="rating"></div>
                                                    </div>
                                                </div>
                                                <div class="price-box">
                                                    @if ($collection_product->product->discount != null)
                                                        <p class="special-price"> <span class="price">
                                                                {{ $collection_product->product->price - ($collection_product->product->price * $collection_product->product->discount) / 100 }}
                                                                <small>vnd</small>
                                                            </span>
                                                        </p>
                                                        <p class="old-price"> <span class="price-sep">-</span> <span
                                                                class="price">
                                                                {{ $collection_product->product->price }} <small>vnd</small>
                                                            </span> </p>
                                                    @else
                                                        <p class="regular-price"> <span class="price">
                                                                {{ $collection_product->product->price }}
                                                                <small>vnd</small>
                                                            </span>
                                                        </p>
                                                    @endif

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @break
                                @endforeach --}}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
    <!-- End middle slider -->

    <!-- promo banner section -->
    <div class="promo-banner-section container wow">
        <div class="container">
            <div class="row"> <img alt="promo-banner3" src="{{ url('images/bottom-banner.jpg') }}"></div>
        </div>
    </div>
    <!-- End promo banner section -->

    <!-- Latest Blog -->
    <section class="latest-blog container wow">
        <div class="blog-title">
            <h2><span>Latest Coupon</span></h2>
        </div>
        <div class="row">
            @foreach ($coupons as $coupon)
                <div class="col-xs-12 col-sm-4">
                    <div class="blog-img">
                        <img style="height: 250px; width:100%;object-fit:cover;" class="img-responsive"
                            src="{{ url('uploads/coupons-images/' . $coupon->image) }}" alt="Image">
                        <!--<div class="mask"> <a class="info" href="blog-detail.html">Read More</a> </div>-->
                    </div>
                    {{-- <h3><a href="">{{ $coupon->name }}</a> </h3>
                    --}}
                    <h4 class="item-title">{{ $coupon->name }}</h4>
                    <p class="text-primary">Code : {{ $coupon->code }}
                    <p>
                    <div class="post-date"><i class="icon-calendar"></i> {{ $coupon->create_date }}</div>
                </div>
            @endforeach
        </div>
    </section>
    <!-- End Latest Blog -->

    <!-- Featured Slider -->
    <section class="featured-pro wow animated parallax parallax-2">
        <div class="container">
            <div class="std">
                <div class="slider-items-products">
                    <div class="featured_title center">
                        <h2>Sale Products</h2>
                    </div>
                    <div id="featured-slider" class="product-flexslider hidden-buttons">
                        <div class="slider-items slider-width-col4">
                            @foreach ($sale_products as $product)
                                <!-- Item -->
                                <div class="item">
                                    <div class="col-item">

                                        @if((abs(strtotime(date('Y-m-d'))-strtotime($product->create_date))/60/60/24) < 10)
                                            <div class="new-label new-top-left">
                                            New
                                    </div>
                            @endif
                            @if ($product->discount != null)
                                <div class="sale-label sale-top-right">Sale</div>
                            @endif
                            <div class="images-container">
                                <a class="product-image" title="{{ $product->name }}"
                                    href="{{ URL('product-details/' . $product->id) }}"> <img
                                        src="{{ url('uploads/products-images/' . $product->id . '/' . $product->image) }}"
                                        class="img-responsive" alt="a" />
                                </a>
                                <div class="actions">
                                    <div class="actions-inner">
                                        <button type="button" onclick="add_to_cart({{ $product->id }});" title="Add to Cart"
                                            class="button btn-cart"><span>Add to Cart</span>
                                        </button>
                                        <ul class="add-to-links">
                                            <li>
                                                @if (Auth::user())
                                                    <a onclick="add_to_wish_list({{ $product->id }});"
                                                        title="Add to Wishlist" class="link-wishlist">
                                                        <span>Add to Wishlist</span>
                                                    </a>
                                                @else
                                                    <a onclick="alert('You need to login to add this product to the wish list!');"
                                                        title="Add to Wishlist" class="link-wishlist">
                                                        <span>Add to Wishlist</span></a>
                                                @endif
                                            </li>
                                            <li><a onclick="add_to_compare({{$product->id}})" title="Add to Compare" class="link-compare ">
                                                    <span>Add to Compare</span></a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="qv-button-container">
                                    <a class="qv-e-button btn-quickview-1" onclick="return quick_view({{ $product->id }});">
                                        <span><span>Quick View</span></span>
                                    </a>
                                </div>
                            </div>
                            <div class="info">
                                <div class="info-inner">
                                    <div class="item-title">
                                        <a title="{{ $product->name }}" href="{{ URL('product-details/' . $product->id) }}">
                                            {{ $product->name }}
                                        </a>
                                    </div>
                                    <!--item-title-->
                                    <div class="item-content">
                                        <div class="ratings">
                                            <div class="rating-box">
                                                <div style="width:{{ $product->rating * 20 }}%" class="rating">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="price-box">
                                            @if ($product->discount != null)
                                                <p class="special-price">
                                                    <span class="price">
                                                        {{ $product->price - ($product->price * $product->discount) / 100 }}
                                                        <small>vnd</small>
                                                    </span>
                                                </p>
                                                <p class="old-price">
                                                    <span class="price-sep">-</span>
                                                    <span class="price">{{ $product->price }} <small>vnd</small>
                                                    </span>
                                                </p>
                                            @else
                                                <p class="regular-price">
                                                    <span class="price"> {{ $product->price }}<small>vnd</small>
                                                    </span>
                                                </p>
                                            @endif

                                        </div>
                                    </div>
                                    <!--item-content-->
                                </div>
                                <!--info-inner-->
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
    </section>
    <!-- End Featured Slider -->
    <script type='text/javascript'>
        jQuery(document).ready(function() {
            jQuery('#rev_slider_4').show().revolution({
                dottedOverlay: 'none',
                delay: 5000,
                startwidth: 1170,
                startheight: 580,

                hideThumbs: 200,
                thumbWidth: 200,
                thumbHeight: 50,
                thumbAmount: 2,

                navigationType: 'thumb',
                navigationArrows: 'solo',
                navigationStyle: 'round',

                touchenabled: 'on',
                onHoverStop: 'on',

                swipe_velocity: 0.7,
                swipe_min_touches: 1,
                swipe_max_touches: 1,
                drag_block_vertical: false,

                spinner: 'spinner0',
                keyboardNavigation: 'off',

                navigationHAlign: 'center',
                navigationVAlign: 'bottom',
                navigationHOffset: 0,
                navigationVOffset: 20,

                soloArrowLeftHalign: 'left',
                soloArrowLeftValign: 'center',
                soloArrowLeftHOffset: 20,
                soloArrowLeftVOffset: 0,

                soloArrowRightHalign: 'right',
                soloArrowRightValign: 'center',
                soloArrowRightHOffset: 20,
                soloArrowRightVOffset: 0,

                shadow: 0,
                fullWidth: 'on',
                fullScreen: 'off',

                stopLoop: 'off',
                stopAfterLoops: -1,
                stopAtSlide: -1,

                shuffle: 'off',

                autoHeight: 'off',
                forceFullWidth: 'on',
                fullScreenAlignForce: 'off',
                minFullScreenHeight: 0,
                hideNavDelayOnMobile: 1500,

                hideThumbsOnMobile: 'off',
                hideBulletsOnMobile: 'off',
                hideArrowsOnMobile: 'off',
                hideThumbsUnderResolution: 0,

                hideSliderAtLimit: 0,
                hideCaptionAtLimit: 0,
                hideAllCaptionAtLilmit: 0,
                startWithSlide: 0,
                fullScreenOffsetContainer: ''
            });
        });

    </script>
@endsection
