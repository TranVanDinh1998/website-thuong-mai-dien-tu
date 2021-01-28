@extends('layout')
@section('title', 'Login - Electronical Store')
@section('content')
    <section class="main-container col1-layout">
        <div class="main container">
            <div class="account-login">
                <div class="page-title">
                    <h2>Login or Create an Account</h2>
                </div>
                <fieldset class="col2-set">
                    <legend>Login or Create an Account</legend>
                    <div class="col-1 new-users">
                        <strong>New Customers</strong>
                        <div class="content" id="intro">
                            <p>By creating an account with our store, you will be able to move through the checkout process
                                faster, store multiple shipping addresses, view and track your orders in your account and
                                more.</p>
                            <div class="buttons-set">
                                <button class="button create-account" id="btn_register"
                                 {{-- href="{{ URL::to('/login') }}" --}} ><span>Create an
                                        Account</span></button>
                            </div>
                        </div>
                        <div class="content" id="register">
                            <form id="register_form" enctype="multipart/form-data" method="POST">
                                @csrf
                                <ul class="form-list">
                                    <li>
                                        <p id="errorRegisterMessage"></p>
                                    </li>
                                    <li>
                                        <label for="email">Name <span class="required">*</span></label>
                                        <br>
                                        <input type="text" title="Name" class="input-text required-entry" name="name">
                                    </li>
                                    <li>
                                        <label for="email">Email Address <span class="required">*</span></label>
                                        <br>
                                        <input type="email" title="Email Address" class="input-text required-entry"
                                            name="email">
                                    </li>
                                    <li>
                                        <label for="password">Password <span class="required">*</span></label>
                                        <br>
                                        <input type="password" title="Password"
                                            class="input-text required-entry validate-password" name="password">
                                    </li>
                                    <li>
                                        <label for="re_password">Re password <span class="required">*</span></label>
                                        <br>
                                        <input type="password" title="Re Password"
                                            class="input-text required-entry validate-password" name="re_password">
                                    </li>
                                </ul>
                                <p class="required">* Required Fields</p>
                                <div class="buttons-set">
                                    <button type="submit" class="button register"><span>Register</span></button>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="col-2 registered-users"><strong>Registered Customers</strong>
                        <form id="login_form" method="POST" enctype='multipart/form-data' action="{{route('doLogin')}}">
                            @csrf
                            <div class="content">
                                <p>If you have an account with us, please log in.</p>
                                <ul class="form-list">
                                    <li>
                                        <p id="errorMessage"></p>
                                    </li>
                                    <li>
                                        <label for="email">Email Address <span class="required">*</span></label>
                                        <br>
                                        <input type="email" title="Email Address" class="input-text required-entry"
                                            name="email">
                                    </li>
                                    <li>
                                        <label for="pass">Password <span class="required">*</span></label>
                                        <br>
                                        <input type="password" title="Password"
                                            class="input-text required-entry validate-password" name="password">
                                    </li>
                                </ul>
                                <p class="required">* Required Fields</p>
                                <div class="buttons-set">
                                    <button type="submit" class="button login"><span>Login</span></button>
                                    <a class="forgot-word" href="">Forgot
                                        Your Password?</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </fieldset>
            </div>
            <br>
            <br>
            <br>
            <br>
            <br>
        </div>
    </section>
    <script>
        $(document).ready(function() {
            $('#register').hide();
            $('#btn_register').on('click', function() {
                $('#intro').hide();
                $('#register').show();
            });
            $('#login_form').on('submit', (function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('doLogin')}}",
                    type: "POST",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    dataType: 'JSON',
                    beforeSend: function() {},
                    success: function(response) {
                        console.log(response);
                        if (response.error == true) {
                            if (response.message.email != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<p class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.email[0] +
                                        "</p>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.password != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<p class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.password[0] + "</p>");
                                    $("#errorMessage").fadeOut(10000);

                                });
                            }
                            if (response.message.errorLogin != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<p class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.errorLogin[0] +
                                        "</p>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                        } else {
                            window.location.href = "{{route('account.dashboard')}}";
                        }
                    },
                })
            }));
            $('#register_form').on('submit', (function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{ url('/register') }}",
                    type: "POST",
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
                                $("#errorRegisterMessage").fadeIn(1000, function() {
                                    $("#errorRegisterMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.name[0] +
                                        "</div>"
                                    );
                                    $("#errorRegisterMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.email != undefined) {
                                $("#errorRegisterMessage").fadeIn(1000, function() {
                                    $("#errorRegisterMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.email[0] +
                                        "</div>"
                                    );
                                    $("#errorRegisterMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.password != undefined) {
                                $("#errorRegisterMessage").fadeIn(1000, function() {
                                    $("#errorRegisterMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.password[0] + "</div>");
                                    $("#errorRegisterMessage").fadeOut(10000);

                                });
                            }
                            if (response.message.re_password != undefined) {
                                $("#errorRegisterMessage").fadeIn(1000, function() {
                                    $("#errorRegisterMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.re_password[0] +
                                        "</div>");
                                    $("#errorRegisterMessage").fadeOut(10000);

                                });
                            }
                            if (response.message.image != undefined) {
                                $("#errorRegisterMessage").fadeIn(1000, function() {
                                    $("#errorRegisterMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.image[0] +
                                        "</div>"
                                    );
                                    $("#errorRegisterMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.errorImage != undefined) {
                                $("#errorRegisterMessage").fadeIn(1000, function() {
                                    $("#errorRegisterMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.errorImage[0] +
                                        "</div>"
                                    );
                                    $("#errorRegisterMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.errorRegister != undefined) {
                                $("#errorRegisterMessage").fadeIn(1000, function() {
                                    $("#errorRegisterMessage").html(
                                        "<div class='alert alert-danger' style='width:100%; margin:auto;'>" +
                                        response.message.errorRegister[0] +
                                        "</div>"
                                    );
                                    $("#errorRegisterMessage").fadeOut(10000);
                                });
                            }
                        } else {
                            alert("A new user has been registered.");
                            window.location.href = "{{ url('/login') }}";
                        }
                    },
                    // error: function(e) {
                    //     console.log(e);
                    // }
                })
            }));

        });

    </script>
@endsection
