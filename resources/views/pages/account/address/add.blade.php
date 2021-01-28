@extends('layout')
@section('title', 'My addresses - Electronical Store')
@section('content')
    <div class="main-container col2-right-layout">
        <div class="main container">
            <div class="row">
                <section class="col-main col-sm-9 wow">
                    <div class="my-account">
                        <div class="page-title">
                            <h2>New Address</h2>
                        </div>
                        <div class="my-wishlist">
                            <fieldset>
                                <form id="add_address_form" action="{{ route('account.address.doAdd') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="row">
                                        <div class="col-md-6">
                                            <h4>Contact information</h4>
                                            <div class="form-group">
                                                <label for=""> Name <span class="required">*</span>
                                                </label>
                                                <input type="text" class="form-control" name="name">
                                            </div>
                                            <div class="form-group">
                                                <label for="shipping:telephone">Telephone <span
                                                        class="required">*</span></label>
                                                <input type="text" name="number"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <h4>Address</h4>
                                            <div class="form-group">
                                                <label for="">Province <span class="required">*</span></label>
                                                <select class="form-control" name="province_id">
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
                                            <div class="form-group">
                                                <label for="">District<span class="required">*</span></label>
                                                <select class="form-control" name="district_id">
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
                                            <div class="form-group">
                                                <label for="">Ward<span class="required">*</span></label>
                                                <select class="form-control" name="ward_id">
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
                                            <div class="form-group">
                                                <label for="shipping:street1">Address <span
                                                        class="required">*</span></label>
                                                <input type="text" title="Street Address" name="address" value=""
                                                    class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="1" name="shipping"> Use this address as primary shipping
                                            address</label>
                                    </div>
                                    <div class="form-group">
                                        <p class="require"><em class="required">* </em>Required Fields</p>
                                        <button id="btn_shipping_checkout" type="submit" class="button continue"><span>Save
                                                this address</span></button>
                                    </div>
                                    <div id="errorMessage"></div>
                                </form>
                            </fieldset>
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

            $("#add_address_form").on("submit", function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('account.address.doAdd') }}",
                    type: 'POST',
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
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.name[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.number != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.number[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.address != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.address[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.province_id != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.province_id[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.district_id != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.district_id[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.ward_id != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.ward_id[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.errorAdd != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.errorAdd[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                        } else {
                            alert(response.message);
                            window.location.href = "{{ route('account.address.index') }}";
                            // alert("A new address has been add.");
                        }
                    },
                    error: function(e) {
                        console.log(e);
                    }
                });
            });
        });

    </script>
@endsection
