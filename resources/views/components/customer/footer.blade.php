<footer class="footer">
    <div class="brand-logo ">
        <div class="container">
            <div class="slider-items-products">
                <div id="brand-logo-slider" class="product-flexslider hidden-buttons">
                    <div class="slider-items slider-width-col6">
                        @foreach ($producers as $producer)
                            <!-- Item -->
                            <div class="item">
                                <a
                                    href="{{ route('info.advanced_search.result', ['producer_id_list' => [$producer->id]]) }}"><img
                                        style="width: 40%;height:auto;"
                                        src="{{ asset('/storage/images/producers/' . $producer->image) }}" alt="Image"></a>
                            </div>
                            <!-- End Item -->
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-middle container">
        <div class="col-md-3 col-sm-4">
            <div class="footer-logo"><a href="{{ URL::to('/') }}" title="Logo"><img
                        src="{{ url('images/footer-logo.png') }}" alt="logo"></a></div>
            <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus diam arcu. </p>
            <div class="payment-accept">
                <div><img src="{{ url('images/payment-1.png') }}" alt="payment"> <img
                        src="{{ url('images/payment-2.png') }}" alt="payment"> <img
                        src="{{ url('images/payment-3.png') }}" alt="payment"> <img
                        src="{{ url('images/payment-4.png') }}" alt="payment"></div>
            </div>
        </div>
        <div class="col-md-3 col-sm-4">
            <h4>Tư vấn</h4>
            <ul class="links">
                <li class="first"><a title="Your Account" href="{{ route('account.dashboard') }}">Tài khoản của bạn</a></li>
                <li><a title="Information" href="{{ route('account.info.index') }}">Thông tin</a></li>
                <li><a title="Addresses" href="{{ route('account.address.index') }}">Địa chỉ giao hàng</a></li>
                <li><a title="Addresses" href="{{ route('coupon') }}">Giảm giá</a></li>
                <li><a title="Orders History" href="{{ route('account.order.index') }}">Lịch sử giao dịch</a></li>
            </ul>
        </div>
        <div class="col-md-3 col-sm-4">
            <h4>Thông tin</h4>
            <ul class="links">
                <li class="first"><a href="{{ route('info.site_map.category') }}" title="Site Map">Phụ lục</a></li>
                <li><a href="{{ route('info.search_terms') }}" title="Search Terms">Thẻ tìm kiếm</a></li>
                <li><a href="{{ route('info.advanced_search.index') }}" title="Advanced Search">Tìm kiếm nâng cao</a>
                </li>
                <li><a href="{{ route('info.contact_us.index') }}" title="Contact Us">Liên hệ với chúng tôi</a></li>
                <li><a href="{{ route('info.about_us') }}" title="About Us">Thông tin cửa hàng</a></li>
            </ul>
        </div>
        <div class="col-md-3 col-sm-4">
            <h4>Liên hệ với chúng tôi tại</h4>
            <div class="contacts-info">
                <address>
                    <i class="add-icon">&nbsp;</i>số 72 đường Trần Đại Nghĩa, quận Hai Bà Trưng <br>
                    &nbsp;Hà Nội, Việt Nam
                </address>
                <div class="phone-footer"><i class="phone-icon">&nbsp;</i> +1 800 000 000</div>
                <div class="email-footer"><i class="email-icon">&nbsp;</i> <a href="#">stevenuniverse03@gmail.com</a>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-bottom container">
    </div>
</footer>
