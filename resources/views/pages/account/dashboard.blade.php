@extends('layout')
@section('title', 'Dashboard - Electronical Store')
@section('content')
    <!-- main-container -->
    <div class="main-container col2-right-layout">
        <div class="main container">
            <div class="row">
                <section class="col-main col-sm-9 wow">
                    <div class="my-account">
                        <div class="page-title">
                            <h2>My Dashboard</h2>
                        </div>
                        <div class="dashboard">
                            <div class="welcome-msg"> <strong>Hello, {{ Auth::user()->name }}!</strong>
                                <p>From your My Account Dashboard you have the ability to view a snapshot of your recent
                                    account activity and update your account information. Select a link below to view or
                                    edit information.</p>
                            </div>
                            <div class="recent-orders">
                                <div class="title-buttons"><strong>Recent Orders</strong> <a
                                        href="{{ route('account.order.index') }}">View All </a> </div>
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
                                                <th>Order #</th>
                                                <th>Date</th>
                                                <th>Ship to</th>
                                                <th><span class="nobr">Order Total</span></th>
                                                <th>Status</th>
                                                <th>Active</th>
                                                <th>&nbsp;</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($recent_orders as $order)
                                                <tr class="first odd">
                                                    <td>{{ $order->id }}</td>
                                                    <td>{{ $order->create_date }} </td>
                                                    <td>
                                                        {{ $order->shipping_address->address . ', ' . $order->shipping_address->ward . ', ' . $order->shipping_address->district . ', ' . $order->shipping_address->province }}
                                                    </td>
                                                    <td><span class="price">{{ $order->total }} d</span></td>
                                                    <td>
                                                        <em>
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
                            <div class="box-account">
                                <div class="page-title">
                                    <h2>Account Information</h2>
                                </div>
                                <div class="col2-set">
                                    <div class="col-1">
                                        <h5>Contact Information</h5>
                                        <a href="{{ route('account.info.index') }}">Edit</a>
                                        <p>{{ $user->name }}<br>
                                            {{ $user->email }}<br>
                                            <a href="{{ route('account.password.index') }}">Change Password</a>
                                        </p>
                                    </div>
                                </div>
                                <div class="col2-set">
                                    <h4>Address Book</h4>
                                    <div class="manage_add"><a href="{{ route('account.address.index') }}">Manage
                                            Addresses</a> </div>
                                    <div class="col-1">
                                        <h5>Primary Shipping Address</h5>
                                        <address>
                                            @foreach ($addresses as $address)
                                                @if ($address->id == $user->shipping_address_id)
                                                    {{ $address->name }}<br>
                                                    {{ $address->address }} ,
                                                    @foreach ($wards as $ward)
                                                        @if ($ward->id == $address->ward_id)
                                                            {{ $ward->name }} ,
                                                        @endif
                                                    @endforeach
                                                    @foreach ($districts as $district)
                                                        @if ($district->id == $address->district_id)
                                                            {{ $district->name }} ,
                                                        @endif
                                                    @endforeach
                                                    @foreach ($provinces as $province)
                                                        @if ($province->id == $address->province_id)
                                                            {{ $province->name }}
                                                        @endif
                                                    @endforeach
                                                    T: {{ $address->number }}<br>
                                                    <a
                                                        href="{{ route('account.address.edit', ['id' => $user->shipping_address_id]) }}">Edit
                                                        Address</a>
                                                @endif
                                            @endforeach
                                        </address>
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
    <!--End main-container -->
@endsection
