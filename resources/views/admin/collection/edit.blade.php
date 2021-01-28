@extends('admin.layout')
@section('title', 'Collection management')
@section('head')
    <script src="{{ url('admin/ckeditor/ckeditor.js') }}"></script>
@endsection
@section('content')
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.collection.index') }}">Collection management</a></li>
                <li class="active">{{ $collection->name }}</li>
            </ol>
            <section class="panel">
                <div class="panel-heading">
                    {{ $collection->name }}
                </div>
                <div class="panel-body">
                    <div class="position-center">
                        <form role="form" method="POST" enctype="multipart/form-data"
                            action="{{ URL::to('administrator/collection/edit') }}" id="edit_collection_form">
                            @csrf
                            <div id="errorMessage"></div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="hidden" class="form-control" name="id" value="{{ $collection->id }}">
                                <input type="text" class="form-control" name="name" placeholder="name"
                                    value="{{ $collection->name }}">
                            </div>
                            <div class="form-group">
                                <label for="">Description</label>
                                <textarea type="text" class="form-control" name="description"
                                    placeholder="Password">{{ $collection->description }}</textarea>
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
                                                    @if ($collection->image)
                                                        <img src="{{ URL::to('uploads/categories-images/' . $collection->category_id . '/' . $collection->image) }}"
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
                                <label for="category_id">Category:</label>
                                <select class="form-control" name="category_id">
                                    <option>Select category </option>
                                    @foreach ($categories as $category)
                                        @if ($collection->category_id != $category->id)
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
                                <label for="priority">Priority</label>
                                <input type="number" class="form-control" name="priority" placeholder="priority"
                                    value="{{ $collection->priority }}">
                            </div>
                            <div class="form-group">
                                <div class="col-sm-2 col-sm-hidden"></div>
                                <div class="col-sm-10">
                                    <button type="submit" name="btn_add" class="btn btn-primary">Submit</button>
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

            function readURL1(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('#sub1_image_tag').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(input.files[0]);
                }
            }
            $("#sub1_image_selected").change(function() {
                readURL1(this);
            });
            $('#edit_collection_form').on('submit', (function(e) {
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
                    url: "{{ route('admin.collection.doEdit') }}",
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
                            if (response.message.caetgory_id != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.caetgory_id[0] +
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
                                        response.message.errorAdd[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                        } else {
                            alert("Edited a collection.");
                            window.location.href = "{{ route('admin.collection.index') }}";
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
