@extends('admin.layout')
@section('title', 'Edit a tag')
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
            </ol>
            <section class="panel">
                <div class="panel-heading">
                    Edit a tag
                </div>
                <div class="panel-body">
                    <div class="position-center">
                        <form id="edit_tag_form" method="POST" enctype="multipart/form-data"
                            action="{{ URL::to('administrator/tag/edit') }}">
                            @csrf
                            <div id="errorMessage"></div>
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" name="name" value="{{ $tag->name }}"
                                    placeholder="name">
                                <input type="hidden" class="form-control" name="id" value="{{ $tag->id }}"
                                    placeholder="name">
                            </div>
                            <div class="form-group">
                                <div class="col-sm-2 col-sm-hidden"></div>
                                <div class="col-sm-10">
                                    <button type="submit" name="btn_edit" class="btn btn-primary">Submit</button>
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
        // function display_error()
        jQuery(document).ready(function($) {
            $('#edit_tag_form').on('submit', (function(e) {
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
                    url: "{{ route('admin.tag.doEdit') }}",
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
                            if (response.message.id != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.id[0] +
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
                            alert("Edited a new tag.");
                            window.location.href = "{{ route('admin.tag.index') }}";
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
