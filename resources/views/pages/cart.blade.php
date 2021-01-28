@extends('layout')
@section('title', 'Shopping cart - Electronical Store')
@section('content')
    <section class="main-container col1-layout">
        <div class="main container">
            <div class="col-main">
                <div class="cart wow">
                    <div class="page-title">
                        <h2>Shopping Cart</h2>
                    </div>
                    <div class="table-responsive">
                        <fieldset>
                            @if ($shopping_carts != null)
                                <form method="post" enctype="multipart/form-data" action=""
                                    id="update_cart_form">
                                    @csrf
                                    <table class="data-table cart-table" id="shopping-cart-table">
                                        <thead>
                                            <tr class="first last">
                                                <th>&nbsp;</th>
                                                <th><span class="nobr">Product Name</span></th>
                                                <th><span class="nobr">Unit Price</span></th>
                                                <th>Qty</th>
                                                <th>Subtotal</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr class="first last">
                                                <td class="a-right last" colspan="50">
                                                    <a href="{{ URL::to('/') }}" class="button btn-default btn-continue"
                                                        title="Continue Shopping" type="button"><span><span>Continue
                                                                Shopping</span></span>
                                                    </a>
                                                    <button class="button btn-update" name="update_cart_action"
                                                        type="submit"><span><span>Update
                                                                Cart</span></span>
                                                    </button>
                                                    <button onclick="remove_cart();" class="button btn-empty"
                                                        title="Clear Cart"><span><span>Clear Cart</span></span>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                            @foreach ($shopping_carts as $product_id => $info)
                                                <tr class="first odd">
                                                    <td class="image">
                                                        <a class="product-image" title="{{ $info['product_name'] }}"
                                                            href="{{ URL::to('product-details/' . $product_id) }}">
                                                            <img width="75" alt="{{ $info['product_name'] }}"
                                                                src="{{ url('uploads/products-images/' . $product_id . '/' . $info['product_image']) }}"></a>
                                                    </td>
                                                    <td>
                                                        <h2 class="product-name"> <a
                                                                href="{{ URL::to('product-details/' . $product_id) }}">{{ $info['product_name'] }}</a>
                                                        </h2>
                                                    </td>
                                                    <td>
                                                        @if ($info['product_discount'] != null)
                                                            <span class="cart-price">
                                                                <p class="special-price"> <span class="price">
                                                                        {{ $info['product_price'] - ($info['product_price'] * $info['product_discount']) / 100 }}
                                                                    </span>
                                                                </p>
                                                                <p class="old-price"> <span class="price-sep">-</span> <span
                                                                        class="price"> {{ $info['product_price'] }}
                                                                    </span> </p>
                                                            </span>
                                                        @else
                                                            <span class="cart-price">
                                                                <p class="price"> <span class="price">
                                                                        {{ $info['product_price'] }} </span>
                                                                </p>
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <input type="number" min="0" class="form-control"
                                                            value="{{ $info['product_quantity'] }}"
                                                            name="quantity_{{ $product_id }}">
                                                    </td>
                                                    <td>
                                                        <span class="cart-price">
                                                            @if ($info['product_discount'] != null)
                                                                <span
                                                                    class="price">{{ ($info['product_price'] - ($info['product_price'] * $info['product_discount']) / 100) * $info['product_quantity'] }}
                                                                </span>
                                                            @else
                                                                <span
                                                                    class="price">{{ $info['product_price'] * $info['product_quantity'] }}
                                                                </span>
                                                            @endif
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <a onclick="remove_item_from_cart({{ $product_id }})"
                                                            class="button remove-item" title="Remove item">
                                                            <span><span>Remove item</span></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </form>
                            @else
                                <a href="{{ URL::to('/') }}" class="btn btn-default button btn-continue"
                                    title="Continue Shopping"><span><span>Continue shopping</span></span></a>
                            @endif
                        </fieldset>
                    </div>
                    <!-- BEGIN CART COLLATERALS -->
                    <div class="cart-collaterals row">
                        <div class="col-sm-4">
                            <div class="discount">
                                <h3>Discount Codes</h3>
                                <form method="post" action="" id="apply_coupon_form">
                                    @csrf
                                    <label for="coupon_code">Enter your coupon code if you have one.</label>
                                    <input type="text" value="" name="code" id="coupon_code" class="input-text fullwidth">
                                    <button value="Apply Coupon" class="button coupon " title="Apply Coupon"
                                        type="submit"><span>Apply Coupon</span></button>
                                    <button onclick="return remove_coupon();" title="Clear"
                                        class="button button-clear"><span>Clear</span></button>
                                </form>
                            </div>
                        </div>
                        <div class="totals col-sm-4">
                            <h3>Shopping Cart Total</h3>
                            <div class="inner">
                                <table class="table shopping-cart-table-total" id="shopping-cart-totals-table">
                                    <colgroup>
                                        <col>
                                        <col width="1">
                                    </colgroup>
                                    <tfoot>
                                        <tr>
                                            <td colspan="1" class="a-left" style=""><strong>Grand Total</strong></td>
                                            <td class="a-right" style=""><strong><span
                                                        class="price">{{ $total_cart - $discount_cart['coupon_discount'] }}
                                                        </span></strong>
                                            </td>
                                        </tr>
                                    </tfoot>
                                    <tbody>
                                        <tr>
                                            <td colspan="1" class="a-left" style=""> Subtotal </td>
                                            <td class="a-right" style=""><span class="price">{{ $total_cart }} </span>
                                            </td>
                                        </tr>
                                        @if (isset($discount_cart))
                                            <tr>
                                                <td colspan="1" class="a-left" style=""> Discount </td>
                                                <td class="a-right" style=""><span class="">-
                                                        {{ $discount_cart['coupon_discount'] }}</span>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <ul class="checkout">
                                    <li>
                                        <button id="btn_check_out" class="button btn-proceed-checkout"
                                            title="Proceed to Checkout" type="button"><span>Proceed to
                                                Checkout</span></button>
                                    </li>
                                    <br>
                                    {{-- <li><a title="Checkout with Multiple Addresses"
                                            href="multiple_addresses.html">Checkout
                                            with Multiple Addresses</a> </li>
                                    <br> --}}
                                </ul>
                            </div>
                            <!--inner-->

                        </div>
                    </div>
                    <!--cart-collaterals-->
                </div>
                <!-- recommend slider -->
                {{-- <section class="recommend container">
                    <div class="new-pro-slider small-pr-slider" style="overflow:visible">
                        <div class="slider-items-products">
                            <div class="new_title center">
                                <h2>Based on your selection, you may be interested in the following items:</h2>
                            </div>
                            <div id="recommend-slider" class="product-flexslider hidden-buttons">
                                <div class="slider-items slider-width-col3">
                                    @foreach ($similar_products as $product)
                                        <!-- Item -->
                                        <div class="item">
                                            <div class="col-item">
                                                @if((abs(strtotime(date('Y-m-d')) -
                                                strtotime($product->create_date))/60/60/24) < 10) <div
                                                    class="new-label new-top-left">
                                                    New</div>

                                    @endif
                                    @if ($product->discount != null)
                                        <div class="sale-label sale-top-right">Sale {{ $product->discount }}%</div>
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
                                                            onclick="alert('You need to login to add this product to the
                                                            wish list!');"
                                                            @endif
                                                            title="Add to Wishlist" class="link-wishlist"><span>Add to
                                                                Wishlist</span></a>
                                                    </li>
                                                    <li><a href="compare.html" title="Add to Compare"
                                                            class="link-compare "><span>Add to
                                                                Compare</span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="qv-button-container"> <a href="quick_view.html"
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
                                                        <div style="width:{{ $product->rating }}%" class="rating"></div>
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
                                                                class="price">
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
                                            <button type="button" onclick="add_to_cart({{ $product->id }});"
                                                title="Add to Cart" class="button btn-cart"><span>Add to
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
            </div>
        </div>
    </section> --}}
    <!-- End Recommend slider -->
    </div>
    </div>
    </section>
    <script>
        function remove_cart() {
            if (confirm("Are you sure want to remove entire the shopping cart?")) {
                $.ajax({
                    url: "{{ route('cart.delete') }}",
                    type: 'GET',
                    data: {},
                    success: function(response) {
                        console.log(response)
                        alert(response.message);
                        if (response.error == false) {
                            window.location.reload();
                        }
                    },
                    error: function(e) {
                        console.log(e);
                    }

                });
                return false;
            }
        }
        function remove_coupon() {
            if (confirm("Are you sure want to remove the coupon?")) {
                $.ajax({
                    url: "{{ route('cart.remove_coupon') }}",
                    type: 'GET',
                    data: {},
                    success: function(response) {
                        console.log(response)
                        alert(response.message);
                        if (response.error == false) {
                            window.location.reload();
                        }
                    },
                    error: function(e) {
                        console.log(e);
                    }

                });
                return false;
            }
            // window.location.href="{{ route('cart.remove_coupon') }}";
        }

        $(document).ready(function() {
            $('#btn_check_out').on('click', function() {
                $.ajax({
                    url: "{{ url('/cart/check') }}",
                    type: 'GET',
                    data: {},
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'JSON',
                    beforeSend: function() {},
                    success: function(response) {
                        console.log(response);
                        alert(response.message);
                        if (response.error == false) {
                            window.location.href = "{{ url('/check-out') }}";
                        }
                    },
                    error: function(e) {
                        console.log(e);
                    }

                });
            });
            $('#update_cart_form').on('submit', (function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ url('/cart/update') }}",
                    type: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'JSON',
                    beforeSend: function() {},
                    success: function(response) {
                        console.log(response);
                        alert(response.message);
                        if (response.error == false) {
                            window.location.reload();
                        }
                    },
                    error: function(e) {
                        console.log(e);
                    }

                });
            }));
            $('#apply_coupon_form').on('submit', (function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ url('/cart/apply-coupon') }}",
                    type: 'POST',
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'JSON',
                    beforeSend: function() {},
                    success: function(response) {
                        console.log(response);
                        alert(response.message);
                        if (response.error == false) {
                            window.location.reload();
                        }
                    },
                    error: function(e) {
                        console.log(e);
                    }

                });
            }));
        });

    </script>
@endsection
