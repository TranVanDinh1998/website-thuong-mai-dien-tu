@extends('layouts.customer.index')
@section('title', 'Phụ lục trang web')
@section('content')
    <!-- main-container -->
    <div class="main-container col2-right-layout">
        <div class="main container">
            <div class="row">
                <section class="col-main col-sm-9 wow">
                    <div class="my-account">
                        <div class="page-title">
                            <h2>Danh sách thể loại</h2>
                        </div>
                        <div class="col-2 registered-users">
                            <div class="content">
                                <div id="sitemap_top_links">
                                    <ul class="links">
                                        <li class=" first last"><a href="{{ route('info.site_map.product') }}"
                                                title="Products Sitemap">Danh sách sản phẩm</a></li>
                                    </ul>
                                </div>
                                <div class="toolbar">
                                    <div id="sort-by">
                                        <label class="left">Sắp xếp theo: </label>
                                        <ul>
                                            <li>
                                                <a
                                                    href="{{ route('info.site_map.category', ['sort' => 0, 'view' => $view]) }}">ID<span
                                                        class="right-arrow"></span>
                                                </a>
                                                <ul>
                                                    <li><a
                                                            href="{{ route('info.site_map.category', ['sort' => 1, 'view' => $view]) }}">Tên</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                        <a class="button-asc left" href="#" title="Set Descending Direction"><span
                                                style="color:#999;font-size:11px;"
                                                class="glyphicon glyphicon-arrow-up"></span></a>
                                    </div>
                                    <div class="pager" style="float: left">
                                        <p class="amount"><strong>{{ $count_categories }} thể loại<strong></p>
                                        <div id="limiter">
                                            <label>Hiển thị: </label>
                                            <ul>
                                                <li><a
                                                        href="{{ route('info.site_map.category', ['sort' => $sort, 'view' => 15]) }}">15<span
                                                            class="right-arrow"></span>
                                                    </a>
                                                    <ul>
                                                        <li><a
                                                                href="{{ route('info.site_map.category', ['sort' => $sort, 'view' => 20]) }}">20
                                                            </a>
                                                        </li>
                                                        <li><a
                                                                href="{{ route('info.site_map.category', ['sort' => $sort, 'view' => 30]) }}">30
                                                            </a>
                                                        </li>
                                                        <li><a
                                                                href="{{ route('info.site_map.category', ['sort' => $sort, 'view' => 35]) }}">35
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="pages">
                                            <ul class="pagination">
                                                {!! $categories->withQueryString()->links() !!}
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="search-term">
                                    <ul class="categories-list">
                                        @foreach ($categories as $category)
                                            <li><a href="{{ route('filter', ['category_id' => $category->id]) }}">
                                                    <h4>{{ $category->name }}</h4>
                                                </a></li>
                                        @endforeach
                                    </ul>
                                </div>
                                <div class="toolbar">
                                    <div id="sort-by">
                                        <label class="left">Sắp xếp theo: </label>
                                        <ul>
                                            <li>
                                                <a
                                                    href="{{ route('info.site_map.category', ['sort' => 0, 'view' => $view]) }}">ID<span
                                                        class="right-arrow"></span>
                                                </a>
                                                <ul>
                                                    <li><a
                                                            href="{{ route('info.site_map.category', ['sort' => 1, 'view' => $view]) }}">Tên</a>
                                                    </li>
                                                </ul>
                                            </li>
                                        </ul>
                                        <a class="button-asc left" href="#" title="Set Descending Direction"><span
                                                style="color:#999;font-size:11px;"
                                                class="glyphicon glyphicon-arrow-up"></span></a>
                                    </div>
                                    <div class="pager" style="float: left">
                                        <p class="amount"><strong>{{ $count_categories }} thể loại<strong></p>
                                        <div id="limiter">
                                            <label>Hiển thị: </label>
                                            <ul>
                                                <li><a
                                                        href="{{ route('info.site_map.category', ['sort' => $sort, 'view' => 15]) }}">15<span
                                                            class="right-arrow"></span>
                                                    </a>
                                                    <ul>
                                                        <li><a
                                                                href="{{ route('info.site_map.category', ['sort' => $sort, 'view' => 20]) }}">20
                                                            </a>
                                                        </li>
                                                        <li><a
                                                                href="{{ route('info.site_map.category', ['sort' => $sort, 'view' => 30]) }}">30
                                                            </a>
                                                        </li>
                                                        <li><a
                                                                href="{{ route('info.site_map.category', ['sort' => $sort, 'view' => 35]) }}">35
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                @include('components.customer.sidebar.info')
            </div>
        </div>
    </div>
    <!--End main-container -->
@endsection
