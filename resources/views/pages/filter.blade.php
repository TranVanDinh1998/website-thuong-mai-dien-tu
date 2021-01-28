@extends('layout')
@section('title', 'Filter - Electronical Store')
@section('content')
    <!-- breadcrumbs -->
    <div class="breadcrumbs">
        <div class="container">
            <div class="row">
                <ul>
                    <li class="home"> <a href="{{ URL::to('/') }}" title="Go to Home Page">Home</a><span>&mdash;›</span>
                    </li>
                    <li class=""> <a href="{{ URL::to('filter/' . $current_category->id) }}"
                            title="Go to category {{ $current_category->name }}">{{ $current_category->name }}</a><span>&mdash;›</span>
                    </li>
                    @if ($current_collection != null)
                        <li class="category13"><strong>{{ $current_collection->name }}</strong></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
    <!-- End breadcrumbs -->

    <!-- Two columns content -->
    <section class="main-container col2-left-layout">
        <div class="main container">
            <div class="row">
                <section class="col-main col-sm-9 col-sm-push-3 wow">
                    <div class="category-title">
                        @if ($current_collection != null)
                            <h1>{{ $current_category->name }} - <strong>{{ $current_collection->name }}</strong></h1>
                        @else
                            <h1>{{ $current_category->name }}</h1>
                        @endif
                    </div>
                    <!-- category banner -->
                    <div class="category-description std">
                        <div class="slider-items-products">
                            <div id="category-desc-slider" class="product-flexslider hidden-buttons">
                                <div class="slider-items slider-width-col1">
                                    @if ($current_collection != null)
                                        <!-- Item -->
                                        <div class="item"> <a href="#"><img class="img-responsive"
                                                    style="width:100%;height:200px; object-fit:cover;" alt="category-banner"
                                                    src="{{ url('uploads/categories-images/' . $current_category->id . '/' . $current_collection->image) }}"></a>
                                            <div class="cat-img-title cat-bg cat-box">
                                                {{-- <h2 class="cat-heading">New Fashion 2015
                                                </h2>
                                                --}}
                                                <p>{!! $current_collection->description !!}
                                                </p>
                                            </div>
                                        </div>
                                        <!-- End Item -->
                                    @endif

                                    <!-- Item -->
                                    <div class="item"> <a href="#x"><img class="img-responsive"
                                                style="width:100%;height:200px; object-fit:cover;" alt="category-banner"
                                                src="{{ url('uploads/categories-images/' . $current_category->id . '/' . $current_category->image) }}"></a>
                                    </div>
                                    <!-- End Item -->

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- category banner -->
                    <div class="category-products">
                        <div class="toolbar">
                            <div class="sorter">
                                <div class="view-mode">
                                    <button id="btn_filter_list" title="List" class="button button-list">List</button>
                                    <button id="btn_filter_grid" title="Grid" class="button button-grid">Grid</button>
                                </div>
                            </div>
                            <div id="sort-by">
                                <label class="left">Sort By: </label>
                                <ul>
                                    <li>
                                        <a
                                            href="{{ route('filter', ['category_id' => $current_category_id, 'collection_id' => $current_collection_id, 'sort' => 0, 'view' => $view,'producer_id'=>$producer_id,'price_from'=>$price_from,'price_to'=>$price_to]) }}">Position<span
                                                class="right-arrow"></span>
                                        </a>
                                        <ul>
                                            <li><a
                                                    href="{{ route('filter', ['category_id' => $current_category_id, 'collection_id' => $current_collection_id, 'sort' => 1, 'view' => $view,'producer_id'=>$producer_id,'price_from'=>$price_from,'price_to'=>$price_to]) }}">Name</a>
                                            </li>
                                            <li><a
                                                    href="{{ route('filter', ['category_id' => $current_category_id, 'collection_id' => $current_collection_id, 'sort' => 2, 'view' => $view,'producer_id'=>$producer_id,'price_from'=>$price_from,'price_to'=>$price_to]) }}">Price</a>
                                            </li>
                                            <li><a
                                                    href="{{ route('filter', ['category_id' => $current_category_id, 'collection_id' => $current_collection_id, 'sort' => 3, 'view' => $view,'producer_id'=>$producer_id,'price_from'=>$price_from,'price_to'=>$price_to]) }}">Rating</a>
                                            </li>
                                            <li><a
                                                    href="{{ route('filter', ['category_id' => $current_category_id, 'collection_id' => $current_collection_id, 'sort' => 4, 'view' => $view,'producer_id'=>$producer_id,'price_from'=>$price_from,'price_to'=>$price_to]) }}">Seller</a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                                <a class="button-asc left" href="#" title="Set Descending Direction"><span
                                        style="color:#999;font-size:11px;" class="glyphicon glyphicon-arrow-up"></span></a>
                            </div>
                            <div class="pager">
                                <div id="limiter">
                                    <label>View: </label>
                                    <ul>
                                        <li><a
                                                href="{{ route('filter', ['category_id' => $current_category_id, 'collection_id' => $current_collection_id, 'sort' => $sort, 'view' => 15,'producer_id'=>$producer_id,'price_from'=>$price_from,'price_to'=>$price_to]) }}">15<span
                                                    class="right-arrow"></span>
                                            </a>
                                            <ul>
                                                <li><a
                                                        href="{{ route('filter', ['category_id' => $current_category_id, 'collection_id' => $current_collection_id, 'sort' => $sort, 'view' => 20,'producer_id'=>$producer_id,'price_from'=>$price_from,'price_to'=>$price_to]) }}">20
                                                    </a>
                                                </li>
                                                <li><a
                                                        href="{{ route('filter', ['category_id' => $current_category_id, 'collection_id' => $current_collection_id, 'sort' => $sort, 'view' => 30,'producer_id'=>$producer_id,'price_from'=>$price_from,'price_to'=>$price_to]) }}">30
                                                    </a>
                                                </li>
                                                <li><a
                                                        href="{{ route('filter', ['category_id' => $current_category_id, 'collection_id' => $current_collection_id, 'sort' => $sort, 'view' => 35,'producer_id'=>$producer_id,'price_from'=>$price_from,'price_to'=>$price_to]) }}">35
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                    </ul>
                                </div>
                                <div class="pages">
                                    <ul class="pagination">
                                        {!! $products->withQueryString()->links() !!}
                                    </ul>
                                </div>
                            </div>
                        </div>
                        {{-- display product in grid view --}}
                        <div id="filter_grid">
                            @include('filter_grid');
                        </div>
                        {{-- end of grid view --}}

                        {{-- display product in list view --}}
                        <div id="filter_list" style="display: none;">
                            @include('filter_list');
                        </div>
                        {{-- end of list view --}}
                    </div>
                </section>
                {{-- start filter side bar --}}
                @include('filter_side_bar')
                {{-- end filter side bar --}}
            </div>
        </div>
    </section>
    <!-- End Two columns content -->
    <script>
        $(document).ready(function() {
            // $('#pages .nav').removeClass('sticky');
            $('#btn_filter_grid').click(function() {
                $('#filter_grid').show();
                $('#filter_list').hide();
                $('#btn_filter_grid').addClass('button-active');
                $('#btn_filter_list').removeClass('button-active');
            });
            $('#btn_filter_list').click(function() {
                $('#filter_list').show();
                $('#filter_grid').hide();
                $('#btn_filter_list').addClass('button-active');
                $('#btn_filter_grid').removeClass('button-active');
            });
        });

    </script>
@endsection
