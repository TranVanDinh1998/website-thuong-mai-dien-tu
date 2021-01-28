@extends('admin.layout')
@section('title', 'Edit a product of tag')
@section('head')
    <script src="{{ url('admin/ckeditor/ckeditor.js') }}"></script>
@endsection
@section('content')
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.tag.index') }}">Tag management</a></li>
                <li class="active">{{ $tag->name }}</li>
                <li class=""><a href="{{ route('admin.tag.product.index', ['id' => $tag->id]) }}">Products</a>
                </li>
                <li class="active">Edit</li>
            </ol>
            <section class="panel">
                <div class="panel-heading">
                    Edit a product of a tag
                </div>
                <div class="panel-body">
                    <div class="position-center">
                        <form id="edit_tag_product_form" enctype="multipart/form-data" method="POST"
                            action="{{ route('admin.tag.product.doEdit', ['id' => $tag->id]) }}">
                            @csrf
                            <div id="errorMessage"></div>
                            <div class="form-group">
                                <label class="control-label" for="name">Name</label>
                                <input type="text" class="form-control" value="{{ $tag->name }}" name="name"
                                    placeholder="name" disabled>
                                <input type="hidden" class="form-control" value="{{ $tag_product->id }}"
                                    name="tag_product_id">
                            </div>
                            <div class="form-group">
                                <label for="category_id">Product:</label>
                                <select class="form-control" name="product_id">
                                    <option>Select product </option>
                                    <option value="{{ $product->id }}" selected>{{ $product->id }} - {{ $product->name }}</option>
                                    @foreach ($products as $product)
                                        <option value='{{ $product->id }}'>{{ $product->id }} -
                                            {{ $product->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-2 col-sm-hidden"></div>
                                <div class="col-sm-10">
                                    <button type="submit" id="btnRegister" name="btn_edit"
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
        jQuery(document).ready(function($) {
            $('#edit_tag_product_form').on('submit', (function(e) {
                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                var tag_id = $('#tag_id').val();
                $.ajax({
                    url: "{{ route('admin.tag.product.doEdit', ['id' => $tag->id]) }}",
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
                            if (response.message.product_id_list != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.product_id_list[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }

                            if (response.message.erroreditDetail != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.erroreditDetail[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                        } else {
                            alert("Success.");
                            window.location.href = "{{ route('admin.tag.product.index', ['id' => $tag->id]) }}";

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
