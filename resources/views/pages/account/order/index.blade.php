@extends('layout')
@section('title', 'Order')
@section('content')
    <!-- main-container -->
    <div class="main-container col2-right-layout">
        <div class="main container">
            <div class="row">
                <section class="col-main col-sm-9 wow">
                    <div class="my-account">
                        <div class="page-title">
                            <h2>My orders</h2>
                        </div>
                        <div class="col-sm-12 wow">
                            @if (session('success'))
                                <div id="success_msg" class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif
                            @if (session('error'))
                                <div id="error_msg" class="alert alert-danger">
                                    {!! session('error') !!}
                                </div>
                            @endif
                            <div class="toolbar">
                                <div class="pager" style="float: left">
                                    <p class="amount"><strong>{{ $count_orders }} item(s)<strong></p>
                                    <div id="limiter">
                                        <label>View: </label>
                                        <ul>
                                            <li><a
                                                    href="{{ route('account.order.index', ['view' => 15]) }}">15<span
                                                        class="right-arrow"></span>
                                                </a>
                                                <ul>
                                                    <li><a
                                                            href="{{ route('account.order.index', ['view' => 20]) }}">20
                                                        </a>
                                                    </li>
                                                    <li><a
                                                            href="{{ route('account.order.index', ['view' => 30]) }}">30
                                                        </a>
                                                    </li>
                                                    <li><a
                                                            href="{{ route('account.order.index', ['view' => 35]) }}">35
                                                        </a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="pages">
                                        <ul class="pagination">
                                            {!! $all_orders->withQueryString()->links() !!}
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <div class="product-tabs-content-inner clearfix">
                                    <div class="table-responsive">
                                        <table class="data-table" id="my-orders-table">
                                            <col>
                                            <col>
                                            <col>
                                            <col width="1">
                                            <col width="1">
                                            <col width="1">
                                            <thead>
                                                <tr class="first last">
                                                    <th>Order ID</th>
                                                    <th>Date</th>
                                                    <th>Ship to</th>
                                                    <th><span class="nobr">Order Total</span></th>
                                                    <th>Status</th>
                                                    <th>Active</th>
                                                    <th>&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($all_orders as $order)
                                                    <tr class="first odd">
                                                        <td>{{ $order->id }}</td>
                                                        <td>{{ $order->create_date }} </td>
                                                        <td>
                                                            {{$order->shipping_address->address.', '.$order->shipping_address->ward.', '.$order->shipping_address->district.', '.$order->shipping_address->province}}
                                                        </td>
                                                        <td><span class="price">{{ $order->total }} d</span></td>
                                                        <td>
                                                            <em>
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
                                                            </em>
                                                        </td>
                                                        <td>
                                                            @if ($order->is_actived == 0)
                                                                No
                                                            @else
                                                                Yes
                                                            @endif
                                                        </td>
                                                        <td class="a-center last">
                                                            <span class="nobr">
                                                                <a
                                                                    href="{{ route('account.order.detail', ['id' => $order->id]) }}">View
                                                                    Order</a>
                                                                @if ($order->is_actived == 1)
                                                                    <span class="separator">|</span> <a
                                                                        href="{{ route('account.order.cancel', ['id' => $order->id]) }}">Cancel</a>
                                                                @endif
                                                                <span class="separator">|</span> <a
                                                                    href="{{ route('account.order.re_order', ['id' => $order->id]) }}">Reorder</a>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                @include('personal_side_bar');
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $("#success_msg").fadeOut(10000);
            $("#error_msg").fadeOut(10000);
        });

    </script>
    <!--End main-container -->
@endsection
