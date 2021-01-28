@extends('layout')
@section('title', 'Order detail')
@section('content')
    <!-- breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <ul>
                    <li class="home"> <a href="{{ route('account.order.index')}}"
                            title="Go to Order Page">Order</a><span>&mdash;›</span></li>
                    <li class=""><strong> Order # {{ $order->id }} </strong></li>
                </ul>
            </div>
        </div>
    </div>
    <!-- end breadcrumbs -->
    <!-- main-container -->
    <div class="main-container col2-right-layout">
        <div class="main container">
            <div class="row">
                <section class="col-main col-sm-9 wow">
                    <div class="my-account">
                        <div class="page-title">
                            <h2>Order #{{ $order->id }} -
                                @if ($order->is_actived == 1)
                                    @switch($order->status)
                                        @case(0)
                                        Pending
                                        @break
                                        @case(1)
                                        Waiting for goods
                                        @break
                                        @case(2)
                                        In Delivery
                                        @break
                                        @case(3)
                                        Delivered
                                        @break
                                    @endswitch
                                @else
                                    Cancel
                                @endif
                            </h2>
                        </div>
                        <div class="dashboard">
                            <dl class="order-info panel">
                                <dt>About This Order:</dt>
                                <dd>
                                    <ul id="order-info-tabs">
                                        <li class="current first last">Order Information</li>
                                    </ul>
                                </dd>
                                <div style="float:right;">
                                    <a href="{{ route('account.order.re_order',['id'=>$order->id]) }}" id="btn_check_out"
                                        class="link-reorder button" title="Order again" type="button">
                                        <span>Order again</span>
                                    </a>
                                </div>
                            </dl>
                            <p class="order-date">Order Date: {{ $order->create_date }}</p>
                            <div class="col2-set">
                                <div class="col-1">
                                    <div class="box">
                                        <div class="box-title">
                                            <h4>Shipping Address</h4>
                                        </div>
                                        <div class="box-content">
                                            <address>
                                                {{ $order->shipping_address->name }}<br>
                                                {{ $order->shipping_address->address . ', ' . $order->shipping_address->ward . ', ' . $order->shipping_address->district . ', ' . $order->shipping_address->province }} <br>
                                                T: {{ $order->shipping_address->number }}<br>
                                            </address>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-2">
                                    <div class="box box-payment">
                                        <div class="box-title">
                                            <h4>Payment Method</h4>
                                        </div>
                                        <div class="box-content">
                                            @if ($order->payment != null)
                                                <p>Credit card </p>
                                                <p>Owner: {{ $order->payment->name }} </p>
                                                @switch($order->payment->type)
                                                    @case(1)
                                                    <p>Type: American Express </p>
                                                    @break
                                                    @case(2)
                                                    <p>Type: Visa </p>
                                                    @break
                                                    @case(3)
                                                    <p>Type: MasterCard</p>
                                                    @break
                                                    @case(4)
                                                    <p>Type: Discover</p>
                                                    @break
                                                @endswitch
                                                <p>Expiration time: {{ $order->payment->expire_month }} /
                                                    {{ $order->payment->expire_year }}
                                                </p>
                                            @else
                                                <p>Check / Money order</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="order-items order-details">
                                <h2 class="table-caption">Items Ordered </h2>
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
                                            @foreach ($order_details as $detail)
                                                <tr class="first odd">
                                                    @foreach ($order_detail_products as $product)
                                                        @if ($detail->product_id == $product->id)
                                                            <td class="image">
                                                                <a class="product-image" title="{{$product->name}}"
                                                                    href="{{ route('product_details', ['id'=>$product->id]) }}"><img
                                                                        width="100" height="auto" alt="Sample Product"
                                                                        src="{{ url('uploads/products-images/' . $product->id . '/' . $product->image) }}"></a>
                                                            </td>
                                                            <td>
                                                                <p class="product-name">
                                                                    <a
                                                                        href="{{ route('product_details',['id'=>$product->id]) }}">{{ $product->name }}</a>
                                                                </p>
                                                            </td>
                                                            <td>
                                                                @if ($detail->product_discount != null)
                                                                    <span class="cart-price">
                                                                        <p class="special-price"> <span class="price">
                                                                                {{ $detail->price - ($detail->price * $detail->product_discount) / 100 }}
                                                                            </span>
                                                                        </p>
                                                                        <p class="old-price"> <span
                                                                                class="price-sep">-</span> <span
                                                                                class="price"> {{ $detail->price }}
                                                                            </span> </p>
                                                                    </span>
                                                                @else
                                                                    <span class="cart-price">
                                                                        <p class="price"> <span class="price">
                                                                                {{ $detail->price }}</span>
                                                                        </p>
                                                                    </span>
                                                                @endif

                                                            </td>
                                                            <td>
                                                                {{ $detail->quantity }}
                                                            </td>
                                                            <td>
                                                                <span class="cart-price">
                                                                    @if ($detail->product_discount != null)
                                                                        <span
                                                                            class="price">{{ ($detail->price - ($detail->price * $detail->product_discount) / 100) * $detail->quantity }}
                                                                        </span>
                                                                    @else
                                                                        <span
                                                                            class="price">{{ $detail->price * $detail->quantity }}
                                                                        </span>
                                                                    @endif
                                                                </span>
                                                            </td>
                                                        @endif
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="cart-collaterals row">
                                        <div class="col-sm-8">
                                        </div>
                                        <div class="totals col-sm-4">
                                            <h3>Shopping Cart Total</h3>
                                            <div class="inner">
                                                <table class="table shopping-cart-table-total"
                                                    id="shopping-cart-totals-table">
                                                    <colgroup>
                                                        <col>
                                                        <col width="1">
                                                    </colgroup>
                                                    <tfoot>
                                                        <tr>
                                                            <td colspan="1" class="a-left" style=""><strong>Grand
                                                                    Total</strong>
                                                            </td>
                                                            <td class="a-right" style=""><strong><span
                                                                        class="price">{{ $order->total }}
                                                                         </span></strong>
                                                            </td>
                                                        </tr>
                                                    </tfoot>
                                                    <tbody>
                                                        <tr>
                                                            <td colspan="1" class="a-left" style=""> Subtotal </td>
                                                            <td class="a-right" style=""><span
                                                                    class="price">{{ $order->sub_total }}
                                                                    </span></td>
                                                        </tr>
                                                        @if ($order->discount))
                                                            <tr>
                                                                <td colspan="1" class="a-left" style=""> Discount </td>
                                                                <td class="a-right" style=""><span class="">-
                                                                        {{ $order->discount }} d</span>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                                {{-- <ul class="checkout">
                                                    <li>
                                                        <a href="{{ URL::to('account/order/re-order/' . $order->id) }}"
                                                            id="btn_check_out" onclick="re_order({{ $order->id }})"
                                                            class="button btn-lg btn-proceed-checkout"
                                                            title="Proceed to Checkout" type="button">
                                                            <span>Order again</span>
                                                        </a>
                                                    </li>
                                                    <br>
                                                    <li>
                                                        <a href="{{ URL::to('/account/order/cancel/' . $order->id) }}"
                                                            class="button button-clear" title="Cancel order"
                                                            type="button"><span>Cancel
                                                                order</span>
                                                        </a>
                                                    </li>
                                                    <br>
                                                </ul> --}}
                                            </div>
                                            <!--inner-->

                                        </div>
                                    </div>
                                </fieldset>
                                <div class="buttons-set">
                                    <p class="back-link"><a href="{{ route('account.order.index') }}"><small>«
                                            </small>Back to My Orders</a></p>
                                </div>
                            </div>
                        </div>
                        <!--dashboard-->
                    </div>
                </section>
                @include('personal_side_bar');
            </div>
        </div>
    </div>
    <script>


    </script>
    <!--End main-container -->
@endsection
