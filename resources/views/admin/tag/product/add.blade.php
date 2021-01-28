@extends('admin.layout')
@section('title', 'Add product to tag')
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
                <li class="active">Add</li>
            </ol>
            <section class="panel">
                <div class="panel-heading">
                    Add product to tag {{$tag->name}}
                </div>
                <div class="panel-body">
                    <div class="position-center">
                        <form id="add_tag_product_form" enctype="multipart/form-data" method="POST"
                            action="{{ route('admin.tag.product.doAdd',['id'=>$tag->id]) }}">
                            @csrf
                            <div id="errorMessage"></div>
                            <div class="form-group">
                                <label class="control-label" for="name">Name</label>
                                <input type="text" class="form-control" value="{{ $tag->name }}" name="name"
                                    placeholder="name" disabled>
                                <input type="hidden" class="form-control" id="tag_id" value="{{ $tag->id }}"
                                    name="id" placeholder="name">
                            </div>
                            <div class="form-group">
                                <table class="table table-striped b-t b-light">
                                    <thead>
                                        <tr>
                                            <th style="width:20px;">
                                                <label class="i-checks m-b-none">
                                                    <input type="checkbox"><i></i>
                                                </label>
                                            </th>
                                            <th>ID</th>
                                            <th>Image</th>
                                            <th>Name</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($products as $product)
                                            <tr>
                                                <td>
                                                    <label class="i-checks m-b-none">
                                                        <input type="checkbox" name="product_id_list[]"
                                                            value="{{ $product->id }}"><i></i>
                                                    </label>
                                                </td>
                                                <td>
                                                    {{ $product->id }}
                                                </td>
                                                <td>
                                                    <img style="width: 70px; height : auto"
                                                        src="{{ url('uploads/products-images/' . $product->id . '/' . $product->image) }}"
                                                        alt="{{ $product->name }}">
                                                </td>
                                                <td><span class="text-ellipsis">{{ $product->name }}</span></td>
                                                <td>
                                                    @if ($product->is_actived == 1)
                                                        <strong>Active</strong>
                                                    @else
                                                        <strong>Deactive</strong>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
        jQuery(document).ready(function($) {
            $(".mul-select").select2({
                placeholder: "select country", //placeholder
                tags: true,
                tokenSeparators: ['/', ',', ';', " "]
            });
            $('#add_tag_product_form').on('submit', (function(e) {
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
                    url: "{{ route('admin.tag.product.doAdd',['id'=>$tag->id]) }}",
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
                            if (response.message.errorAddDetail != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.errorAddDetail[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                        } else {
                            alert("Success.");
                            window.location.href = "{{ route('admin.tag.product.index',['id'=>$tag->id]) }}";

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
