<div class="col-right sidebar col-sm-3">
    <div role="complementary" class="widget_wrapper13" id="secondary">
        <div class="popular-posts widget widget__sidebar wow" id="recent-posts-4">
            <h3 class="widget-title"><span>Most Popular Post</span></h3>
            <div class="widget-content">
                <ul class="posts-list unstyled clearfix">
                    {{-- <li>
                        <figure class="featured-thumb"> <a href="#/pellentesque-posuere"> <img width="80" height="53"
                                    alt="blog image" src="{{ url('images/blog-img1.jpg') }}"> </a> </figure>
                        <!--featured-thumb-->
                        <h4><a title="Pellentesque posuere" href="#/pellentesque-posuere">Pellentesque
                                posuere</a></h4>
                        <p class="post-meta"><i class="icon-calendar"></i>
                            <time datetime="2014-07-10T07:09:31+00:00" class="entry-date">Jul 10,
                                2014</time>
                            .
                        </p>
                    </li>
                    <li>
                        <figure class="featured-thumb"> <a href="#/dolor-lorem-ipsum"> <img width="80" height="53"
                                    alt="blog image" src="{{ url('images/blog-img2.jpg') }}"> </a> </figure>
                        <!--featured-thumb-->
                        <h4><a title="Dolor lorem ipsum" href="#/dolor-lorem-ipsum">Dolor lorem ipsum</a>
                        </h4>
                        <p class="post-meta"><i class="icon-calendar"></i>
                            <time datetime="2014-07-10T07:01:18+00:00" class="entry-date">Jul 10,
                                2014</time>
                            .
                        </p>
                    </li>
                    <li>
                        <figure class="featured-thumb"> <a href="#/aliquam-eget-sapien-placerat"> <img width="80"
                                    height="53" alt="blog image" src="{{ url('images/blog-img3.jpg') }}"> </a>
                        </figure>
                        <!--featured-thumb-->
                        <h4><a title="Aliquam eget sapien placerat" href="#/aliquam-eget-sapien-placerat">Aliquam eget
                                sapien placerat</a></h4>
                        <p class="post-meta"><i class="icon-calendar"></i>
                            <time datetime="2014-07-10T06:59:14+00:00" class="entry-date">Jul 10,
                                2014</time>
                            .
                        </p>
                    </li>
                    <li>
                        <figure class="featured-thumb"> <a href="#/pellentesque-habitant-morbi"> <img width="80"
                                    height="53" alt="blog image" src="{{ url('images/blog-img4.jpg') }}"> </a>
                        </figure>
                        <!--featured-thumb-->
                        <h4><a title="Pellentesque habitant morbi" href="#/pellentesque-habitant-morbi">Pellentesque
                                habitant morbi</a></h4>
                        <p class="post-meta"><i class="icon-calendar"></i>
                            <time datetime="2014-07-10T06:53:43+00:00" class="entry-date">Jul 10,
                                2014</time>
                            .
                        </p>
                    </li> --}}
                    @foreach ($most_view_coupons as $coupon)
                        <li>
                            <figure class="featured-thumb"> <a href="#"> <img width="80" height="53" alt="blog image"
                                        src="{{ url('uploads/coupons-images/'.$coupon->image) }}"> </a>
                            </figure>
                            <!--featured-thumb-->
                            <h4><a title="Pellentesque habitant morbi" href="">{{ $coupon->name }}</a></h4>
                            <p class="post-meta"><i class="icon-calendar"></i>
                                <time datetime="2014-07-10T06:53:43+00:00"
                                    class="entry-date">{{ $coupon->create_date }}</time>
                                .
                            </p>
                        </li>
                    @endforeach
                </ul>
            </div>
            <!--widget-content-->
        </div>
        <div class="popular-posts widget widget_categories wow" id="categories-2">
            <h3 class="widget-title"><span>Categories</span></h3>
            <ul>
                @foreach ($categories as $category)
                    <li class="cat-item cat-item-19599"><a href="">{{ $category->name }}</a></li>
                    <ul>
                    </ul>
                @endforeach
            </ul>
        </div>
        {{-- <!-- Banner Ad Block -->
        <div class="ad-spots widget widget__sidebar">
            <h3 class="widget-title"><span>Ad Spots</span></h3>
            <div class="widget-content"><a target="_self" href="#" title=""><img alt="offer banner"
                        src="{{ url('images/RHS-banner-img.jpg') }}"></a></div>
        </div>
        <!-- Banner Text Block -->
        <div class="text-widget widget widget__sidebar">
            <h3 class="widget-title"><span>Text Widget</span></h3>
            <div class="widget-content">Mauris at blandit erat. Nam vel tortor non quam scelerisque cursus.
                Praesent nunc vitae magna pellentesque auctor. Quisque id lectus.<br>
                <br>
                Massa, eget eleifend tellus. Proin nec ante leo ssim nunc sit amet velit malesuada pharetra.
                Nulla neque sapien, sollicitudin non ornare quis, malesuada.
            </div>
        </div> --}}
    </div>
</div>
