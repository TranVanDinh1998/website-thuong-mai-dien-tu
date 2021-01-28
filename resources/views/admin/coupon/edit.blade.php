@extends('admin.layout')
@section('title', 'Coupon management')
@section('content')
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.coupon.index')}}">Coupon management</a></li>
                <li class="active">{{ $coupon->name }}</li>
            </ol>
            <section class="panel">
                <div class="panel-heading">
                    {{ $coupon->name }}
                </div>
                <div class="panel-body">
                    <div class="position-center">
                        <form id="edit_coupon_form" enctype="multipart/form-data"
                            action="{{ route('admin.coupon.doEdit') }}" method="POST">
                            @csrf
                            <div id="errorMessage"></div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="hidden" class="form-control" name="id" placeholder="name"
                                    value="{{ $coupon->id }}">
                                <input type="text" class="form-control" name="name" placeholder="name"
                                    value="{{ $coupon->name }}">
                            </div>
                            <div class="form-group">
                                <label for="name">Code</label>
                                <input type="text" class="form-control" name="code" value="{{ $coupon->code }}"
                                    placeholder="name">
                            </div>
                            <div class="form-group">
                                <label for="image">Image:</label>
                                <input type="file" class="form-control" id="image_selected" name="image"
                                    placeholder="Select image">
                                <p class="help-block">Only accept file .jpg, .png, .gif and < 5MB</p>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-sm-1" for="current_image">Current:</label>
                                                <div class="col-sm-5">
                                                    <input type='hidden' class="form-control" name='current_image'
                                                        value='{{ $coupon->image }}'>
                                                    @if ($coupon->image)
                                                        <img src="{{ URL::to('uploads/coupons-images/'  . $coupon->image) }}"
                                                            style="width: 250px;height:auto;">
                                                    @endif
                                                </div>
                                                <label class="control-label col-sm-1" for="current_image">New:</label>
                                                <div class="col-sm-5">
                                                    <img id="image_tag" width="250px;" height="auto;" alt="new image"
                                                        class="img-responsive" src="">
                                                </div>
                                            </div>
                                        </div>
                            </div>
                            <div class="form-group">
                                <label for="name">Expire at</label>
                                <input type="date" class="form-control" name="expire_date"
                                    value="{{ $coupon->expire_date }}" placeholder="name">
                            </div>
                            <div class="form-group">
                                <label for="name">Quantity</label>
                                <input type="number" class="form-control" name="quantity" value="{{ $coupon->quantity }}"
                                    placeholder="name">
                            </div>
                            <div class="form-group">
                                <label for="name">Remaining</label>
                                <input type="number" class="form-control" name="remaining" value="{{ $coupon->remaining }}"
                                    placeholder="name">
                            </div>
                            <div class="form-group">
                                <label for="name">Minimum required order's value</label>
                                <input type="number" class="form-control" name="minimum_order_value"
                                    value="{{ $coupon->minimum_order_value }}" placeholder="name">
                            </div>
                            <div class="form-group">
                                <label for="name">Discount type</label>
                                <select id="discount_type" name="type" class="form-control">
                                    <option>Select discount type</option>
                                    <option value="0" @if ($coupon->type == 0)
                                        selected
                                        @endif>Cash</option>
                                    <option value="1" @if ($coupon->type == 1)
                                        selected
                                        @endif>%</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="name">Discount </label>
                                <input type="number" class="form-control" name="discount" value="{{ $coupon->discount }}"
                                    placeholder="name">
                            </div>
                            <div class="form-group">
                                <div class="col-sm-2 col-sm-hidden"></div>
                                <div class="col-sm-10">
                                    <button type="submit" id="btnRegister" name="btn_add"
                                        class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </section>
        </section>
        <!-- footer -->
        @include('admin.footer')
        <!-- / footer -->
    </section>

    <!--main content end-->
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#image_tag').attr('src', e.target.result);
                }
                reader.readAsDataURL(input.files[0]);
            }
        }
        $(document).ready(function() {
            $("#image_selected").change(function() {
                readURL(this);
            });

            $('#edit_coupon_form').on('submit', (function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('admin.coupon.doEdit') }}",
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
                            if (response.message.code != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.code[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.quantity != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.quantity[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.expire_date != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.expire_date[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.minimum_order_value != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.minimum_order_value[
                                            0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.type != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.type[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.discount != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.discount[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.errorEdit != undefined) {
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
                            alert("Edited a coupon.");
                            window.location.href = "{{ route('admin.coupon.index') }}";
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
