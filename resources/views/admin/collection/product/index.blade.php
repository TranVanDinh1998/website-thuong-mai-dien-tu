@extends('admin.layout')
@section('title', 'Collection')
@section('head')
    <script src="{{ url('admin/ckeditor/ckeditor.js') }}"></script>
@endsection
@section('content')
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin.collection.index') }}">Collection management</a></li>
                <li class="active">{{ $collection->name }}</li>
                <li class="active">Products</li>
            </ol>
            <div class="table-agile-info">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Collection's products
                    </div>
                    <div class="row w3-res-tb">
                        <div class="col-sm-5 m-b-xs">
                            <span class="btn-group">
                                <a href="{{ route('admin.collection.product.index', ['id' => $collection->id]) }}"
                                    class="btn btn-sm btn-default"><i class="fa fa-refresh"></i> Refresh</a>
                                <a href="{{ route('admin.collection.product.add', ['id' => $collection->id]) }}"
                                    class="btn btn-sm btn-success"><i class="fa fa-plus"></i> Add</a>
                                <a href="{{ route('admin.collection.product.recycle', ['id' => $collection->id]) }}"
                                    class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Recycle</a>
                            </span>
                        </div>
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-5">
                        </div>
                    </div>
                    <form method="GET" action="{{ route('admin.collection.product.bulk_action', ['id' => $collection->id]) }}"
                        enctype="multipart/form-data">
                        <div class="row w3-res-tb">
                            <div class="col-sm-5 m-b-xs">
                                <select name="bulk_action" class="input-sm form-control w-sm inline v-middle">
                                    <option>Bulk action</option>
                                    <option value="0">Deactivate</option>
                                    <option value="1">Activate</option>
                                    <option value="2">Remove</option>
                                </select>
                                <button class="btn btn-sm btn-default">Apply</button>
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
                            <table class="table table-striped b-t b-light">
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
                                                        @endif
                                                        ><a
                                                            href="{{ route('admin.collection.product.index', ['id' => $collection->id, 'sort_id' => 0, 'status' => $status, 'view' => $view]) }}">Inc</a>
                                                    </li>

                                                    <li @if ($sort_id == 1)
                                                        class="active"
                                                        @endif
                                                        ><a
                                                            href="{{ route('admin.collection.product.index', ['id' => $collection->id, 'sort_id' => 1, 'status' => $status, 'view' => $view]) }}">Desc</a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </th>
                                        <th>Image</th>
                                        <th>Name</th>
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
                                                            href="{{ route('admin.collection.product.index', ['id' => $collection->id, 'sort_id' => $sort_id, 'view' => $view]) }}">All</a>
                                                    </li>
                                                    <li @if ($status == 1)
                                                        class="active"
                                                        @endif
                                                        ><a
                                                            href="{{ route('admin.collection.product.index', ['id' => $collection->id, 'sort_id' => $sort_id, 'status' => '1', 'view' => $view]) }}">Active</a>
                                                    </li>
                                                    <li @if ($status == 0 && $status != null)
                                                        class="active"
                                                        @endif
                                                        ><a
                                                            href="{{ route('admin.collection.product.index', ['id' => $collection->id, 'sort_id' => $sort_id, 'status' => '0', 'view' => $view]) }}">Inactive</a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </th>
                                        <th colspan="3">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($products as $product)
                                        @foreach ($collection_products as $collection_product)
                                            @if ($collection_product->product_id == $product->id)
                                                <tr>
                                                    <td>
                                                        <label class="i-checks m-b-none">
                                                            <input type="checkbox" value="{{ $collection_product->id }}"
                                                                name="collection_product_id_list[]"><i></i>
                                                        </label>
                                                    </td>
                                                    <td>
                                                        {{ $collection_product->id }}
                                                    </td>
                                                    <td>
                                                        <img style="width: 70px; height : auto"
                                                            src="{{ url('uploads/products-images/' . $product->id . '/' . $product->image) }}"
                                                            alt="{{ $product->name }}">
                                                    </td>
                                                    <td><span class="text-ellipsis">{{ $product->name }}</span></td>
                                                    <td>
                                                        @if ($collection_product->is_actived == 1)
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
                                                        @if ($collection_product->is_actived == 0)
                                                            <a onclick="return confirm('Are you sure?')"
                                                                href="{{ route('admin.collection.product.activate', ['id' => $collection->id, 'product_id' => $collection_product->id]) }}"
                                                                class="btn btn-success" title="Activate">
                                                                <span class="glyphicon glyphicon-check"></span>
                                                            </a>
                                                        @else
                                                            <a onclick="return confirm('Are you sure?')"
                                                                href="{{ route('admin.collection.product.deactivate', ['id' => $collection->id, 'product_id' => $collection_product->id]) }}"
                                                                class="btn btn-warning" title="Deactivate">
                                                                <span class="glyphicon glyphicon-remove"></span>
                                                            </a>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <a class="btn btn-info"
                                                            href="{{ route('admin.collection.product.edit', ['id' => $collection->id, 'product_id' => $collection_product->id]) }}">
                                                            <span class="glyphicon glyphicon-edit"></span>
                                                        </a>
                                                    </td>
                                                    <td>
                                                        <a onclick="return confirm('Are you sure?')"
                                                            href="{{ route('admin.collection.product.remove', ['id' => $collection->id, 'product_id' => $collection_product->id]) }}"
                                                            class="btn btn-danger"><span
                                                                class="glyphicon glyphicon-trash"></span>
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach

                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <footer class="panel-footer">
                            <div class="row">
                                <div class="col-sm-5">
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">View :
                                            {{ $view }}
                                            of {{ $count_collection_product }} item(s)
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li @if ($view == 10)
                                                class="active"
                                                @endif
                                                ><a
                                                    href="{{ route('admin.collection.product.index', ['id' => $collection->id, 'sort_id' => $sort_id, 'status' => $status, 'view' => 10]) }}">10</a>
                                            </li>
                                            <li @if ($view == 15)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.collection.product.index', ['id' => $collection->id, 'sort_id' => $sort_id, 'status' => $status, 'view' => 15]) }}">15</a>
                                            </li>
                                            <li @if ($view == 20)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.collection.product.index', ['id' => $collection->id, 'sort_id' => $sort_id, 'status' => $status, 'view' => 20]) }}">20</a>
                                            </li>
                                            <li @if ($view == 30)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.collection.product.index', ['id' => $collection->id, 'sort_id' => $sort_id, 'status' => $status, 'view' => 30]) }}">30</a>
                                            </li>
                                            <li @if ($view == 40)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.collection.product.index', ['id' => $collection->id, 'sort_id' => $sort_id, 'status' => $status, 'view' => 40]) }}">40</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-7 text-right text-center-xs">
                                    <ul class="pagination pagination-sm m-t-none m-b-none">
                                        {!!$collection_products->withQueryString()->links() !!}
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
