<header class="header-container">
    <div class="header-top">
        <div class="container">
            <div class="row">
                <!-- Header Language -->
                <div class="col-xs-6">
                    {{-- <div class="dropdown block-language-wrapper"> <a role="button"
                            data-toggle="dropdown" data-target="#" class="block-language dropdown-toggle" href="#"> <img
                                src="{{ url('images/english.png') }}" alt="language"> English <span
                                class="caret"></span> </a>
                        <ul class="dropdown-menu" role="menu">
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="#"><img
                                        src="{{ url('images/english.png') }}" alt="language"> English </a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="#"><img
                                        src="{{ url('images/francais.png') }}" alt="language"> French </a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="#"><img
                                        src="{{ url('images/german.png') }}" alt="language"> German </a></li>
                        </ul>
                    </div> --}}
                    <!-- End Header Language -->
                    <!-- Header Currency -->
                    {{-- <div class="dropdown block-currency-wrapper"> <a role="button"
                            data-toggle="dropdown" data-target="#" class="block-currency dropdown-toggle" href="#"> USD
                            <span class="caret"></span></a>
                        <ul class="dropdown-menu" role="menu">
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="#"> $ - Dollar </a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="#"> £ - Pound </a></li>
                            <li role="presentation"><a role="menuitem" tabindex="-1" href="#"> € - Euro </a></li>
                        </ul>
                    </div> --}}
                    <!-- End Header Currency -->
                    <div class="welcome-msg hidden-xs">
                        Welcome
                        @if (Auth::user())
                            {{ Auth::user()->name }}
                        @else
                            to the shop
                        @endif
                    </div>
                </div>
                <div class="col-xs-6">
                    <!-- Header Top Links -->
                    <div class="toplinks">
                        <div class="links">
                            <div class="myaccount">
                                @if (Auth::user() && Auth::user()->is_admin == 1)
                                    <a title="My management" href="{{ route('admin.index') }}"><span
                                            class="hidden-xs">My Management</span></a>
                                @endif
                            </div>
                            <div class="myaccount"><a title="My Account" href="{{ URL::to('account') }}"><span
                                        class="hidden-xs">My
                                        Account</span></a></div>
                            <div class="wishlist"><a title="My Wishlist" href="{{ URL::to('account/wish-list') }}"><span
                                        class="hidden-xs">Wishlist</span></a></div>
                            <div class="check"><a title="Checkout" href="{{ URL::to('/check-out') }}"><span
                                        class="hidden-xs">Checkout</span></a>
                            </div>
                            <div class="phone hidden-xs">1 800 000 000</div>
                        </div>
                    </div>
                    <!-- End Header Top Links -->
                </div>
            </div>
        </div>
    </div>
    <div class="header container">
        <div class="row">
            <div class="col-lg-2 col-sm-3 col-md-2 col-xs-12">
                <!-- Header Logo -->
                <a class="logo" title="Magento Commerce" href="{{ URL::to('/') }}"><img alt="Magento Commerce"
                        src="{{ url('images/logo.png') }}"></a>
                <!-- End Header Logo -->
            </div>
            <div class="col-lg-7 col-sm-4 col-md-6 col-xs-12">
                <!-- Search-col -->
                <div class="search-box" style="border: none !important;">
                    <div class="panel">
                        <form autocomplete="off" class="form-inline navbar-form" action="{{ route('search') }}"
                            method="GET">
                            <select name="category_id" class="cate-dropdown hidden-xs">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}">&nbsp;&nbsp;&nbsp;{{ $category->name }}</option>
                                @endforeach
                            </select>
                            <input type="text" autocomplete="off" placeholder="Search here..." class="form-control"
                                name="search" id="search" required>
                            <button type="submit" class="search-btn-bg"><span>Search</span></button>
                        </form>
                    </div>
                </div>
                {{-- <script>
                    function get_result(search_for) {
                        $.ajax({
                            url: "{{ url('/mini-search') }}",
                            type: 'GET',
                            data: {
                                search: search_for
                            },
                            success: function(response) {
                                console.log(response.message);
                                if (response.error == false) {
                                    $("#result").html(response.message);
                                }
                            },

                        });
                        return false;
                    }
                    $(document).ready(function() {
                        $("#search").on("input", function() {
                            get_result($("#search").val());
                        })
                        // $("#search").on("input", function() {
                        //     var search_for = $("#search").val();
                        //     $.ajax({
                        //         url: "{{ url('/mini-search') }}",
                        //         type: 'GET',
                        //         data: {
                        //             search: search_for,
                        //         },
                        //         success: function(response) {
                        //             console.log(response.message);
                        //             if (response.error == false) {
                        //                 $("#result").html(response.message);
                        //             }
                        //         }
                        //     });
                        // });


                    });

                </script> --}}
                <!-- End Search-col -->


            </div>
            <!-- Top Cart -->
            <div class="col-lg-3 col-sm-5 col-md-4 col-xs-12">
                <div class="top-cart-contain">
                    <div class="mini-cart">
                        <div data-toggle="dropdown" data-hover="dropdown" class="basket dropdown-toggle">
                            <a href="{{ URL::to('/cart') }}"> <i class="icon-cart"></i>
                                <div class="cart-box"><span class="title">My Cart</span>
                                    @if (isset($count_cart) && $count_cart != null)
                                        <span id="cart-total">
                                            {{ $count_cart }}
                                        </span>
                                    @endif
                                </div>
                            </a>
                        </div>
                        <div>
                            <div class="top-cart-content arrow_box">
                                <div class="block-subtitle">Recently added item(s)</div>
                                <ul id="cart-sidebar" class="mini-products-list">
                                    @if (isset($shopping_carts) && $shopping_carts != null)
                                        @foreach ($shopping_carts as $product_id => $info)
                                            <li class="item even"> <a class="product-image"
                                                    href="{{ URL::to('/product-details/' . $product_id) }}"
                                                    title="Downloadable Product "><img
                                                        alt="{{ $info['product_name'] }} "
                                                        src="{{ url('uploads/products-images/' . $product_id . '/' . $info['product_image']) }}"
                                                        width="80"></a>
                                                <div class="detail-item">
                                                    <div class="product-details">
                                                        <a onclick="remove_item_from_cart({{ $product_id }});"
                                                            title="Remove This Item" onClick=""
                                                            class="glyphicon glyphicon-remove">&nbsp;</a>
                                                        {{-- <a
                                                            class="glyphicon glyphicon-pencil" title="Edit item"
                                                            href="#">&nbsp;</a> --}}
                                                        <p class="product-name"> <a
                                                                href="{{ URL::to('product-details/' . $product_id) }}"
                                                                title="Downloadable Product">
                                                                {{ $info['product_name'] }} </a> </p>
                                                    </div>
                                                    <div class="product-details-bottom">
                                                        <span class="price">
                                                            Price :
                                                            @if ($info['product_discount'] != null)
                                                                {{ $info['product_price'] - ($info['product_price'] * $info['product_discount']) / 100 }}
                                                                vnd
                                                            @else
                                                                {{ $info['product_price'] }}
                                                                vnd
                                                            @endif
                                                        </span>
                                                        <span class="title-desc">Quantity:</span>
                                                        <strong>{{ $info['product_quantity'] }}</strong>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                                <div class="top-subtotal">Subtotal: <span class="price">
                                        @if (isset($total_cart))
                                            {{ $total_cart }}
                                        @endif
                                    </span>
                                </div>
                                <div class="actions">
                                    <a class="btn-checkout" href="{{ URL::to('/check-out') }}"><span>Checkout</span></a>
                                    <a href="{{ URL::to('/cart') }}" class="view-cart" type="button"><span>View
                                            Cart</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="ajaxconfig_info" style="display:none"> <a href="#/"></a>
                        <input value="" type="hidden">
                        <input id="enable_module" value="1" type="hidden">
                        <input class="effect_to_cart" value="1" type="hidden">
                        <input class="title_shopping_cart" value="Go to shopping cart" type="hidden">
                    </div>
                </div>
                @if (!Auth::user())
                    <div class="signup"><a title="Login" href="{{ URL::to('/register') }}"><span>Sign up Now</span></a>
                    </div>
                    <span class="or"> | </span>
                    <div class="login"><a title="Login" href="{{ URL::to('/login') }}"><span>Log In</span></a></div>
            </div>
        @else
            <div class="signup"><a title="Logout" href="{{ URL::to('/account/logout') }}"><span>Logout</span></a>
            </div>
            @endif
            <!-- End Top Cart -->
        </div>
    </div>
