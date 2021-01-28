@extends('layout')
@section('title', 'Error - Electronic Store')
@section('content')
    <!--End main-container -->
    <section class="content-wrapper">
        <div class="container">
            <div class="std">
                <div class="page-not-found">
                    <h2>404</h2>
                    <h3><img src="images/signal.png">Oops! The Page you requested was not found!</h3>
                    <div><a href="{{URL::to('/')}}" type="button" class="btn-home"><span>Back To Home</span></a></div>
                </div>
            </div>
        </div>
    </section>
    <!--End main-container -->
@endsection
