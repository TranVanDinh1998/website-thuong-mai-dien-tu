@extends('admin.layout')
@section('title', 'Trash can')
@section('content')
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <ol class="breadcrumb">
                <li><a href="{{ URL::to('administrator/product') }}">Product management</a></li>
                <li class="active">Recycle</li>
            </ol>
            <div class="table-agile-info">
                <div class="panel panel-default">
                        <div class="panel-heading">
                            Products - trash can
                        </div>
                        <div class="row w3-res-tb">
                            <div class="col-sm-5 m-b-xs">
                                <span class="btn-group">
                                    <a href="{{route('admin.product.recycle')}}" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> Refresh</a>
                                </span>
                            </div>
                            <div class="col-sm-2">
                            </div>
                            <div class="col-sm-5">
                                <form enctype="multipart/form-data" method="GET"
                                action="{{ route('admin.product.recycle') }}">
                                    <div class="input-group">
                                        <input type="text" class="input-sm form-control" value="{{ $search }}" name="search"
                                        placeholder="Search">
                                        <span class="input-group-btn">
                                            <button type="submit" class="btn btn-sm btn-default">Search</button>
                                        </span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <form method="GET" action="{{ route('admin.product.bulk_action') }}" enctype="multipart/form-data">
                        <div class="row w3-res-tb">
                            <div class="col-sm-5 m-b-xs">
                                <select name="bulk_action" class="input-sm form-control w-sm inline v-middle">
                                    <option>Bulk action</option>
                                    <option value="4">Restore</option>
                                    <option value="5">Delete</option>
                                </select>
                                <button type="submit" class="btn btn-sm btn-default">Apply</button>
                            </div>
                            <div class="col-sm-4">
                            </div>
                            <div class="col-sm-3">
                            </div>
                        </div>
                        @if (session('success'))
                            <div id="success_msg" class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        @if (session('error'))
                            <div id="error_msg" class="alert alert-danger">
                                {!! session('error') !!}
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="cmxform table table-striped b-t b-light">
                                <thead>
                                    <tr>
                                        <th style="width:20px;">
                                            <label class="i-checks m-b-none">
                                                <input type="checkbox"><i></i>
                                            </label>
                                        </th>
                                        <th>
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">ID
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li @if ($sort_id == 0)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.product.recycle', ['search' => $search, 'sort_id' => 0, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => $view]) }}">Inc</a>
                                                    </li>

                                                    <li @if ($sort_id == 1)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.product.recycle', ['search' => $search, 'sort_id' => 1, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => $view]) }}">Desc</a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        {{-- <th>Date</th> --}}
                                        <th>
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button"
                                                    data-toggle="dropdown">Price
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li @if ($price_to == null && $price_from == null)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.product.recycle', ['search' => $search, 'sort_id' => $sort_id, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => $view]) }}">All</a>
                                                    </li>
                                                    <li @if ($price_to == 500000)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.product.recycle', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => 500000, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => $view]) }}">0
                                                            -
                                                            500000</a></li>
                                                    <li @if ($price_to == 1000000)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.product.recycle', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => 500000, 'price_to' => 1000000, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => $view]) }}">500000
                                                            - 1000000</a></li>
                                                    <li @if ($price_to == 2000000)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.product.recycle', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => 1000000, 'price_to' => 2000000, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => $view]) }}">1000000
                                                            - 2000000</a></li>
                                                    <li @if ($price_to == 5000000)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.product.recycle', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => 2000000, 'price_to' => 5000000, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => $view]) }}">2000000
                                                            - 5000000</a></li>
                                                    <li @if ($price_from == 5000000)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.product.recycle', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => 5000000, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => $view]) }}">500000
                                                            - above</a></li>

                                                </ul>
                                            </div>
                                            {{-- Price --}}
                                        </th>
                                        <th>Remaining</th>
                                        <th>
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button"
                                                    data-toggle="dropdown">Category
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li @if ($category_id == null)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.product.recycle', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'producer_id' => $producer_id, 'status' => $status, 'view' => $view]) }}">All</a>
                                                    </li>
                                                    @foreach ($categories as $category)
                                                        <li @if ($category_id == $category->id)
                                                            class="active"
                                                    @endif
                                                    ><a
                                                        href="{{ route('admin.product.recycle', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category->id, 'producer_id' => $producer_id, 'status' => $status, 'view' => $view]) }}">{{ $category->name }}</a>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button"
                                                    data-toggle="dropdown">Producer
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li @if ($producer_id == null)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.product.recycle', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'status' => $status, 'view' => $view]) }}">All</a>
                                                    </li>
                                                    @foreach ($producers as $producer)
                                                        <li @if ($producer_id == $producer->id)
                                                            class="active"
                                                    @endif
                                                    ><a
                                                        href="{{ route('admin.product.recycle', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer->id, 'status' => $status, 'view' => $view]) }}">{{ $producer->name }}</a>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </th>
                                        <th>
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button"
                                                    data-toggle="dropdown">Status
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li @if ($status == null)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.product.recycle', ['search' => $search, 'sort_id' => 0, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer_id, 'view' => $view]) }}">All</a>
                                                    </li>
                                                    <li @if ($status == 1)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.product.recycle', ['search' => $search, 'sort_id' => 0, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => '1', 'view' => $view]) }}">Active</a>
                                                    </li>

                                                    <li @if ($status == 0 && $status !=null)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.product.recycle', ['search' => $search, 'sort_id' => 1, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => '0', 'view' => $view]) }}">Inactive</a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </th>
                                        <th colspan="4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        <tr>
                                            <td>
                                                <label class="i-checks m-b-none">
                                                    <input type="checkbox" value="{{ $product->id }}"
                                                        name="product_id_list[]"><i></i>
                                                </label>
                                            </td>
                                            <td>
                                                {{ $product->id }}
                                            </td>
                                            <td>
                                                <img style="width: 70px; height : auto"
                                                    src="{{ url('uploads/products-images/' . $product->id . '/' . $product->image) }}"
                                                    alt="{{ $product->name }}">
                                            </td>
                                            <td><span class="text-ellipsis">{{ $product->name }}</span></td>
                                            <td><span class="text-ellipsis">{{ $product->price }}</span></td>
                                            <td><span class="text-ellipsis">{{ $product->remaining }}</span></td>
                                            <td>
                                                @foreach ($categories as $category)
                                                    @if ($category->id == $product->category_id)
                                                        {{ $category->name }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @foreach ($producers as $producer)
                                                    @if ($producer->id == $product->producer_id)
                                                        {{ $producer->name }}
                                                    @endif
                                                @endforeach
                                            </td>
                                            <td>
                                                @if ($product->is_actived == 1)
                                                    <div class="alert alert-success" role="alert">
                                                        <strong>Active</strong>
                                                    </div>
                                                @else
                                                    <div class="alert alert-danger" role="alert">
                                                        <strong>Deactive</strong>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <a onclick="return confirm('Are you sure?')"
                                                    href="{{ URL::to('administrator/product/restore/' . $product->id) }}"
                                                    class="btn btn-success"><span class="glyphicon glyphicon-check"></span>
                                                </a>
                                            </td>
                                            <td>
                                                <a onclick="return confirm('Are you sure?')"
                                                    href="{{ URL::to('administrator/product/delete/' . $product->id) }}"
                                                    class="btn btn-danger"><span class="glyphicon glyphicon-trash"></span>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <footer class="panel-footer">
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">View : {{$view}} of {{$count_product}} item(s)
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li @if ($view == 10)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.product.recycle', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => 10]) }}">10</a>
                                            </li>
                                            <li @if ($view == 15)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.product.recycle', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => 15]) }}">15</a>
                                            </li>
                                            <li @if ($view == 20)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.product.recycle', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => 20]) }}">20</a>
                                            </li>
                                            <li @if ($view == 30)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.product.recycle', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => 30]) }}">30</a>
                                            </li>
                                            <li @if ($view == 40)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.product.recycle', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => 40]) }}">40</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-7 text-right text-center-xs">
                                    <ul class="pagination pagination-sm m-t-none m-b-none">
                                        {!! $products->links() !!}
                                    </ul>
                                </div>
                            </div>
                        </footer>
                    </form>
                </div>
            </div>
        </section>
        <!-- footer -->
        @include('admin.footer')
        <!-- / footer -->
    </section>

    <!--main content end-->
    <script>
        $(document).ready(function() {
            $("#success_msg").fadeOut(10000);
            $("#error_msg").fadeOut(10000);
        });

    </script>
@endsection
