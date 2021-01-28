<!--sidebar start-->
<aside>
    <div id="sidebar" class="nav-collapse">
        <!-- sidebar menu start-->
        <div class="leftside-navigation">
            <ul class="sidebar-menu" id="nav-accordion">
                <li>
                    <a class="active" href="{{ route('admin.index') }}">
                        <i class="fa fa-dashboard"></i>
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-book"></i>
                        <span>UI Elements</span>
                    </a>
                    <ul class="sub">
                        <li><a href="typography.html">Typography</a></li>
                        <li><a href="glyphicon.html">glyphicon</a></li>
                        <li><a href="grids.html">Grids</a></li>
                    </ul>
                </li>
                <li>
                    <a href="fontawesome.html">
                        <i class="fa fa-bullhorn"></i>
                        <span>Font awesome </span>
                    </a>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-th"></i>
                        <span>Data Tables</span>
                    </a>
                    <ul class="sub">
                        <li><a href="basic_table.html">Basic Table</a></li>
                        <li><a href="responsive_table.html">Responsive Table</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-tasks"></i>
                        <span>Form Components</span>
                    </a>
                    <ul class="sub">
                        <li><a href="form_component.html">Form Elements</a></li>
                        <li><a href="form_validation.html">Form Validation</a></li>
                        <li><a href="dropzone.html">Dropzone</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-envelope"></i>
                        <span>Mail </span>
                    </a>
                    <ul class="sub">
                        <li><a href="mail.html">Inbox</a></li>
                        <li><a href="mail_compose.html">Compose Mail</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class=" fa fa-bar-chart-o"></i>
                        <span>Charts</span>
                    </a>
                    <ul class="sub">
                        <li><a href="chartjs.html">Chart js</a></li>
                        <li><a href="flot_chart.html">Flot Charts</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class=" fa fa-bar-chart-o"></i>
                        <span>Maps</span>
                    </a>
                    <ul class="sub">
                        <li><a href="google_map.html">Google Map</a></li>
                        <li><a href="vector_map.html">Vector Map</a></li>
                    </ul>
                </li>
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-glass"></i>
                        <span>Extra</span>
                    </a>
                    <ul class="sub">
                        <li><a href="gallery.html">Gallery</a></li>
                        <li><a href="404.html">404 Error</a></li>
                        <li><a href="registration.html">Registration</a></li>
                    </ul>
                </li>
                <li>
                    <a href="login.html">
                        <i class="fa fa-user"></i>
                        <span>Login Page</span>
                    </a>
                </li> --}}

                <!--Product data -->
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-th"></i>
                        <span>Products</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{{ URL::to('/administrator/product') }}">Management</a></li>
                        <li><a href="{{ URL::to('/administrator/product/add') }}">Add</a></li>
                        {{-- <li><a
                                href="{{ URL::to('/administrator/product/import') }}">Import</a></li>
                        --}}
                        <li><a href="{{ URL::to('/administrator/product/recycle') }}">Recycle</a></li>
                    </ul>
                </li>
                <!--Category data -->
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-th"></i>
                        <span>Categories</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{{ URL::to('/administrator/category') }}">Management</a></li>
                        <li><a href="{{ URL::to('/administrator/category/add') }}">Add</a></li>
                        <li><a href="{{ URL::to('/administrator/category/recycle') }}">Recycle</a></li>
                    </ul>
                </li>
                <!--Producer data -->
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-th"></i>
                        <span>Producers</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{{ URL::to('/administrator/producer') }}">Management</a></li>
                        <li><a href="{{ URL::to('/administrator/producer/add') }}">Add</a></li>
                        <li><a href="{{ URL::to('/administrator/producer/recycle') }}">Recycle</a></li>
                    </ul>
                </li>
                <!--Contact data -->
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-th"></i>
                        <span>Contacts</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{{ URL::to('/administrator/contact') }}">Management</a></li>
                        <li><a href="{{ URL::to('/administrator/contact/recycle') }}">Recycle</a></li>
                    </ul>
                </li>
                <!--Coupon data -->
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-th"></i>
                        <span>Coupon</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{{ URL::to('/administrator/coupon') }}">Management</a></li>
                        <li><a href="{{ URL::to('/administrator/coupon/add') }}">Add</a></li>
                        <li><a href="{{ URL::to('/administrator/coupon/recycle') }}">Recycle</a></li>
                    </ul>
                </li>
                <!--Collection data -->
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-th"></i>
                        <span>Collections</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{{ URL::to('/administrator/collection') }}">Management</a></li>
                        <li><a href="{{ URL::to('/administrator/collection/add') }}">Add</a></li>
                        <li><a href="{{ URL::to('/administrator/collection/recycle') }}">Recycle</a></li>
                    </ul>
                </li>
                <!--Review data -->
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-th"></i>
                        <span>Reviews</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{{ URL::to('/administrator/review') }}">Management</a></li>
                        <li><a href="{{ URL::to('/administrator/review/recycle') }}">Recycle</a></li>
                    </ul>
                </li>
                <!--Order data -->
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-th"></i>
                        <span>Orders</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{{ URL::to('/administrator/order') }}">Management</a></li>
                        <li><a href="{{ URL::to('/administrator/order/history') }}">History</a></li>
                        <li><a href="{{ URL::to('/administrator/order/cancel') }}">Cancel order</a></li>
                        <li><a href="{{ URL::to('/administrator/order/recycle') }}">Recycle</a></li>
                    </ul>
                </li>
                <!--User data -->
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-th"></i>
                        <span>Users</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{{ URL::to('/administrator/user') }}">Management</a></li>
                        <li><a href="{{ URL::to('/administrator/user/recycle') }}">Recycle</a></li>
                    </ul>
                </li>
                <!--End of user -->
                <!--Admin data -->
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-th"></i>
                        <span>Admins</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{{ route('admin.admin.index') }}">Management</a></li>
                        <li><a href="{{ URL::to('admin.admin.recycle') }}">Recycle</a></li>
                    </ul>
                </li>
                <!--Advertise data -->
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-th"></i>
                        <span>Advertises</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{{ URL::to('/administrator/advertise') }}">Management</a></li>
                        <li><a href="{{ URL::to('/administrator/advertise/add') }}">Add</a></li>
                        <li><a href="{{ URL::to('/administrator/advertise/recycle') }}">Recycle</a></li>
                    </ul>
                </li>
                <!--Tag data -->
                <li class="sub-menu">
                    <a href="javascript:;">
                        <i class="fa fa-th"></i>
                        <span>Tags</span>
                    </a>
                    <ul class="sub">
                        <li><a href="{{ URL::to('/administrator/tag') }}">Management</a></li>
                        <li><a href="{{ URL::to('/administrator/tag/add') }}">Add</a></li>
                        <li><a href="{{ URL::to('/administrator/tag/recycle') }}">Recycle</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- sidebar menu end-->
    </div>
</aside>
<!--sidebar end-->
