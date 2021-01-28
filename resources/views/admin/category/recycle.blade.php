@extends('admin.layout')
@section('title', 'Trash can')
@section('content')
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.category.index')}}">Category management</a></li>
                <li class="active">Recycle</li>
            </ol>
            <div class="table-agile-info">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Categories - Trash can
                    </div>
                    <div class="row w3-res-tb">
                        <div class="col-sm-5 m-b-xs">
                            <span class="btn-group">
                                <a href="{{ route('admin.category.recycle') }}" class="btn btn-sm btn-default"><i
                                        class="fa fa-refresh"></i> Refresh</a>
                            </span>
                        </div>
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-5">
                            <form enctype="multipart/form-data" method="GET" action="{{ route('admin.category.recycle') }}">
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
                    <form method="GET" action="{{ route('admin.category.bulk_action') }}" enctype="multipart/form-data">
                        <div class="row w3-res-tb">
                            <div class="col-sm-5 m-b-xs">
                                <select name="bulk_action" class="input-sm form-control w-sm inline v-middle">
                                    <option>Bulk action</option>
                                    <option value="3">Restore</option>
                                    <option value="4">Delete</option>
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
                                                    <li @if ($sort_id == 0)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.category.recycle', ['sort_id' => 0, 'status' => $status, 'view' => $view]) }}">Inc</a>
                                                    </li>

                                                    <li @if ($sort_id == 1)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.category.recycle', ['sort_id' => 1, 'status' => $status, 'view' => $view]) }}">Desc</a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </th>
                                        <th>Image</th>
                                        <th>Name</th>
                                        <th>Description</th>
                                        <th>
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button"
                                                    data-toggle="dropdown">Status
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li @if ($status == null)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.category.recycle', ['sort_id' => $sort_id, 'view' => $view]) }}">All</a>
                                                    </li>
                                                    <li @if ($status == 1)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.category.recycle', ['sort_id' => $sort_id, 'status' => '1', 'view' => $view]) }}">Active</a>
                                                    </li>

                                                    <li @if ($status == 0 && $status != null)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.category.recycle', ['sort_id' => $sort_id, 'status' => '0', 'view' => $view]) }}">Inactive</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </th>
                                        <th colspan="2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>
                                                <label class="i-checks m-b-none">
                                                    <input type="checkbox" value="{{ $category->id }}"
                                                        name="category_id_list[]"><i></i>
                                                </label>
                                            </td>
                                            <td>
                                                {{ $category->id }}
                                            </td>
                                            <td>
                                                <img style="width: 70px; height : auto"
                                                    src="{{ url('uploads/categories-images/' . $category->id . '/' . $category->image) }}"
                                                    alt="{{ $category->name }}">
                                            </td>
                                            <td><span class="text-ellipsis">{{ $category->name }}</span></td>
                                            <td><span class="text-ellipsis">{!! $category->description !!}</span></td>
                                            <td>
                                                @if ($category->is_actived == 1)
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
                                                    href="{{ route('admin.category.restore',['id'=>$category->id]) }}"
                                                    class="btn btn-success"><span class="glyphicon glyphicon-check"></span>
                                                </a>
                                            </td>
                                            <td>
                                                <a onclick="return confirm('Are you sure?')"
                                                    href="{{ route('admin.category.delete',['id'=>$category->id]) }}"
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
                                    {{-- <small
                                        class="text-muted inline m-t-sm m-b-sm">showing <ul>
                                            of {{ $count_category }}
                                            items</small> --}}
                                    <div class="dropdown">
                                        <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">View : {{$view}} of {{$count_category}} item(s)
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li @if ($view == 10)
                                                class="active"
                                                @endif ><a
                                                    href="{{ route('admin.category.recycle', ['sort_id' => $sort_id, 'status' => $status, 'view' => 10]) }}">10</a>
                                            </li>
                                            <li @if ($view == 15)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.category.recycle', ['sort_id' => $sort_id, 'status' => $status, 'view' => 15]) }}">15</a>
                                            </li>
                                            <li @if ($view == 20)
                                                class="active"
                                                @endif ><a
                                                    href="{{ route('admin.category.recycle', ['sort_id' => $sort_id, 'status' => $status, 'view' => 20]) }}">20</a>
                                            </li>
                                            <li @if ($view == 30)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.category.recycle', ['sort_id' => $sort_id, 'status' => $status, 'view' => 30]) }}">30</a>
                                            </li>
                                            <li @if ($view == 40)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.category.recycle', ['sort_id' => $sort_id, 'status' => $status, 'view' => 40]) }}">40</a>
                                            </li>

                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-7 text-right text-center-xs">
                                    <ul class="pagination pagination-sm m-t-none m-b-none">
                                        {!! $categories->links() !!}
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