</header>
<!-- Navbar -->
<nav>
    <div class="container">
        <div class="nav-inner">

            <!-- mobile-menu -->
            <div class="hidden-desktop" id="mobile-menu">
                <ul class="navmenu">
                    <li>
                        <div class="menutop">
                            <div class="toggle"> <span class="icon-bar"></span> <span class="icon-bar"></span> <span
                                    class="icon-bar"></span></div>
                            <h2>Menu</h2>
                        </div>
                        <ul style="display:none;" class="submenu">
                            <li>
                                <ul class="topnav">
                                    <li class="level0 nav-6 level-top first parent"> <a class="level-top"
                                            href="{{ URL::to('/') }}"> <span>Home</span> </a>
                                        <ul class="level0">
                                            <li class="level1"><a
                                                    href="http://htmldemo.magikcommerce.com/ecommerce/inspire-html-template/fashion/index.html"><span>Fashion
                                                        Store</span></a> </li>
                                            <li class="level1"><a href="index.html"><span>Digital Store</span></a> </li>
                                            <li class="level1"><a
                                                    href="http://htmldemo.magikcommerce.com/ecommerce/inspire-html-template/furniture/index.html"><span>Furniture
                                                        Store</span></a> </li>
                                            <li class="level1"><a
                                                    href="http://htmldemo.magikcommerce.com/ecommerce/inspire-html-template/jewellery/index.html"><span>Jewellery
                                                        Store</span></a> </li>
                                        </ul>
                                    </li>
                                    <li class="level0 nav-6 level-top"> <a class="level-top" href="#">
                                            <span>Pages</span> </a>
                                        <ul class="level0">
                                            <li class="level1 first"><a href="grid.html"><span>Grid</span></a></li>
                                            <li class="level1 nav-10-2"> <a href="list.html"> <span>List</span> </a>
                                            </li>
                                            <li class="level1 nav-10-3"> <a href="product_detail.html"> <span>Product
                                                        Detail</span> </a> </li>
                                            <li class="level1 nav-10-4"> <a href="shopping_cart.html"> <span>Shopping
                                                        Cart</span> </a> </li>
                                            <li class="level1 first parent"><a
                                                    href="checkout.html"><span>Checkout</span></a>
                                                <ul class="level2">
                                                    <li class="level2 nav-2-1-1 first"><a
                                                            href="checkout_method.html"><span>Checkout Method</span></a>
                                                    </li>
                                                    <li class="level2 nav-2-1-5 last"><a
                                                            href="checkout_billing-info.html"><span>Checkout Billing
                                                                Info</span></a></li>
                                                </ul>
                                            </li>
                                            <li class="level1 nav-10-4"> <a href="wishlist.html"> <span>Wishlist</span>
                                                </a> </li>
                                            <li class="level1"> <a href="dashboard.html"> <span>Dashboard</span> </a>
                                            </li>
                                            <li class="level1"> <a href="multiple_addresses.html"> <span>Multiple
                                                        Addresses</span> </a> </li>
                                            <li class="level1"> <a href="about.html"> <span>About us</span> </a> </li>
                                            <li class="level1"><a href="compare.html"><span>Compare</span></a></li>
                                            <li class="level1"><a href="delivery.html"><span>Delivery</span></a> </li>
                                            <li class="level1"><a href="faq.html"><span>FAQ</span></a> </li>
                                            <li class="level1"><a href="quick_view.html"><span>Quick View</span></a>
                                            </li>
                                            <li class="level1"><a href="newsletter.html"><span>Newsletter</span></a>
                                            </li>
                                            <li class="level1"><a href="contact_us.html"><span>Contact us</span></a>
                                            </li>
                                            <li class="level1"><a href="sitemap.html"><span>Sitemap</span></a> </li>
                                            <li class="level1"><a href="404error.html"><span>404 Error Page</span></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="level0 nav-7 level-top parent"> <a class="level-top" href="grid.html">
                                            <span>Fashion</span> </a> </li>
                                    <li class="level0 nav-8 level-top parent"> <a class="level-top" href="grid.html">
                                            <span>Women</span> </a> </li>
                                    <li class="level0 parent drop-menu"><a href="blog.html"><span>Blog</span> </a>
                                        <ul class="level1">
                                            <li class="level1 first"><a href="blog_posts_table_view.html"><span>Table
                                                        View</span></a></li>
                                            <li class="level1 nav-10-2"> <a href="blog_single_post.html"> <span>Single
                                                        Post</span> </a> </li>
                                        </ul>
                                    </li>
                                    <li class="level0 nav-9 level-top last parent "> <a class="level-top"
                                            href="contact.html"> <span>Contact</span> </a> </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
                <!--navmenu-->
            </div>
            <!--End mobile-menu -->

            <a class="logo-small" title="Magento Commerce" href="{{ route('home') }}"><img alt="Magento Commerce"
                    src="{{ url('images/logo-small.png') }}"></a>
            <ul id="nav" class="hidden-xs">
                <li class="level0 parent drop-menu"><a href="{{ route('home') }}" class="active"><span>Home</span> </a>
                </li>
                <li class="level0 nav-5 level-top first"> <a class="level-top" href="">
                        <span>Electronics</span> </a>
                    <div style="display:none;" class="level0-wrapper dropdown-6col">
                        <div class="level0-wrapper2">
                            <div class="nav-block nav-block-center grid12-12 itemgrid itemgrid-4col">
                                <!--mega menu-->
                                <ul class="level3">
                                    @foreach ($categories as $category)
                                        <li class="level3 nav-6-1 parent item"> <a
                                                href="{{ route('filter', ['category_id' => $category->id]) }}"><span>{{ $category->name }}</span></a>
                                            <!--sub sub category-->
                                            <ul class="level1">
                                                @foreach ($collections as $collection)
                                                    @if ($collection->category_id == $category->id)
                                                        <li class="level2 nav-6-1-1">
                                                            <a
                                                                href="{{ route('filter', ['category_id' => $category->id, 'collection_id' => $collection->id]) }}">
                                                                <span>{{ $collection->name }}</span></a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                            <!--level1-->
                                            <!--sub sub category-->
                                            <div class="cat-img"><img
                                                    src="{{ url('uploads/categories-images/' . $category->id . '/' . $category->image) }}"
                                                    alt="Mobiles">
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                                <!--level0-->
                            </div>
                        </div>
                    </div>
                </li>

                <li class="level0 parent drop-menu"><a href="{{ route('coupon') }}"><span>Coupon</span> </a>
                    {{-- <ul style="display: none;" class="level1">
                        <li class="level1 first"><a href="blog_posts_table_view.html"><span>Table View</span></a> </li>
                        <li class="level1 parent"><a href="blog_single_post.html"><span>Single Post</span></a> </li>
                    </ul> --}}
                </li>
                <li class="level0 parent drop-menu"><a href="{{ route('compare.index') }}"><span>Comparison</span> </a>
                    {{-- <ul style="display: none;" class="level1">
                        <li class="level1 first"><a href="blog_posts_table_view.html"><span>Table View</span></a> </li>
                        <li class="level1 parent"><a href="blog_single_post.html"><span>Single Post</span></a> </li>
                    </ul> --}}
                </li>
                <li class="level0 parent drop-menu"><a href="{{ route('info.contact_us') }}"><span>Contact us</span> </a>
                    {{-- <ul style="display: none;" class="level1">
                        <li class="level1 first"><a href="blog_posts_table_view.html"><span>Table View</span></a> </li>
                        <li class="level1 parent"><a href="blog_single_post.html"><span>Single Post</span></a> </li>
                    </ul> --}}
                </li>
                <li class="level0 parent drop-menu"><a href="{{ route('info.about_us') }}"><span>About us</span> </a>
                    {{-- <ul style="display: none;" class="level1">
                        <li class="level1 first"><a href="blog_posts_table_view.html"><span>Table View</span></a> </li>
                        <li class="level1 parent"><a href="blog_single_post.html"><span>Single Post</span></a> </li>
                    </ul> --}}
                </li>
                {{-- <li class="nav-custom-link level0 level-top parent"> <a
                        class="level-top" href="#"><span>Custom</span></a>
                    <div class="level0-wrapper custom-menu" style="left: 0px; display: none;">
                        <div class="header-nav-dropdown-wrapper clearer">
                            <div class="grid12-5">
                                <div class="custom_img"><a href="#"><img
                                            src="{{ url('uploads/images/custom-img1.jpg') }}" alt="custom img1"></a>
                                </div>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam fringilla augue.</p>
                                <button class="learn_more_btn" title="Add to Cart" type="button"><span>Learn
                                        More</span></button>
                            </div>
                            <div class="grid12-5">
                                <div class="custom_img"><a href="#"><img
                                            src="{{ url('uploads/images/custom-img2.jpg') }}" alt="custom img2"></a>
                                </div>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam fringilla augue.</p>
                                <button class="learn_more_btn" title="Add to Cart" type="button"><span>Learn
                                        More</span></button>
                            </div>
                            <div class="grid12-5">
                                <div class="custom_img"><a href="#"><img
                                            src="{{ url('uploads/images/custom-img3.jpg') }}" alt="custom img3"></a>
                                </div>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam fringilla augue.</p>
                                <button class="learn_more_btn" title="Add to Cart" type="button"><span>Learn
                                        More</span></button>
                            </div>
                            <div class="grid12-5">
                                <div class="custom_img"><a href="#"><img
                                            src="{{ url('uploads/images/custom-img4.jpg') }}" alt="custom img4"></a>
                                </div>
                                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam fringilla augue.</p>
                                <button class="learn_more_btn" title="Add to Cart" type="button"><span>Learn
                                        More</span></button>
                            </div>
                        </div>
                    </div>
                </li> --}}
            </ul>
        </div>
    </div>
</nav>
<!-- end nav -->
