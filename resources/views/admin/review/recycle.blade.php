@extends('admin.layout')
@section('title', 'Trash can')
@section('content')
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.review.index')}}">Review management</a></li>
                <li class="active">Recycle</li>
            </ol>
            <div class="table-agile-info">
                <div class="panel panel-default">

                        <div class="panel-heading">
                            Reviews - Trash can
                        </div>
                        <div class="row w3-res-tb">
                            <div class="col-sm-5 m-b-xs">
                                <span class="btn-group">
                                    <a href="{{ route('admin.review.recycle') }}" class="btn btn-sm btn-default"><i
                                            class="fa fa-refresh"></i> Refresh</a>
                                </span>
                            </div>
                            <div class="col-sm-2">
                            </div>
                            <div class="col-sm-5">

                            </div>
                        </div>
                    <form method="GET" action="{{ route('admin.review.bulk_action') }}" enctype="multipart/form-data">

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
                                                            href="{{ route('admin.review.recycle', ['search' => $search, 'sort_id' => 0, 'status' => $status, 'view' => $view]) }}">Inc</a>
                                                    </li>
    
                                                    <li @if ($sort_id == 1)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.review.recycle', ['search' => $search, 'sort_id' => 1, 'status' => $status, 'view' => $view]) }}">Desc</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </th>
                                        <th>Image</th>
                                        <th>Product</th>
                                        <td>User</td>
                                        <th>Comment</th>
                                        <th>
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button"
                                                    data-toggle="dropdown">Status
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li @if ($status == null)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.review.recycle', ['search' => $search, 'sort_id' => 0, 'view' => $view]) }}">All</a>
                                                    </li>
                                                    <li @if ($status == 1)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.review.recycle', ['search' => $search, 'sort_id' => 0, 'status' => '1', 'view' => $view]) }}">Active</a>
                                                    </li>
    
                                                    <li @if ($status == 0 && $status !=null)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.review.recycle', ['search' => $search, 'sort_id' => 1, 'status' => '0', 'view' => $view]) }}">Inactive</a>
                                                    </li>
    
                                                </ul>
                                            </div>
                                        </th>
                                        <th colspan="2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($reviews as $review)
                                        <tr>
                                            <td>
                                                <label class="i-checks m-b-none">
                                                    <input type="checkbox" value="{{ $review->id }}"
                                                        name="review_id_list[]"><i></i>
                                                </label>
                                            </td>
                                            <td>
                                                {{ $review->id }}
                                            </td>
                                            @foreach ($products as $product)
                                                @if ($product->id == $review->product_id)
                                                    <td>
                                                        <img style="width: 70px; height : auto"
                                                            src="{{ url('uploads/products-images/' . $product->id . '/' . $product->image) }}"
                                                            alt="{{ $product->name }}">
                                                    </td>
                                                    <td><span class="text-ellipsis">{{ $product->name }}</span></td>
                                                @endif
                                            @endforeach
                                            @foreach ($users as $user)
                                                @if ($user->id == $review->user_id)
                                                    <td>
                                                        {{ $user->email }}
                                                    </td>
                                                @endif
                                            @endforeach

                                            <td>
                                                <span class="text-ellipsis">
                                                    {!! $review->description !!}

                                                </span>
                                            </td>
                                            <td>
                                                @if ($review->is_actived == 1)
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
                                                    href="{{ route('admin.review.restore',['id'=>$review->id]) }}"
                                                    class="btn btn-success"><span class="glyphicon glyphicon-check"></span>
                                                </a>
                                            </td>
                                            <td>
                                                <a onclick="return confirm('Are you sure?')"
                                                    href="{{ route('admin.review.delete',['id'=>$review->id]) }}"
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
                                        <button class="btn dropdown-toggle" type="button" data-toggle="dropdown">View : {{$view}} of {{$count_review}} item(s)
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li @if ($view == 10)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.review.recycle', ['search' => $search, 'status' => $status, 'view' => 10]) }}">10</a>
                                            </li>
                                            <li @if ($view == 15)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.review.recycle', ['search' => $search, 'status' => $status, 'view' => 15]) }}">15</a>
                                            </li>
                                            <li @if ($view == 20)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.review.recycle', ['search' => $search, 'status' => $status, 'view' => 20]) }}">20</a>
                                            </li>
                                            <li @if ($view == 30)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.review.recycle', ['search' => $search, 'status' => $status, 'view' => 30]) }}">30</a>
                                            </li>
                                            <li @if ($view == 40)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.review.recycle', ['search' => $search, 'status' => $status, 'view' => 40]) }}">40</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-7 text-right text-center-xs">
                                    <ul class="pagination pagination-sm m-t-none m-b-none">
                                        {!! $reviews->links() !!}
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
