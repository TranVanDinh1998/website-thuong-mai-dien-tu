@extends('layout')
@section('title', 'Personal imformation - Electronical Store')
@section('content')
    <div class="main-container col2-right-layout">
        <div class="main container">
            <div class="row">
                <section class="col-main col-sm-9 wow">
                    <div class="my-account">
                        <div class="page-title">
                            <h2>My personal Imformation</h2>
                        </div>
                        <div class="col-2 registered-users">
                            <div class="content">
                                <form enctype="multipart/form-data" id="edit_info_form" method="POST"
                                    action="{{route('account.info.doEdit') }}">
                                    @csrf
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" name="name" placeholder="name"
                                            value="{{ $user->name }}">
                                    </div>
                                    <div class="form-group">
                                        <label for="name">Email</label>
                                        <input type="email" class="form-control" name="email" placeholder="email"
                                            value="{{ $user->email }}" disabled>
                                    </div>
                                    <div class="form-group">
                                        <label for="image">Image:</label>
                                        <input type="file" class="form-control" id="image_selected" name="image"
                                            placeholder="Select image">
                                        <p class="help-block">Only accept file .jpg, .png, .gif and < 5MB</p>
                                                <div class="row">
                                                    <div class="form-group">
                                                        <label class="control-label col-sm-1"
                                                            for="current_image">Current:</label>
                                                        <div class="col-sm-5">
                                                            <input type='hidden' class="form-control" name='current_image'
                                                                value='{{ $user->image }}'>
                                                            @if ($user->image)
                                                                <img src="{{ URL::to('uploads/users-images/' . $user->image) }}"
                                                                    style="width: 250px;height:auto;">
                                                            @endif
                                                        </div>
                                                        <label class="control-label col-sm-1"
                                                            for="current_image">New:</label>
                                                        <div class="col-sm-5">
                                                            <img id="image_tag" width="250px;" height="auto;"
                                                                alt="new image" class="img-responsive" src="">
                                                        </div>
                                                    </div>
                                                </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-2 col-sm-hidden"></div>
                                        <div class="col-sm-10">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </div>
                                    </div>
                                    <div id="errorMessage"></div>

                                </form>
                            </div>
                        </div>
                </section>
                @include('personal_side_bar');
            </div>
        </div>
    </div>
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

            $("#edit_info_form").on("submit", function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('account.info.doEdit') }}",
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
                            alert("Edited info.");
                        }
                    },
                    error: function(e) {
                        console.log(e);
                    }
                });
            });
        })

    </script>
@endsection
