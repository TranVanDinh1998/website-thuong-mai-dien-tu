@extends('admin.layout')
@section('title', 'edit new advertise')
@section('head')
    <script src="{{ url('admin/ckeditor/ckeditor.js') }}"></script>
@endsection
@section('content')
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.advertise.index')}}">Advertise management</a></li>
                <li class="active">edit</li>
            </ol>
            <section class="panel">
                <div class="panel-heading">
                    New advertise
                </div>
                <div class="panel-body">
                    <div class="position-center">
                        <form id="edit_advertise_form" method="POST" enctype="multipart/form-data"
                            action="{{ route('admin.advertise.doEdit') }}">
                            @csrf
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" value="{{ $advertise->name }}" name="name"
                                    placeholder="name">
                                <input type="hidden" class="form-control" value="{{ $advertise->id }}" name="id"
                                    placeholder="id">
                            </div>
                            <div class="form-group">
                                <label for="name">Summary</label>
                                <input type="text" class="form-control" value="{{ $advertise->summary }}" name="summary"
                                    placeholder="summary">
                            </div>
                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea type="text" class="form-control" name="description"
                                    placeholder="Description">{{ $advertise->description }}</textarea>
                                <script>
                                    CKEDITOR.replace('description');
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
                                                        value='{{ $advertise->image }}'>
                                                    @if ($advertise->image)
                                                        <img src="{{ URL::to('uploads/advertises-images/' . $advertise->image) }}"
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
                                <label for="product_id">Product:</label>
                                <select class="form-control" name="product_id">
                                    <option>Select product_id </option>
                                    @foreach ($products as $product)
                                        @if ($advertise->product_id != $product->id)
                                            <option value='{{ $product->id }}'>{{ $product->id }} -
                                                {{ $product->name }}
                                            </option>
                                        @else
                                            <option value='{{ $product->id }}' selected>{{ $product->id }} -
                                                {{ $product->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-2 col-sm-hidden"></div>
                                <div class="col-sm-10">
                                    <button type="submit" name="btn_edit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                        </form>
                        <div id="errorMessage"></div>
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

        // function display_error()
        jQuery(document).ready(function($) {
            // $("#multi_select").chosen();
            $("#image_selected").change(function() {
                readURL(this);
            });

            $('#edit_advertise_form').on('submit', (function(e) {
                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ route('admin.advertise.doEdit') }}",
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
                            if (response.message.summary != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.summary[0] +
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
                            if (response.message.image != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.image[0] +
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
                            alert("edited an advertise.");
                            window.location.href = "{{ route('admin.advertise.index') }}";
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
