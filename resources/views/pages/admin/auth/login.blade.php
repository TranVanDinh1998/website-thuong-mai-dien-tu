@extends('layouts.admin.login')
@section('title', 'Đăng nhập quản lý')
@section('content')
    <!--main content start-->
    <div class="log-w3">
        <div class="w3layouts-main">
            <h2>Đăng nhập</h2>
            <form action="{{ route('auth.admin.login') }}" method="post">
                @csrf
                <div class="panel">
                    @if (count($errors) > 0)
                        @foreach ($errors->all() as $error)
                            <p class="alert alert-danger">{{ $error }}</p>
                        @endforeach
                    @endif
                    @if (session('success'))
                        <p class="alert-success alert">{{ session('success') }}</p>
                    @endif
                    @if (session('error'))
                        <p class="alert-danger alert">{{ session('error') }}</p>
                    @endif
                </div>
                <input type="email" class="ggg" name="email" value="{{old('email')}}" placeholder="E-MAIL" >
                <input type="password" class="ggg" name="password" placeholder="PASSWORD" value="{{old('password')}}">
                {{-- <h6><a href="#">Forgot Password?</a></h6> --}}
                <div class="clearfix"></div>
                <input type="submit" value="Login" name="login">
            </form>
        </div>
    </div>
    <!--main content end-->

@endsection
