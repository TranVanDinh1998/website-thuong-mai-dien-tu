@extends('admin.layout')
@section('title', 'Product management ')
@section('head')
    <script src="{{ url('admin/ckeditor/ckeditor.js') }}"></script>
@endsection
@section('content')
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('/administrator/product/') }}">Product management</a></li>
                <li class="active">{{ $product->name }}</li>
            </ol>
            <section class="panel">
                <div class="panel-heading">
                    {{ $product->name }}
                </div>
                <div class="panel-body">
                    <div class="position-center">
                        <form method="POST" id="edit_product_form" action="{{ URL::to('administrator/product/edit') }}"
                            enctype='multipart/form-data'>
                            @csrf
                            <div id="errorMessage"></div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="hidden" class="form-control" name="id" placeholder="name"
                                    value="{{ $product->id }}">
                                <input type="text" class="form-control" name="name" placeholder="name"
                                    value="{{ $product->name }}">
                            </div>
                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea type="text" class="form-control" id="descript" name="description"
                                    placeholder="Description">{{ $product->description }}</textarea>
                                <script>
                                    CKEDITOR.replace('descript');
                                </script>
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
                                                        value='{{ $product->image }}'>
                                                    @if ($product->image)
                                                        <img src="{{ URL::to('uploads/products-images/' . $product->id . '/' . $product->image) }}"
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
                                <label for="price">Price</label>
                                <input type="number" class="form-control" value="{{ $product->price }}" name="price"
                                    placeholder="price">
                            </div>
                            <div class="form-group">
                                <label for="quantity">Remaining</label>
                                <input type="number" class="form-control" value="{{ $product->remaining }}" name="quantity"
                                    placeholder="quantity">
                            </div>
                            <div class="form-group">
                                <label for="category_id">Category:</label>
                                <select class="form-control" name="category_id" required>
                                    <option>Select category </option>
                                    @foreach ($categories as $category)
                                        @if ($product->category_id != $category->id)
                                            <option value='{{ $category->id }}'>{{ $category->id }} -
                                                {{ $category->name }}
                                            </option>
                                        @else
                                            <option value='{{ $category->id }}' selected>{{ $category->id }} -
                                                {{ $category->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="producer_id">Producer:</label>
                                <select class="form-control" name="producer_id" required>
                                    <option>Select producer </option>
                                    @foreach ($producers as $producer)
                                        @if ($product->producer_id != $producer->id)
                                            <option value='{{ $producer->id }}'>{{ $producer->id }} -
                                                {{ $producer->name }}
                                            </option>
                                        @else
                                            <option value='{{ $producer->id }}' selected>{{ $producer->id }} -
                                                {{ $producer->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="discount">Discount</label>
                                <input type="number" class="form-control" min="0" max="100" name="discount"
                                    placeholder="discount" value="{{ $product->discount }}">
                            </div>
                            <button type="submit" class="btn btn-info">Submit</button>
                        </form>
                    </div>
                </div>
            </section>
        </section>

        <!-- Form add category and form add producer -->
        {{-- @include('admin.optional_add') --}}
        <!-- / Form add category and form add producer -->

        <!-- footer -->
        @include('admin.footer')
        <!-- / footer -->
    </section>

    <!--main content end-->
    <script>
        $(document).ready(function() {
            function readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#image_tag').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#image_selected").change(function() {
                readURL(this);
            });

            $('#edit_product_form').on('submit', (function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ url('administrator/product/edit') }}",
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
                            if (response.message.description != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.description[0] +
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
                            if (response.message.price != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.price[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.category_id != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.category_id[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.producer_id != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.producer_id[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.errorImage != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.errorImage[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.errorEdit != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.errorEdit[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                        } else {
                            alert("Edited a product.");
                            // window.location.href = "{{ url('administrator/product') }}";
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
