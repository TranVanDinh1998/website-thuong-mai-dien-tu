@extends('layout')
@section('title', 'Personal changing password - Electronical Store')
@section('content')
    <div class="main-container col2-right-layout">
        <div class="main container">
            <div class="row">
                <section class="col-main col-sm-9 wow">
                    <div class="my-account">
                        <div class="page-title">
                            <h2>My new password</h2>
                        </div>
                        <div class="col-2 registered-users">
                            <div class="content">
                                <form enctype="multipart/form-data" id="change_password_form" method="POST"
                                    action="{{ URL::to('/account/password') }}">
                                    @csrf
                                    <div id="errorMessage"></div>
                                    <ul class="form-list">
                                        <li>
                                            <label for="">Current password <span class="required">*</span></label>
                                            <br>
                                            <input type="password" title="Name"
                                                class="input-text required-entry validate-password"  value=""
                                                name="old_password">
                                        </li>
                                        <li>
                                            <label for="">New password <span class="required">*</span></label>
                                            <br>
                                            <input type="password" title="Email Address"
                                                class="input-text required-entry validate-password"  value=""
                                                name="new_password">
                                        </li>
                                        <li>
                                            <label for="">Confirm password <span class="required">*</span></label>
                                            <br>
                                            <input type="password" title="Email Address"
                                                class="input-text required-entry validate-password"  value=""
                                                name="confirm_password">
                                        </li>
                                    </ul>
                                    <p class="required">* Required Fields</p>
                                    <div class="buttons-set">
                                        <button id="send2" name="send" type="submit"
                                            class="button login"><span>Change</span></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                </section>
                @include('personal_side_bar');
            </div>
        </div>
    </div>
    <script>
        $(document).ready(function() {
            $('#change_password_form').on('submit', function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ url('account/password') }}",
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
                            if (response.message.old_password != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.old_password[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }

                            if (response.message.new_password != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.new_password[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.confirm_password != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.confirm_password[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.old_password_incorrect != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.confirm_password[0] +
                                        "</div>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                        } else {
                            alert("Changed password.");
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
