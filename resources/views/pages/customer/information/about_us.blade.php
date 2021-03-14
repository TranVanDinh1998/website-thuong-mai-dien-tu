@extends('layouts.customer.index')
@section('title', 'Thông tin về website')
@section('content')
    <!-- main-container -->
    <div class="main-container col2-right-layout">
        <div class="main container">
            <div class="row">
                <section class="col-main col-sm-9 wow">
                    <div class="page-title">
                        <h1>Thông tin về cửa hàng</h1>
                    </div>
                    <div class="col-2 registered-users">
                        <div class="content">
                            <h4>Đồ án tốt nghiệp - Đại học xây dựng</h4>
                            <h5>Đề tài : Website thương mại điện tử</h5>
                            <h5>Sinh viên : Trần Văn Định - 1511661 - 61PM1</h5>
                            <h5>Giảng viên hướng dẫn : Phan Hữu Trung</h5>
                        </div>
                    </div>
                </section>
                @include('components.customer.sidebar.info')
            </div>
        </div>
    </div>
    <!--End main-container -->

@endsection
