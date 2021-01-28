@extends('admin.layout')
@section('title', 'Tags management')
@section('content')
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <div class="table-agile-info">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Tags
                    </div>
                    <div class="row w3-res-tb">
                        <div class="col-sm-5 m-b-xs">
                            <span class="btn-group">
                                <a href="{{ route('admin.tag.index') }}" class="btn btn-sm btn-default"><i
                                        class="fa fa-refresh"></i> Refresh</a>
                                <a href="{{ route('admin.tag.add') }}" class="btn btn-sm btn-success"><i
                                        class="fa fa-plus"></i> Add</a>
                                <a href="{{ route('admin.tag.recycle') }}" class="btn btn-sm btn-danger"><i
                                        class="fa fa-trash"></i> Recycle</a>
                            </span>
                        </div>
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-5">
                            <form enctype="multipart/form-data" method="GET" action="{{ route('admin.tag.index') }}">
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
                    <form method="GET" action="{{ route('admin.tag.bulk_action') }}" enctype="multipart/form-data">

                        <div class="row w3-res-tb">
                            <div class="col-sm-5 m-b-xs">
                                <select id="bulk_action" name="bulk_action"
                                    class="input-sm form-control w-sm inline v-middle">
                                    <option>Bulk action</option>
                                    <option value="0">Deactivate</option>
                                    <option value="1">Activate</option>
                                    <option value="2">Remove</option>
                                    <option value="5">Add product</option>
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
                                                    <li><a
                                                            href="{{ route('admin.tag.index', ['sort_id' => 0, 'status' => $status, 'view' => $view]) }}">Inc</a>
                                                    </li>

                                                    <li><a
                                                            href="{{ route('admin.tag.index', ['sort_id' => 1, 'status' => $status, 'view' => $view]) }}">Desc</a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </th>
                                        <th>Name</th>
                                        <th>View</th>
                                        <th>
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button"
                                                    data-toggle="dropdown">Status
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="{{ route('admin.tag.index', ['view' => $view]) }}">All</a>
                                                    </li>
                                                    <li><a
                                                            href="{{ route('admin.tag.index', ['sort_id' => $sort_id, 'status' => '1', 'view' => $view]) }}">Active</a>
                                                    </li>

                                                    <li><a
                                                            href="{{ route('admin.tag.index', ['sort_id' => $sort_id, 'status' => '0', 'view' => $view]) }}">Inactive</a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </th>
                                        <th colspan="2">Action</th>
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
                                            <td><span class="text-ellipsis">{{ $tag->view }}</span></td>
                                            <td>
                                                @if ($tag->is_actived == 1)
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
                                                @if ($tag->is_actived == 0)
                                                    <a onclick="return confirm('Are you sure?')"
                                                        href="{{ route('admin.tag.activate', ['id' => $tag->id]) }}"
                                                        class="btn btn-success" title="Activate">
                                                        <span class="glyphicon glyphicon-check"></span>
                                                    </a>
                                                @else
                                                    <a onclick="return confirm('Are you sure?')"
                                                        href="{{ route('admin.tag.deactivate', ['id' => $tag->id]) }}"
                                                        class="btn btn-warning" title="Deactivate">
                                                        <span class="glyphicon glyphicon-remove"></span>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-info"
                                                    href="{{ route('admin.tag.edit', ['id' => $tag->id]) }}">
                                                    <span class="glyphicon glyphicon-edit"></span>
                                                </a>
                                            </td>
                                            <td>
                                                <a class="btn btn-primary"
                                                    href="{{ route('admin.tag.product.index', ['id' => $tag->id]) }}">
                                                    <span class="glyphicon glyphicon-search"></span>
                                                </a>
                                            </td>
                                            <td>
                                                <a onclick="return confirm('Are you sure?')"
                                                    href="{{ route('admin.tag.remove', ['id' => $tag->id]) }}"
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
                                        <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">View :
                                            {{ $view }} of {{ $count_tag }} item(s)
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li @if ($view == 10)
                                                class="active"
                                                @endif
                                                ><a
                                                    href="{{ route('admin.tag.index', ['search' => $search, 'sort_id' => $sort_id, 'status' => $status, 'view' => 10]) }}">10</a>
                                            </li>
                                            <li @if ($view == 15)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.tag.index', ['search' => $search, 'sort_id' => $sort_id, 'status' => $status, 'view' => 15]) }}">15</a>
                                            </li>
                                            <li @if ($view == 20)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.tag.index', ['search' => $search, 'sort_id' => $sort_id, 'status' => $status, 'view' => 20]) }}">20</a>
                                            </li>
                                            <li @if ($view == 30)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.tag.index', ['search' => $search, 'sort_id' => $sort_id, 'status' => $status, 'view' => 30]) }}">30</a>
                                            </li>
                                            <li @if ($view == 40)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.tag.index', ['search' => $search, 'sort_id' => $sort_id, 'status' => $status, 'view' => 40]) }}">40</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-7 text-right text-center-xs">
                                    <ul class="pagination pagination-sm m-t-none m-b-none">
                                        {!! $tags->withQueryString()->links() !!}

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
                                        <h4 class="modal-title">Add selected tag(s) to products</h4>
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
                                                    <th>Status</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($products as $product)
                                                    <tr>
                                                        <td>
                                                            <label class="i-checks m-b-none">
                                                                <input type="checkbox" name="product_id_list[]"
                                                                    value="{{ $product->id }}"><i></i>
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
                                                        <td>
                                                            @if ($product->is_actived == 1)
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
                        <!-- end of Modal tag -->
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
                if ($('#bulk_action').val() == 5) {
                    $("#tag_modal").modal("show");
                } else {
                    $("#tag_modal").modal("hide");
                }
            });
        });

    </script>
@endsection
