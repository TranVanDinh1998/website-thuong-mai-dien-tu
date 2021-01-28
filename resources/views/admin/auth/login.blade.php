@extends('admin.layout')
@section('title', 'Admin login')
@section('content')
    <!--main content start-->
    <div class="log-w3">
        <div class="w3layouts-main">
            <h2>Sign In Now</h2>
            <form action="{{route('admin.auth.doLogin')}}" id="login_form" method="post">
                @csrf
                <div id="errorMessage"></div>
                <input type="email" class="ggg" name="email" placeholder="E-MAIL" required="">
                <input type="password" class="ggg" name="password" placeholder="PASSWORD" required="">
                {{-- <span><input name="remember" value="1" type="checkbox" />Remember Me</span> --}}
                {{-- <h6><a href="#">Forgot Password?</a></h6> --}}
                <div class="clearfix"></div>
                <input type="submit" value="Sign In" name="login">
            </form>
            {{-- <p>Don't Have an Account ?<a href="registration.html">Create an account</a></p> --}}
        </div>
    </div>
    <!--main content end-->
    <script>
        $(document).ready(function() {
            $('#login_form').on('submit', (function(e) {
                e.preventDefault();
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $.ajax({
                    url: "{{route('admin.auth.doLogin')}}",
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
                                        "<p  style='width:100%; margin:auto;'>" +
                                        response.message.email[0] +
                                        "</p>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                            if (response.message.password != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<p  style='width:100%; margin:auto;'>" +
                                        response.message.password[0] + "</p>");
                                    $("#errorMessage").fadeOut(10000);

                                });
                            }
                            if (response.message.errorLogin != undefined) {
                                $("#errorMessage").fadeIn(1000, function() {
                                    $("#errorMessage").html(
                                        "<p  style='width:100%; margin:auto;'>" +
                                        response.message.errorLogin[0] +
                                        "</p>"
                                    );
                                    $("#errorMessage").fadeOut(10000);
                                });
                            }
                        } else {
                            window.location.href = "{{route('admin.index')}}";
                        }
                    },
                })
            }));
        });

    </script>
@endsection
