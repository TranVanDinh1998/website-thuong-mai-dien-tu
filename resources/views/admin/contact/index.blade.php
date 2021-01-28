@extends('admin.layout')
@section('title', 'Contact management')
@section('content')
    <!--main content start-->
    <section id="main-content">
        <section class="wrapper">
            <div class="table-agile-info">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        Contact
                    </div>
                    <div class="row w3-res-tb">
                        <div class="col-sm-5 m-b-xs">
                            <span class="btn-group">
                                <a href="{{ route('admin.contact.index') }}" class="btn btn-sm btn-default"><i
                                        class="fa fa-refresh"></i> Refresh</a>
                                <a href="{{ route('admin.contact.recycle') }}" class="btn btn-sm btn-danger"><i
                                        class="fa fa-trash"></i> Recycle</a>
                            </span>
                        </div>
                        <div class="col-sm-2">
                        </div>
                        <div class="col-sm-5">
                        </div>
                    </div>
                    <form method="GET" action="{{ route('admin.contact.bulk_action') }}" enctype="multipart/form-data">
                        <div class="row w3-res-tb">
                            <div class="col-sm-5 m-b-xs">
                                <select id="bulk_action" name="bulk_action"
                                    class="input-sm form-control w-sm inline v-middle">
                                    <option>Bulk action</option>
                                    <option value="0">Read</option>
                                    <option value="1">Unread</option>
                                    <option value="2">Remove</option>
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
                                                            href="{{ route('admin.contact.index', ['sort_id' => 0, 'status' => $status, 'view' => $view]) }}">Inc</a>
                                                    </li>

                                                    <li @if ($sort_id == 1)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.contact.index', ['sort_id' => 1, 'status' => $status, 'view' => $view]) }}">Desc</a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Number</th>
                                        <th>Create at</th>
                                        <th>
                                            <div class="dropdown">
                                                <button class="btn dropdown-toggle" type="button"
                                                    data-toggle="dropdown">Status
                                                    <span class="caret"></span></button>
                                                <ul class="dropdown-menu">
                                                    <li @if ($status == null)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.contact.index', ['view' => $view]) }}">All</a>
                                                    </li>
                                                    <li @if ($status == 1)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.contact.index', ['sort_id' => $sort_id, 'status' => '1', 'view' => $view]) }}">Read</a>
                                                    </li>

                                                    <li @if ($status == 0 && $status != null)
                                                        class="active"
                                                        @endif><a
                                                            href="{{ route('admin.contact.index', ['sort_id' => $sort_id, 'status' => '0', 'view' => $view]) }}">Unread</a>
                                                    </li>

                                                </ul>
                                            </div>
                                        </th>
                                        <th colspan="2">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($contacts as $contact)
                                        <tr>
                                            <td>
                                                <label class="i-checks m-b-none">
                                                    <input type="checkbox" value="{{ $contact->id }}"
                                                        name="contact_id_list[]"><i></i>
                                                </label>
                                            </td>
                                            <td>
                                                {{ $contact->id }}
                                            </td>
                                            <td><span class="text-ellipsis">{{ $contact->name }}</span></td>
                                            <td><span class="text-ellipsis">{{ $contact->email }}</span></td>
                                            <td><span class="text-ellipsis">{{ $contact->number }}</span></td>
                                            <td><span class="text-ellipsis">{{$contact->create_date}}</span></td>
                                            <td>
                                                @if ($contact->is_read == 1)
                                                    <div class="alert alert-success" role="alert">
                                                        <strong>Read</strong>
                                                    </div>
                                                @else
                                                    <div class="alert alert-danger" role="alert">
                                                        <strong>Unread</strong>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($contact->is_read == 0)
                                                    <a onclick="return confirm('Are you sure?')"
                                                        href="{{ route('admin.contact.read', ['id' => $contact->id]) }}"
                                                        class="btn btn-success" title="Read">
                                                        <span class="glyphicon glyphicon-check"></span>
                                                    </a>
                                                @else
                                                    <a onclick="return confirm('Are you sure?')"
                                                        href="{{ route('admin.contact.unread', ['id' => $contact->id]) }}"
                                                        class="btn btn-warning" title="Unread">
                                                        <span class="glyphicon glyphicon-remove"></span>
                                                    </a>
                                                @endif
                                            </td>
                                            <td>
                                                <a class="btn btn-info"
                                                    href="{{ route('admin.contact.detail', ['id' => $contact->id]) }}">
                                                    <span class="glyphicon glyphicon-edit"></span>
                                                </a>
                                            </td>
                                            <td>
                                                <a onclick="return confirm('Are you sure?')"
                                                    href="{{ route('admin.contact.remove', ['id' => $contact->id]) }}"
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
                                            {{ $view }} of {{ $count_contact }} item(s)
                                            <span class="caret"></span></button>
                                        <ul class="dropdown-menu">
                                            <li @if ($view == 10)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.contact.index', ['sort_id' => $sort_id, 'status' => $status, 'view' => 10]) }}">10</a>
                                            </li>
                                            <li @if ($view == 15)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.contact.index', ['sort_id' => $sort_id, 'status' => $status, 'view' => 15]) }}">15</a>
                                            </li>
                                            <li @if ($view == 20)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.contact.index', ['sort_id' => $sort_id, 'status' => $status, 'view' => 20]) }}">20</a>
                                            </li>
                                            <li @if ($view == 30)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.contact.index', ['sort_id' => $sort_id, 'status' => $status, 'view' => 30]) }}">30</a>
                                            </li>
                                            <li @if ($view == 40)
                                                class="active"
                                                @endif><a
                                                    href="{{ route('admin.contact.index', ['sort_id' => $sort_id, 'status' => $status, 'view' => 40]) }}">40</a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="col-sm-7 text-right text-center-xs">
                                    <ul class="pagination pagination-sm m-t-none m-b-none">
                                        {!! $contacts->links() !!}
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
