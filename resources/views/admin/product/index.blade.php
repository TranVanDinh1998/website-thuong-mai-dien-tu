@extends('admin.layout')
@section('title', 'Products management')
@section('content')
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <div class="table-agile-info">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Products
                    </div>
                    <div class="row w3-res-tb">
                        <div class="col-sm-5 m-b-xs">
                            <span class="btn-group">
                                <a href="{{route('admin.product.index')}}" class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> Refresh</a>
                                <a href="{{route('admin.product.add')}}" class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Add</a>
                                <a href="{{route('admin.product.recycle')}}" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Recycle</a>
                            </span>
                        </div>
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-5">
                            <form enctype="multipart/form-data" method="GET"
                            action="{{ route('admin.product.index') }}">
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
                                <select id="bulk_action" name="bulk_action"
                                    class="input-sm form-control w-sm inline v-middle">
                                    <option>Bulk action</option>
                                    <option value="0">Deactivate</option>
                                    <option value="1">Activate</option>
                                    <option value="2">Remove</option>
                                    <option value="3">Import</option>
                                    <option value="6">Tag</option>
                                    <option value="7">Collection</option>
                                </select>
                                <button class="btn btn-sm btn-default">Apply</button>
                            </div>
                            <div class="col-sm-2">
                            </div>
                            <div class="col-sm-5">
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
                            <table class="table table-striped b-t b-light">
                                <thead>
                                    <tr>
                                        <th style="width:20px;">
                                            <label class="i-checks m-b-none">
                                                <input name="product_id_list[]" type="checkbox"><i></i>
                                            </label>
                                        </th>
                                        <th>
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">ID
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li @if ($sort_id == 0)
                                                        class="active"
                                                        @endif
                                                        ><a
                                                            href="{{ route('admin.product.index', ['search' => $search, 'sort_id' => 0, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => $view]) }}">Inc</a>
                                                    </li>

                                                    <li @if ($sort_id == 1)
                                                        class="active"
                                                        @endif
                                                        ><a
                                                            href="{{ route('admin.product.index', ['search' => $search, 'sort_id' => 1, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => $view]) }}">Desc</a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button"
                                                    data-toggle="dropdown">Price
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li @if ($price_to == null && $price_from == null)
                                                        class="active"
                                                        @endif
                                                        ><a
                                                            href="{{ route('admin.product.index', ['search' => $search, 'sort_id' => $sort_id, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => $view]) }}">All</a>
                                                    </li>
                                                    <li @if ($price_to == 500000)
                                                        class="active"
                                                        @endif
                                                        ><a
                                                            href="{{ route('admin.product.index', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => 500000, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => $view]) }}">0
                                                            -
                                                            500000</a></li>
                                                    <li @if ($price_to == 1000000)
                                                        class="active"
                                                        @endif
                                                        ><a
                                                            href="{{ route('admin.product.index', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => 500000, 'price_to' => 1000000, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => $view]) }}">500000
                                                            - 1000000</a></li>
                                                    <li @if ($price_to == 2000000)
                                                        class="active"
                                                        @endif
                                                        ><a
                                                            href="{{ route('admin.product.index', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => 1000000, 'price_to' => 2000000, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => $view]) }}">1000000
                                                            - 2000000</a></li>
                                                    <li @if ($price_to == 5000000)
                                                        class="active"
                                                        @endif
                                                        ><a
                                                            href="{{ route('admin.product.index', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => 2000000, 'price_to' => 5000000, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => $view]) }}">2000000
                                                            - 5000000</a></li>
                                                    <li @if ($price_from == 5000000)
                                                        class="active"
                                                        @endif
                                                        ><a
                                                            href="{{ route('admin.product.index', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => 5000000, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => $view]) }}">5000000
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
                                                        @endif
                                                        ><a
                                                            href="{{ route('admin.product.index', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'producer_id' => $producer_id, 'status' => $status, 'view' => $view]) }}">All</a>
                                                    </li>
                                                    @foreach ($categories as $category)
                                                        <li @if ($category_id == $category->id)
                                                            class="active"
                                                    @endif
                                                    ><a
                                                        href="{{ route('admin.product.index', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category->id, 'producer_id' => $producer_id, 'status' => $status, 'view' => $view]) }}">{{ $category->name }}</a>
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
                                                        @endif
                                                        ><a
                                                            href="{{ route('admin.product.index', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'status' => $status, 'view' => $view]) }}">All</a>
                                                    </li>
                                                    @foreach ($producers as $producer)
                                                        <li @if ($producer_id == $producer->id)
                                                            class="active"
                                                    @endif
                                                    ><a
                                                        href="{{ route('admin.product.index', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer->id, 'status' => $status, 'view' => $view]) }}">{{ $producer->name }}</a>
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
                                                        @endif
                                                        ><a
                                                            href="{{ route('admin.product.index', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer_id, 'view' => $view]) }}">All</a>
                                                    </li>
                                                    <li @if ($status == 1)
                                                        class="active"
                                                        @endif
                                                        ><a
                                                            href="{{ route('admin.product.index', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => '1', 'view' => $view]) }}">Active</a>
                                                    </li>
                                                    <li @if ($status == 0 && $status != null)
                                                        class="active"
                                                        @endif
                                                        ><a
                                                            href="{{ route('admin.product.index', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => '0', 'view' => $view]) }}">Inactive</a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </th>
                                        <th colspan="4">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="tbody-products">
                                    <div id="display-products">
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
                                                {{-- <td><span
                                                        class="text-ellipsis">{{ $product->create_date }}</span></td>
                                                --}}
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
                                                            <strong>Inactive</strong>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($product->is_actived == 0)
                                                        <a onclick="return confirm('Are you sure?')"
                                                            href="{{ URL::to('administrator/product/activate/' . $product->id) }}"
                                                            class="btn btn-success" title="Activate">
                                                            <span class="glyphicon glyphicon-check"></span>
                                                        </a>
                                                    @else
                                                        <a onclick="return confirm('Are you sure?')"
                                                            href="{{ URL::to('administrator/product/deactivate/' . $product->id) }}"
                                                            class="btn btn-warning" title="Deactivate">
                                                            <span class="glyphicon glyphicon-remove"></span>
                                                        </a>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a class="btn btn-info"
                                                        href="{{ URL::to('administrator/product/edit/' . $product->id) }}">
                                                        <span class="glyphicon glyphicon-edit"></span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a class="btn btn-default"
                                                        href="{{ URL::to('administrator/product/' . $product->id . '/gallery') }}">
                                                        <span class="glyphicon glyphicon-picture"></span>
                                                    </a>
                                                </td>
                                                <td>
                                                    <a onclick="return confirm('Are you sure?')"
                                                        href="{{ URL::to('administrator/product/remove/' . $product->id) }}"
                                                        class="btn btn-danger"><span
                                                            class="glyphicon glyphicon-trash"></span>
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </div>
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
                                                @endif
                                                ><a
                                                    href="{{ route('admin.product.index', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => 10]) }}">10</a>
                                            </li>
                                            <li @if ($view == 15)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.product.index', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => 15]) }}">15</a>
                                            </li>
                                            <li @if ($view == 20)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.product.index', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => 20]) }}">20</a>
                                            </li>
                                            <li @if ($view == 30)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.product.index', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => 30]) }}">30</a>
                                            </li>
                                            <li @if ($view == 40)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.product.index', ['search' => $search, 'sort_id' => $sort_id, 'price_from' => $price_from, 'price_to' => $price_to, 'category_id' => $category_id, 'producer_id' => $producer_id, 'status' => $status, 'view' => 40]) }}">40</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-7 text-right text-center-xs">
                                    <ul class="pagination pagination-sm m-t-none m-b-none">
                                        {!! $products->withQueryString()->links() !!}

                                    </ul>
                                </div>
                            </div>
                        </footer>
                        <!-- Modal tag-->
                        <div id="tag_modal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Add selected products to Tags</h4>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-striped b-t b-light">
                                            <thead>
                                                <tr>
                                                    <th style="width:20px;">
                                                        <label class="i-checks m-b-none">
                                                            <input type="checkbox"><i></i>
                                                        </label>
                                                    </th>
                                                    <th>ID</th>
                                                    <th>Name</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($tags as $tag)
                                                    <tr>
                                                        <td>
                                                            <label class="i-checks m-b-none">
                                                                <input type="checkbox" value="{{ $tag->id }}"
                                                                    name="tag_id_list[]"><i></i>
                                                            </label>
                                                        </td>
                                                        <td><span class="text-ellipsis">{{ $tag->id }}</span></td>
                                                        <td><span class="text-ellipsis">{{ $tag->name }}</span></td>
                                                        <td>
                                                            @if ($tag->is_actived == 1)
                                                                <strong>Active</strong>
                                                            @else
                                                                <strong>Inactive</strong>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-sm btn-default">Apply</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- end of Modal tag -->

                        <!-- Modal collection-->
                        <div id="collection_modal" class="modal fade" role="dialog">
                            <div class="modal-dialog">
                                <!-- Modal content-->
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        <h4 class="modal-title">Add selected products to collection</h4>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-striped b-t b-light">
                                            <thead>
                                                <tr>
                                                    <th style="width:20px;">
                                                        <label class="i-checks m-b-none">
                                                            <input type="checkbox"><i></i>
                                                        </label>
                                                    </th>
                                                    <th>ID</th>
                                                    <th>Image</th>
                                                    <th>Name</th>
                                                    <th>Description</th>
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($collections as $collection)
                                                    <tr>
                                                        <td>
                                                            <label class="i-checks m-b-none">
                                                                <input type="checkbox" name="collection_id_list[]"
                                                                    value="{{ $collection->id }}"><i></i>
                                                            </label>
                                                        </td>
                                                        <td>
                                                            {{ $collection->id }}
                                                        </td>
                                                        <td>
                                                            <img style="width: 70px; height : auto"
                                                                src="{{ url('uploads/categories-images/' . $collection->category_id . '/' . $collection->image) }}"
                                                                alt="{{ $collection->name }}">
                                                        </td>
                                                        <td><span class="text-ellipsis">{{ $collection->name }}</span></td>
                                                        <td><span class="text-ellipsis">{!! $collection->description
                                                                !!}</span></td>
                                                        <td>
                                                            @if ($collection->is_actived == 1)
                                                                <strong>Active</strong>
                                                            @else
                                                                <strong>Deactive</strong>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-sm btn-default">Apply</button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <!-- end of Modal collection -->
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

            $('#bulk_action').change(function() {
                if ($('#bulk_action').val() == 6) {
                    $("#tag_modal").modal("show");
                } else {
                    $("#tag_modal").modal("hide");
                }
                if ($('#bulk_action').val() == 7) {
                    $("#collection_modal").modal("show");
                } else {
                    $("#collection_modal").modal("hide");
                }
            });

        });

    </script>
@endsection
