@extends('layouts.customer.index')
@section('title', 'Liên hệ với chúng tôi')
@section('content')
    <!-- main-container -->
    <div class="main-container col2-right-layout">
        <div class="main container">
            <div class="row">
                <section class="col-main col-sm-9 wow">
                    <div class="page-title">
                        <h1>Liên hệ với chúng tôi</h1>
                    </div>
                    <form action="{{route('info.contact_us.index')}}" method="post">
                        @csrf
                        <div class="static-contain">
                            <fieldset class="group-select">
                                <ul>
                                    <li id="billing-new-address-form">
                                        <fieldset>
                                            <ul>
                                                <li>
                                                    @if (count($errors) > 0)
                                                        @foreach ($errors->all() as $error)
                                                            <p class="alert alert-danger">{{ $error }}</p>
                                                        @endforeach
                                                    @endif
                                                    @if (session('error'))
                                                        <p class="alert-danger alert">{{ session('error') }}</p>
                                                    @endif
                                                    @if (session('success'))
                                                        <p class="alert-success alert">{{ session('success') }}</p>
                                                    @endif
                                                </li>
                                                <li>
                                                    <div class="customer-name">
                                                        <div class="input-box name-firstname">
                                                            <label for="name"><em class="required">*</em>Tên của bạn</label>
                                                            <br>
                                                            <input name="name" id="name" title="Name" @if ($user != null) value="{{ $user->name }}" @endif class="input-text required-entry"
                                                                type="text">
                                                        </div>
                                                        <div class="input-box name-firstname">
                                                            <label for="email"><em class="required">*</em>Email</label>
                                                            <br>
                                                            <input name="email" id="email" title="Email" @if ($user != null) value="{{ $user->email }}" @endif
                                                                class="input-text required-entry validate-email"
                                                                type="text">

                                                        </div>
                                                    </div>
                                                </li>
                                                <li>

                                                    <label for="telephone">Số điện thoại</label>
                                                    <br>
                                                    <input name="number" id="telephone" title="Telephone" value=""
                                                        class="input-text" type="text">

                                                </li>
                                                <li>

                                                    <label for="comment"><em class="required">*</em>Lời nhắn</label>
                                                    <br>
                                                    <textarea name="comment" id="comment" title="Comment"
                                                        class="required-entry input-text" cols="5" rows="3"></textarea>

                                                </li>
                                            </ul>
                                        </fieldset>
                                    </li>
                                    <p class="require"><em class="required">* </em>Bắt buộc</p>
                                    <input type="text" name="hideit" id="hideit" value="" style="display:none !important;">
                                    <div class="buttons-set">
                                        <button type="submit" title="Submit"
                                            class="button submit"><span><span>Gửi</span></span></button>
                                    </div>
                                </ul>
                            </fieldset>
                        </div>

                    </form>
                </section>
                @include('components.customer.sidebar.info')
            </div>
        </div>
    </div>
    <!--End main-container -->
@endsection
