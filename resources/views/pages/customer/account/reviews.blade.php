@extends('layouts.customer.index')
@section('title', 'Đánh giá')
@section('content')
    <div class="main-container col2-right-layout">
        <div class="main container">
            <div class="row">
                <section class="col-main col-sm-9 wow">
                    <div class="my-account">
                        <div class="page-title">
                            <h2>Các đánh giá sản phẩm của bạn</h2>
                        </div>
                        <div class="my-wishlist">
                            <div class="table-responsive">
                                <fieldset>
                                    <table id="wishlist-table" class="clean-table linearize-table data-table">
                                        <thead>
                                            <tr class="first last">
                                                <th class="customer-wishlist-item-image">Hình ảnh</th>
                                                <th class="customer-wishlist-item-info">Tên sản phẩm</th>
                                                <th class="customer-wishlist-item-info">Bình luận</th>
                                                <th class="customer-wishlist-item-info">Giá cả</th>
                                                <th class="customer-wishlist-item-info">Giá trị</th>
                                                <th class="customer-wishlist-item-info">Chất lượng</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($reviews as $review)
                                                <tr id="item_31" class="first odd">
                                                    <td class="wishlist-cell0 customer-wishlist-item-image"><a
                                                            title="{{ $product->name }}"
                                                            href="{{ route('product_details', ['id' => $review->product->id]) }}"
                                                            class="product-image">
                                                            <img width="150" alt="{{ $review->product->name }}"
                                                                src="{{ asset('storage/images/products/' . $review->product->image) }}">
                                                        </a>
                                                    </td>
                                                    <td class="wishlist-cell1 customer-wishlist-item-info">
                                                        <h3 class="product-name"><a title="{{ $product->name }}"
                                                                href="{{ route('product_details', ['id' => $review->product->id]) }}">{{ $review->product->name }}</a>
                                                        </h3>
                                                    </td>
                                                    <td class="wishlist-cell1 customer-wishlist-item-info">
                                                        {{ $review->description }}
                                                    </td>
                                                    <td class="wishlist-cell1 customer-wishlist-item-info">
                                                        <div class="rating-box">
                                                            <div style="width: {{ $review->price_rate * 20 }}%"
                                                                class="rating"></div>
                                                        </div>
                                                    </td>
                                                    <td class="wishlist-cell1 customer-wishlist-item-info">
                                                        <div class="rating-box">
                                                            <div style="width: {{ $review->value_rate * 20 }}%"
                                                                class="rating"></div>
                                                        </div>
                                                    </td>
                                                    <td class="wishlist-cell1 customer-wishlist-item-info">
                                                        <div class="rating-box">
                                                            <div style="width: {{ $review->quality_rate * 20 }}%"
                                                                class="rating"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="col-sm-7 text-right text-center-xs">
                                        <ul class="pagination pagination-sm m-t-none m-b-none">
                                            {!! $reviews->links() !!}
                                        </ul>
                                    </div>
                                </fieldset>
                            </div>
                        </div>
                    </div>
                </section>
                @include('components.customer.sidebar.account')
            </div>
        </div>
    </div>
@endsection
