@extends('admin.layout')
@section('title', 'Image management ')
@section('head')
    <script src="{{ url('admin/ckeditor/ckeditor.js') }}"></script>
@endsection
@section('content')
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('administrator/product') }}">Product management</a></li>
                <li><a href="#">{{ $product->name }}</a></li>
                <li><a href="{{ route('admin.product.image.index', ['id' => $product->id]) }}">Gallery</a></li>
                <li class="active">Image #{{ $product_image->id }}</li>
            </ol>
            <section class="panel">
                <div class="panel-heading">
                    Image #{{ $product_image->id }}
                </div>
                <div class="panel-body">
                    <div class="position-center">
                        <form method="POST" id="edit_product_form"
                            action="{{ route('admin.product.image.doEdit', ['id' => $product->id]) }}"
                            enctype='multipart/form-data'>
                            @csrf
                            <div id="errorMessage"></div>
                            <div class="form-group">
                                <label for="image">Image:</label>
                                <input type="file" class="form-control" id="image_selected" name="image"
                                    placeholder="Select image">
                                <p class="help-block">Only accept file .jpg, .png, .gif and < 5MB</p>
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="control-label col-sm-1" for="current_image">Current:</label>
                                                <div class="col-sm-5">
                                                    <input type="hidden" class="form-control" name="image_id"
                                                        placeholder="name" value="{{ $product_image->id }}">
                                                    @if ($product->image)
                                                        <img src="{{ URL::to('uploads/products-images/' . $product->id . '/' . $product_image->image) }}"
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
                    url: "{{ route('admin.product.image.doEdit', ['id' => $product->id]) }}",
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
                            alert("Edited an image.");
                            window.location.href =
                                "{{ route('admin.product.image.index', ['id' => $product->id]) }}";
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
