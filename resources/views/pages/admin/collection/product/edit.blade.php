@extends('layouts.admin.index')
@section('title', 'Sản phẩm trong bộ sưu tập')
@section('head')
    <script src="{{ url('admin/ckeditor/ckeditor.js') }}"></script>
@endsection
@section('content')
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.collection.index') }}">Bộ sưu tập</a></li>
                <li class="active">{{ $collection->name }}</li>
                <li><a href="{{ route('admin.collection.product.index',['id'=>$collection->id]) }}">Sản phẩm trong bộ sưu tập</a></li>
                <li class="active">Sản phẩm #{{$collectionProduct->id}}</li>
            </ol>
            <section class="panel">
                <div class="panel-heading">
                    Sản phẩm #{{ $collectionProduct->id }}
                </div>
                <div class="panel-body">
                    <div class="position-center">
                        <form  enctype="multipart/form-data"
                            action="{{ route('admin.collection.product.update',['id'=>$collection->id,'product_id'=>$collectionProduct->id]) }}" method="POST">
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
                            </div>
                            <div class="form-group">
                                <label for="advertise_id">Sản phẩm:</label>
                                <select class="form-control" name="product_id">
                                    @foreach ($products as $product)
                                        @if ($collectionProduct->product_id != $product->id)
                                            <option value='{{ $product->id }}'>{{ $product->id }} -
                                                {{ $product->name }}
                                            </option>
                                        @else
                                            <option value='{{ $product->id }}' selected>{{ $product->id }} -
                                                {{ $product->name }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <div class="col-sm-2 col-sm-hidden"></div>
                                <div class="col-sm-10">
                                    <button type="submit" class="btn btn-primary">Lưu</button>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </section>
        </section>
        <!-- footer -->
        @include('components.admin.footer')
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
        });

    </script>
@endsection
