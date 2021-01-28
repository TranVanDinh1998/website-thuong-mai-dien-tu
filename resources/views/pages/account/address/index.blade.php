@extends('layout')
@section('title', 'My addresses - Electronical Store')
@section('content')
    <div class="main-container col2-right-layout">
        <div class="main container">
            <div class="row">
                <section class="col-main col-sm-9 wow">
                    <div class="my-account">
                        <div class="page-title">
                            <h2>My addresses</h2>
                        </div>
                        <div class="my-wishlist">
                            <div class="table-responsive">
                                <fieldset>
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
                                    <a style="float:right" class="button" href="{{ route('account.address.add') }}">Add new
                                        address</a>
                                    <table id="wishlist-table" class="clean-table linearize-table data-table">
                                        <thead>
                                            <tr class="first last">
                                                <th class="customer-wishlist-item-info">ID</th>
                                                <th class="customer-wishlist-item-image">Name</th>
                                                <th class="customer-wishlist-item-info">Number</th>
                                                <th class="customer-wishlist-item-info">Address</th>
                                                <th class="customer-wishlist-item-info">Ward</th>
                                                <th class="customer-wishlist-item-info">District</th>
                                                <th class="customer-wishlist-item-info">Province</th>
                                                <th class="customer-wishlist-item-action" colspan="4"></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($addresses as $address)
                                                <tr>
                                                    <td>{{ $address->id }}
                                                    </td>
                                                    <td>{{ $address->name }}</td>
                                                    <td>{{ $address->number }}</td>
                                                    <td>{{ $address->address }}</td>
                                                    <td>
                                                        @foreach ($wards as $ward)
                                                            @if ($ward->id == $address->ward_id)
                                                                {{ $ward->name }}
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($districts as $district)
                                                            @if ($district->id == $address->district_id)
                                                                {{ $district->name }}
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        @foreach ($provinces as $province)
                                                            @if ($province->id == $address->province_id)
                                                                {{ $province->name }}
                                                            @endif
                                                        @endforeach
                                                    </td>
                                                    <td>
                                                        <a @if (Auth::user()->shipping_address_id == $address->id)
                                                            class="active btn btn-success"
                                                        @else
                                                            class="btn btn-default"
                                            @endif
                                            title="Set this address as the primary shipping address"
                                            href="{{ route('account.address.shipping_address',['id'=>$address->id]) }}">Ship</a>
                                            </td>
                                            <td>
                                                <a class="btn btn-default"
                                                    href="{{ route('account.address.edit', ['id' => $address->id]) }}">
                                                    Edit
                                                </a>
                                            </td>
                                            <td class="wishlist-cell5 customer-wishlist-item-remove last"><a
                                                    class="remove-item" title="Clear Address"
                                                    onclick="return confirm('Are you sure?')"
                                                    href="{{ route('account.address.remove',['id'=>$address->id]) }}"><span><span></span></span></a>
                                            </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </fieldset>
                            </div>
                        </div>
                        <div class="box-account">
                            <div class="col2-set">
                                <h4>Primary Setting</h4>
                                <div class="manage_add"></div>
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
                                                        {{ $province->name }} <br>
                                                    @endif
                                                @endforeach
                                                T: {{ $address->number }}<br>
                                            @endif
                                        @endforeach
                                    </address>
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
@endsection
