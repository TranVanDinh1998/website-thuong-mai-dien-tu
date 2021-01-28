@extends('layout')
@section('title', 'Check out - Electronical Store')
@section('content')
    <!-- main-container -->
    <script>
        $(document).ready(function() {
            // check method
            $('#btn_checkout_method_continue').click(function() {
                var choice = $('input:radio[name="checkout_method"]:checked').val();
                if (choice == "register") {
                    window.location.href = "{{ url('/register') }}";
                } else {
                    if (choice == "guest") {
                        $("#shipping-old-address").hide();
                        $("#shipping-new-address").show();
                    }
                }
            });

            //login
            $('#login_form').on('submit', (function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ url('/login') }}",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'JSON',
                    beforeSend: function() {},
                    success: function(response) {
                        console.log(response);
                        if (response.error == true) {
                            if (response.message.email != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<p class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.email[0] +
                                        "</p>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.password != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<p class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.password[0] + "</p>");
                                    $("#errorMessage").fadeOut(10000);

                                });
                            }
                            if (response.message.errorLogin != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<p class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.errorLogin[0] +
                                        "</p>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                        } else {
                            alert("Welcome to the shop.");
                            window.location.reload();
                        }
                    },
                })
            }));

            // Shipping check out
            // select new shipping address -> show new shipping address form
            $("#shipping-address-select").change(function() {
                var val = $("#shipping-address-select option:selected").val();
                if (val == 0) {
                    $("#shipping-new-address").show();
                } else {
                    $("#shipping-new-address").hide();
                }
            });
            $('#shipping_add_address_form').on('submit', (function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ url('/check-out/shipping-checkout') }}",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'JSON',
                    beforeSend: function() {},
                    success: function(response) {
                        console.log(response);
                        if (response.error == true) {
                            if (response.message.name != undefined) {
                                $("#errorAddressMessage").fadeIn(1000, function() {
                                    $("#errorAddressMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.name[0] +
                                        "</div>"
                                    );
                                    $("#errorAddressMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.address != undefined) {
                                $("#errorAddressMessage").fadeIn(1000, function() {
                                    $("#errorAddressMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.address[0] +
                                        "</div>"
                                    );
                                    $("#errorAddressMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.number != undefined) {
                                $("#errorAddressMessage").fadeIn(1000, function() {
                                    $("#errorAddressMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.number[0] + "</div>");
                                    $("#errorAddressMessage").fadeOut(10000);

                                });
                            }
                            if (response.message.ward_id != undefined) {
                                $("#errorAddressMessage").fadeIn(1000, function() {
                                    $("#errorAddressMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.ward_id[0] +
                                        "</div>");
                                    $("#errorAddressMessage").fadeOut(10000);

                                });
                            }
                            if (response.message.district_id != undefined) {
                                $("#errorAddressMessage").fadeIn(1000, function() {
                                    $("#errorAddressMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.district_id[0] +
                                        "</div>"
                                    );
                                    $("#errorAddressMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.province_id != undefined) {
                                $("#errorAddressMessage").fadeIn(1000, function() {
                                    $("#errorAddressMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.province_id[0] +
                                        "</div>"
                                    );
                                    $("#errorAddressMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.errorUser != undefined) {
                                $("#errorAddressMessage").fadeIn(1000, function() {
                                    $("#errorAddressMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.errorUser[0] +
                                        "</div>"
                                    );
                                    $("#errorAddressMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.errorAddress != undefined) {
                                $("#errorAddressMessage").fadeIn(1000, function() {
                                    $("#errorAddressMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.errorAddress[0] +
                                        "</div>"
                                    );
                                    $("#errorAddressMessage").fadeOut(10000);
                                });
                            }
                        } else {
                            $("#errorAddressMessage").fadeIn(1000, function() {
                                $("#errorAddressMessage").html(
                                    "<div class='alert alert-success' style='width:100%; margin:auto;'>" +
                                    response.message +
                                    "</div>"
                                );
                                $("#errorAddressMessage").fadeOut(10000);
                            });
                            $.ajax({
                                url: "{{ url('/check-out/get-shipping-address') }}",
                                type: 'POST',
                                data: {},
                                contentType: false,
                                cache: false,
                                processData: false,
                                dataType: 'JSON',
                                beforeSend: function() {},
                                success: function(response) {
                                    if (response.error == false) {
                                        $("#shipping_check_out_result").html(
                                            response.message);
                                    }
                                }
                            });
                        }
                    },
                })
            }));

            // payment
            $('input:radio[name="payment_method"]').click(function() {
                var choice = $('input:radio[name="payment_method"]:checked').val();
                if (choice == "0") {
                    $("#payment_form_ccsave").hide();
                } else {
                    if (choice == "1") {
                        $("#payment_form_ccsave").show();
                    }
                }
            });
            $('#payment_credit_add_form').on('submit', (function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('checkout.payment_checkout') }}",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'JSON',
                    beforeSend: function() {},
                    success: function(response) {
                        console.log(response);
                        if (response.error == true) {
                            if (response.message.name != undefined) {
                                $("#errorPaymentMessage").fadeIn(1000, function() {
                                    $("#errorPaymentMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.name[0] +
                                        "</div>"
                                    );
                                    $("#errorPaymentMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.type != undefined) {
                                $("#errorPaymentMessage").fadeIn(1000, function() {
                                    $("#errorPaymentMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.type[0] +
                                        "</div>"
                                    );
                                    $("#errorPaymentMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.card_number != undefined) {
                                $("#errorPaymentMessage").fadeIn(1000, function() {
                                    $("#errorPaymentMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.card_number[0] +
                                        "</div>");
                                    $("#errorPaymentMessage").fadeOut(10000);

                                });
                            }
                            if (response.message.expire_month != undefined) {
                                $("#errorPaymentMessage").fadeIn(1000, function() {
                                    $("#errorPaymentMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.expire_month[0] +
                                        "</div>");
                                    $("#errorPaymentMessage").fadeOut(10000);

                                });
                            }
                            if (response.message.expire_year != undefined) {
                                $("#errorPaymentMessage").fadeIn(1000, function() {
                                    $("#errorPaymentMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.expire_year[0] +
                                        "</div>"
                                    );
                                    $("#errorPaymentMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.verification_number != undefined) {
                                $("#errorPaymentMessage").fadeIn(1000, function() {
                                    $("#errorPaymentMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.verification_number[
                                            0] +
                                        "</div>"
                                    );
                                    $("#errorPaymentMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.errorPayment != undefined) {
                                $("#errorPaymentMessage").fadeIn(1000, function() {
                                    $("#errorPaymentMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.errorPayment[0] +
                                        "</div>"
                                    );
                                    $("#errorPaymentMessage").fadeOut(10000);
                                });
                            }
                        } else {
                            $("#errorPaymentMessage").fadeIn(1000, function() {
                                $("#errorPaymentMessage").html(
                                    "<div class='alert alert-success' style='width:100%; margin:auto;'>" +
                                    response.message +
                                    "</div>"
                                );
                                $("#errorPaymentMessage").fadeOut(10000);
                            });
                            $.ajax({
                                url: "{{ route('checkout.get_payment') }}",
                                type: 'POST',
                                data: {},
                                contentType: false,
                                cache: false,
                                processData: false,
                                dataType: 'JSON',
                                beforeSend: function() {},
                                success: function(response) {
                                    if (response.error == false) {
                                        $("#payment_check_out_result").html(
                                            response.message);
                                    }
                                }
                            });
                        }
                    },
                })
            }));

            $('#btn_final_check').click(function() {
                window.location.href = "{{route('checkout.final_check')}}";

                // $.ajax({
                //     url: "{{ route('checkout.final_check') }}",
                //     type: "GET",
                //     data: {},
                //     contentType: false,
                //     cache: false,
                //     processData: false,
                //     dataType: 'JSON',
                //     beforeSend: function() {},
                //     success: function(response) {
                //         console.log(response);
                //         alert(response.message);
                //         if (response.error == false) {
                //             window.location.href = "{{URL::to('/delivery/26')}}";
                //         }
                //     },
                // });

            });

        });

    </script>
    <div class="main-container col2-right-layout">
        <div class="main container">
            <div class="row">
                <section class="col-main col-sm-9 wow">
                    <div class="page-title">
                        <h1>Checkout</h1>
                    </div>
                    <ol class="one-page-checkout" id="checkoutSteps">
                        <li id="opc-cart" class="section allow active">
                            <div class="step-title"> <span class="number">1</span>
                                <h3>Shopping cart</h3>
                                <!--<a href="#">Edit</a> -->
                            </div>
                            <div id="checkout-step-cart" class="step a-item" style="">
                                @if ($shopping_carts != null)
                                    <div class="table-responsive">
                                        <fieldset>
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
                                                <tbody>
                                                    @foreach ($shopping_carts as $product_id => $info)
                                                        <tr class="first odd">
                                                            <td class="image">
                                                                <a class="product-image" title="Sample Product"
                                                                    href="{{ URL::to('product-details/' . $product_id) }}"><img
                                                                        width="75" alt="Sample Product"
                                                                        src="{{ url('uploads/products-images/' . $product_id . '/' . $info['product_image']) }}"></a>
                                                            </td>
                                                            <td>
                                                                <p class="product-name"> <a
                                                                        href="{{ URL::to('product-details/' . $product_id) }}">{{ $info['product_name'] }}</a>
                                                                </p>
                                                            </td>
                                                            <td>
                                                                @if ($info['product_discount'] != null)
                                                                    <span class="cart-price">
                                                                        <p class="special-price"> <span class="price">
                                                                                {{ $info['product_price'] - ($info['product_price'] * $info['product_discount']) / 100 }}
                                                                            </span>
                                                                        </p>
                                                                        <p class="old-price"> <span
                                                                                class="price-sep">-</span> <span
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
                                                                <span>{{ $info['product_quantity'] }}</span>
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
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </fieldset>
                                    </div>
                                    <div class="totals " style="float: right;">
                                        <div class="inner">
                                            <table class="table shopping-cart-table-total" id="shopping-cart-totals-table">
                                                <colgroup>
                                                    <col>
                                                    <col width="1">
                                                </colgroup>
                                                <tfoot>
                                                    <tr>
                                                        <td colspan="1" class="a-left" style=""><strong>Grand Total</strong>
                                                        </td>
                                                        <td class="a-right" style=""><strong><span
                                                                    class="price">{{ $total_cart - $discount_cart['coupon_discount'] }}
                                                                    vnd </span></strong>
                                                        </td>
                                                    </tr>
                                                </tfoot>
                                                <tbody>
                                                    <tr>
                                                        <td colspan="1" class="a-left" style=""> Subtotal </td>
                                                        <td class="a-right" style=""><span class="price">{{ $total_cart }}
                                                                vnd</span>
                                                        </td>
                                                    </tr>
                                                    @if (isset($discount_cart))
                                                        <tr>
                                                            <td colspan="1" class="a-left" style=""> Discount </td>
                                                            <td class="a-right" style=""><span class="">-
                                                                    {{ $discount_cart['coupon_discount'] }} vnd</span>
                                                            </td>
                                                        </tr>
                                                    @endif
                                                </tbody>
                                            </table>
                                        </div>
                                        <!--inner-->
                                    </div>
                                @else
                                    <a href="{{ URL::to('/') }}" class="btn btn-default button btn-continue"
                                        title="Continue Shopping"><span><span>Continue shopping</span></span></a>
                                @endif
                            </div>
                        </li>
                        <li id="opc-shipping" class="section allow active">
                            <div class="step-title"> <span class="number">2</span>
                                <h3>Checkout Method</h3>
                                <!--<a href="#">Edit</a> -->
                            </div>
                            @if (!Auth::user())
                                <div id="checkout-step-login" class="step a-item">
                                    <div class="col2-set">
                                        <div class="col-1">
                                            <h4>Checkout as a Guest or Register</h4>
                                            <p>Register with us for future convenience:</p>
                                            <ul class="form-list">
                                                <li class="control">
                                                    <input type="radio" class="form-control-sm radio" value="guest"
                                                        name="checkout_method">
                                                    <label for="login:guest">Checkout as Guest</label>
                                                </li>
                                                <li class="control">
                                                    <input type="radio" class="form-control-sm radio" value="register"
                                                        name="checkout_method">
                                                    <label for="login:register">Register</label>
                                                </li>
                                            </ul>
                                            <h4>Register and save time!</h4>
                                            <p>Register with us for future convenience:</p>
                                            <ul class="ul">
                                                <li>Fast and easy check out</li>
                                                <li>Easy access to your order history and status</li>
                                            </ul>
                                            <div class="buttons-set">
                                                <p class="required">&nbsp;</p>
                                                <button id="btn_checkout_method_continue"
                                                    class="button continue"><span><span>Continue</span></span></button>
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <h4>Login</h4>
                                            <form method="post" action="{{ URL::to('/login') }}" id="login_form"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <fieldset>
                                                    <h5>Already registered?</h5>
                                                    <p>Please log in below:</p>
                                                    <ul class="form-list">
                                                        <li>
                                                            <p id="errorMessage"></p>
                                                        </li>
                                                        <li>
                                                            <label class="required" for="login-email"><em>*</em>Email
                                                                Address</label>
                                                            <div class="input-box">
                                                                <input type="email" value="" name="email"
                                                                    class="input-text required-entry validate-email">
                                                            </div>
                                                        </li>
                                                        <li>
                                                            <label class="required"
                                                                for="login-password"><em>*</em>Password</label>
                                                            <div class="input-box">
                                                                <input type="password" name="password"
                                                                    class="input-text required-entry">
                                                            </div>
                                                        </li>
                                                    </ul>
                                                    <input type="hidden" value="checkout" name="context">
                                                </fieldset>
                                                <div class="buttons-set">
                                                    <p class="required">* Required Fields</p>
                                                    <button class="button login"
                                                        type="submit"><span><span>Login</span></span></button>
                                                    {{-- <a class="f-right"
                                                        href="http://www.magentothemestudio.com/themes/istudio7/index.php/customer/account/forgotpassword/">Forgot
                                                        your password?</a> --}}
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="col-2" id="shipping-new-address" style="display: none;">
                                        <div class="step-title">
                                            <h4>New address</h4>
                                            <!--<a href="#">Edit</a> -->
                                        </div>
                                        <form id="shipping_add_address_form" enctype="multipart/form-data" method="POST"
                                            action="#">
                                            @csrf
                                            <fieldset class="group-select">
                                                <ul>
                                                    <li>
                                                        <ul>
                                                            <li>
                                                                <div class="customer-name">
                                                                    <div class="input-box name-firstname">
                                                                        <label for=""> Name <span class="required">*</span>
                                                                        </label>
                                                                        <br>
                                                                        <input type="text" name="name" value=""
                                                                            class="input-text required-entry">
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div id="" class="input-box">
                                                                    <label for="shipping:region">Province <span
                                                                            class="required">*</span></label>
                                                                    <br>
                                                                    <select name="province_id">
                                                                        <option value="">
                                                                            Select province / city
                                                                        </option>
                                                                        @foreach ($provinces as $province)
                                                                            <option value="{{ $province->id }}">
                                                                                {{ $province->name }}

                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div id="" class="input-box">
                                                                    <label for="shipping:region">District<span
                                                                            class="required">*</span></label>
                                                                    <br>
                                                                    <select name="district_id">
                                                                        <option value="">
                                                                            Select disctrict
                                                                        </option>
                                                                        @foreach ($districts as $district)
                                                                            <option value="{{ $district->id }}">
                                                                                {{ $district->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div id="" class="input-box">
                                                                    <label for="shipping:region">Ward<span
                                                                            class="required">*</span></label>
                                                                    <br>
                                                                    <select name="ward_id">
                                                                        <option value="">
                                                                            Select ward
                                                                        </option>
                                                                        @foreach ($wards as $ward)
                                                                            <option value="{{ $ward->id }}">
                                                                                {{ $ward->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label for="shipping:street1">Address <span
                                                                        class="required">*</span></label>
                                                                <br>
                                                                <input type="text" title="Street Address" name="address"
                                                                    value="" class="input-text required-entry">
                                                            </li>
                                                            <li>
                                                                <div class="input-box">
                                                                    <label for="shipping:telephone">Telephone <span
                                                                            class="required">*</span></label>
                                                                    <br>
                                                                    <input type="text" name="number"
                                                                        class="input-text required-entry">
                                                                </div>
                                                            </li>
                                                        </ul>
                                                        <p id="errorAddressMessage"></p>
                                                        <p class="require"><em class="required">* </em>Required Fields</p>
                                                        <button id="btn_shipping_checkout" type="submit"
                                                            class="button continue"><span>Confirm</span></button>
                                                    </li>
                                                </ul>

                                            </fieldset>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <div id="checkout-step-shipping" class="step a-item">
                                    <form id="shipping_add_address_form" enctype="multipart/form-data" method="POST"
                                        action="#">
                                        @csrf
                                        <fieldset class="group-select">
                                            <ul>
                                                <li id="shipping-old-address">
                                                    <label for="shipping-address-select">
                                                        Select a shipping address from your address book or enter a new
                                                        address.
                                                    </label>
                                                    <br>
                                                    <select name="shipping_address_id" id="shipping-address-select"
                                                        class="address-select" title="">
                                                        <option value="" value="">Select address for shipping</option>
                                                        @if ($addresses != null)
                                                            @foreach ($addresses as $address)
                                                                <option value="{{ $address->id }}" selected>
                                                                    {{ $address->address }} -
                                                                    @foreach ($wards as $ward)
                                                                        @if ($ward->id == $address->ward_id)
                                                                            {{ $ward->name }} -
                                                                        @endif
                                                                    @endforeach
                                                                    @foreach ($districts as $district)
                                                                        @if ($address->district_id == $district->id)
                                                                            {{ $district->id }} -
                                                                        @endif
                                                                    @endforeach
                                                                    @foreach ($provinces as $province)
                                                                        @if ($province->id == $address->province_id)
                                                                            {{ $province->name }} -
                                                                        @endif
                                                                    @endforeach
                                                                </option>
                                                            @endforeach
                                                        @endif
                                                        <option value="0" value="">New Address</option>
                                                    </select>
                                                </li>
                                                <li id="shipping-new-address" style="display: none;">
                                                    <fieldset>
                                                        <legend>New Address</legend>
                                                        <ul>
                                                            <li>
                                                                <div class="customer-name">
                                                                    <div class="input-box name-firstname">
                                                                        <label for=""> Name <span class="required">*</span>
                                                                        </label>
                                                                        <br>
                                                                        <input type="text" name="name" value=""
                                                                            class="input-text required-entry">
                                                                    </div>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div id="" class="input-box">
                                                                    <label for="shipping:region">Province <span
                                                                            class="required">*</span></label>
                                                                    <br>
                                                                    <select name="province_id">
                                                                        <option value="">
                                                                            Select province / city
                                                                        </option>
                                                                        @foreach ($provinces as $province)
                                                                            <option value="{{ $province->id }}">
                                                                                {{ $province->name }}

                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div id="" class="input-box">
                                                                    <label for="shipping:region">District<span
                                                                            class="required">*</span></label>
                                                                    <br>
                                                                    <select name="district_id">
                                                                        <option value="">
                                                                            Select disctrict
                                                                        </option>
                                                                        @foreach ($districts as $district)
                                                                            <option value="{{ $district->id }}">
                                                                                {{ $district->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <div id="" class="input-box">
                                                                    <label for="shipping:region">Ward<span
                                                                            class="required">*</span></label>
                                                                    <br>
                                                                    <select name="ward_id">
                                                                        <option value="">
                                                                            Select ward
                                                                        </option>
                                                                        @foreach ($wards as $ward)
                                                                            <option value="{{ $ward->id }}">
                                                                                {{ $ward->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <label for="shipping:street1">Address <span
                                                                        class="required">*</span></label>
                                                                <br>
                                                                <input type="text" title="Street Address" name="address"
                                                                    value="" class="input-text required-entry">
                                                            </li>
                                                            <li>
                                                                <div class="input-box">
                                                                    <label for="shipping:telephone">Telephone <span
                                                                            class="required">*</span></label>
                                                                    <br>
                                                                    <input type="text" name="number"
                                                                        class="input-text required-entry">
                                                                </div>
                                                            </li>
                                                            <li>
                                                                <input type="checkbox" name="save_address" value="1"
                                                                    title="Save in address book"
                                                                    class="checkbox">
                                                                <label for="shipping:save_in_address_book">Save in address
                                                                    book</label>
                                                            </li>
                                                        </ul>
                                                    </fieldset>
                                                </li>
                                                {{-- <li>
                                                    <input type="radio" name="shipping" value="1" class="radio">
                                                    <label for="shipping:use_for_shipping_yes">Ship to this address</label>
                                                    <input type="radio" name="shipping" value="0" checked="checked"
                                                        class="radio">
                                                    <label for="shipping:use_for_shipping_no">Ship to different
                                                        address</label>
                                                </li> --}}
                                            </ul>
                                            <p id="errorAddressMessage"></p>
                                            <p class="require"><em class="required">* </em>Required Fields</p>
                                            <button id="btn_shipping_checkout" type="submit"
                                                class="button continue"><span>Confirm</span></button>
                                        </fieldset>
                                    </form>
                                </div>
                            @endif
                        </li>
                        <li id="opc-payment" class="section allow active">
                            <div class="step-title"> <span class="number">3</span>
                                <h3 class="one_page_heading">Payment Information</h3>
                            </div>
                            <div id="checkout-step-payment" class="step a-item">
                                <form action="{{ route('checkout.payment_checkout') }}" id="payment_credit_add_form"
                                    method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <dl id="checkout-payment-method-load">
                                        <dt>
                                            <input type="radio" value="0" @if ($check_out)
                                            @if (isset($check_out['payment']) && $check_out['payment'] != null)
                                                @if ($check_out['payment']['credit'] == 0)
                                                    checked
                                                @endif
                                            @endif
                                            @endif
                                            name="payment_method" title="Check / Money order"
                                            class="radio">
                                            <label for="p_method_checkmo">Check / Money order</label>
                                        </dt>
                                        <dd>
                                            <fieldset class="form-list">
                                            </fieldset>
                                        </dd>
                                        <dt>
                                            <input type="radio" value="1" @if ($check_out)
                                            @if (isset($check_out['payment']) && $check_out['payment'] != null)
                                                @if ($check_out['payment']['credit'] == 1)
                                                    checked
                                                @endif
                                            @endif
                                            @endif
                                            name="payment_method" title="Credit Card (saved)"
                                            class="radio">
                                            <label for="p_method_ccsave">Credit Card (saved)</label>
                                        </dt>
                                        <dd>
                                            <fieldset class="form-list">
                                                <ul id="payment_form_ccsave" style="display: none;">
                                                    <li>
                                                        <div class="input-box">
                                                            <label for="ccsave_cc_owner">Name on Card <span
                                                                    class="required">*</span></label>
                                                            <br>
                                                            <input type="text" title="Name on Card"
                                                                class="input-text required-entry" name="name" value="">
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="input-box">
                                                            <label for="ccsave_cc_type">Credit Card Type <span
                                                                    class="required">*</span></label>
                                                            <br>
                                                            <select name="type"
                                                                class="required-entry validate-cc-type-select">
                                                                <option value="">--Please Select--</option>
                                                                <option value="1">American Express</option>
                                                                <option value="2">Visa</option>
                                                                <option value="3">MasterCard</option>
                                                                <option value="4">Discover</option>
                                                            </select>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="input-box">
                                                            <label for="ccsave_cc_number">Credit Card Number <span
                                                                    class="required">*</span></label>
                                                            <br>
                                                            <input type="number" name="card_number"
                                                                title="Credit Card Number"
                                                                class="input-text validate-cc-number validate-cc-type"
                                                                value="">
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="input-box">
                                                            <label for="ccsave_expiration">Expiration Date <span
                                                                    class="required">*</span></label>
                                                            <br>
                                                            <div class="v-fix">
                                                                <select id="ccsave_expiration" style="width: 140px;"
                                                                    name="expire_month" class="required-entry">
                                                                    <option value="" selected="selected">Month</option>
                                                                    <option value="1">01 - January</option>
                                                                    <option value="2">02 - February</option>
                                                                    <option value="3">03 - March</option>
                                                                    <option value="4">04 - April</option>
                                                                    <option value="5">05 - May</option>
                                                                    <option value="6">06 - June</option>
                                                                    <option value="7">07 - July</option>
                                                                    <option value="8">08 - August</option>
                                                                    <option value="9">09 - September</option>
                                                                    <option value="10">10 - October</option>
                                                                    <option value="11">11 - November</option>
                                                                    <option value="12">12 - December</option>
                                                                </select>
                                                            </div>
                                                            <div class="v-fix">
                                                                <select id="ccsave_expiration_yr" style="width: 103px;"
                                                                    name="expire_year" class="required-entry">
                                                                    <option value="" selected="selected">Year</option>
                                                                    <option value="2011">2020</option>
                                                                    <option value="2012">2021</option>
                                                                    <option value="2013">2022</option>
                                                                    <option value="2014">2023</option>
                                                                    <option value="2015">2024</option>
                                                                    <option value="2016">2025</option>
                                                                    <option value="2017">2026</option>
                                                                    <option value="2018">2027</option>
                                                                    <option value="2019">2028</option>
                                                                    <option value="2020">2029</option>
                                                                    <option value="2021">2030</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                    </li>
                                                    <li>
                                                        <div class="input-box">
                                                            <label for="ccsave_cc_cid">Card Verification Number <span
                                                                    class="required">*</span></label>
                                                            <br>
                                                            <div class="v-fix">
                                                                <input type="number" title="Card Verification Number"
                                                                    class="input-text required-entry validate-cc-cvn"
                                                                    name="verification_number">
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </fieldset>
                                        </dd>
                                    </dl>
                                    <p id="errorPaymentMessage"></p>
                                    <p class="require"><em class="required">* </em>Required Fields</p>
                                    <div class="buttons-set1" id="payment-buttons-container">
                                        <button type="submit" class="button"
                                            id="btn_payment_checkout"><span>Confirm</span></button>
                                    </div>
                                </form>
                                <div style="clear: both;"></div>
                            </div>
                        </li>
                    </ol>
                </section>
                <aside class="col-right sidebar col-sm-3 wow">
                    <div class="block block-progress">
                        <div class="block-title ">Your Checkout</div>
                        <div class="block-content">
                            <dl>
                                <dt class="complete">
                                    Shopping cart <span class="separator">|</span>
                                    {{-- <a
                                        onClick="checkout.gotoSection('shipping');return false;" href="#payment">Change</a>
                                    --}}
                                </dt>
                                <dd class="complete" id="cart_check_out_result">
                                    @if ($shopping_carts != null)
                                        @foreach ($shopping_carts as $product_id => $info)
                                            {{ $info['product_name'] . ' x ' . $info['product_quantity'] }} <br>

                                        @endforeach
                                        Total: {{ $total_cart }}<br>
                                    @endif
                                </dd>
                                <dt class="complete">
                                    Shipping Address <span class="separator">|</span>
                                    {{-- <a
                                        onClick="checkout.gotoSection('shipping');return false;" href="#payment">Change</a>
                                    --}}
                                </dt>
                                <dd class="complete" id="shipping_check_out_result">
                                    @if ($check_out)
                                        @if (isset($check_out['shipping_address']) && $check_out['shipping_address'] != null)
                                            <address>
                                                {{ $check_out['shipping_address']['name'] }}<br>
                                                {{ $check_out['shipping_address']['address'] }}<br>
                                                {{ $check_out['shipping_address']['ward'] }},
                                                {{ $check_out['shipping_address']['district'] }},
                                                {{ $check_out['shipping_address']['province'] }}<br>
                                                T: {{ $check_out['shipping_address']['number'] }} <br>
                                            </address>
                                        @endif
                                    @endif
                                </dd>
                                {{-- <dt class="complete">
                                    Shipping Method <span class="separator">|</span>
                                    <a onClick="checkout.gotoSection('shipping_method'); return false;"
                                        href="#shipping_method">Change</a>
                                </dt> --}}
                                {{-- <dd class="complete"> Flat Rate - Fixed <br>
                                    <span class="price">$15.00</span>
                                </dd> --}}
                                <dt> Payment Method <span class="separator">|</span> </dt>
                                <dd class="complete" id="payment_check_out_result">
                                    @if ($check_out)
                                        @if (isset($check_out['payment']) && $check_out['payment'] != null)
                                            @if ($check_out['payment']['credit'] == 0)
                                                Check / Money order
                                            @else
                                                Credit card <br>
                                                Owner: {{ $check_out['payment']['name'] }} <br>
                                                @switch($check_out['payment']['type'])
                                                    @case(1)
                                                    Type: American Express <br>
                                                    @break
                                                    @case(2)
                                                    Type: Visa <br>
                                                    @break
                                                    @case(3)
                                                    Type: MasterCard<br>
                                                    @break
                                                    @case(4)
                                                    Type: Discover<br>
                                                    @break
                                                @endswitch
                                                Card number: {{ $check_out['payment']['card_number'] }} <br>
                                                Expiration time: {{ $check_out['payment']['expire_month'] }} /
                                                {{ $check_out['payment']['expire_year'] }}<br>
                                            @endif
                                        @endif
                                    @endif
                                </dd>
                            </dl>
                            <ul class="checkout">
                                <li>
                                    <button onclick="" id="btn_final_check" class="button btn-proceed-checkout"
                                        title="Proceed to Checkout" type="button"><span>Make order</span></button>
                                </li>
                                <li>
                                    @if (session('error'))
                                    <p class="alert alert-danger">
                                        {{ session('error') }}
                                    </p>
                                    @endif
                                </li>
                                <br>
                            </ul>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>
    <!--End main-container -->
@endsection
