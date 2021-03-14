{{-- Account side bar --}}
<aside class="col-right sidebar col-sm-3 wow">
    <div class="block block-account">
        <div class="block-title">Thông tin</div>
        <div class="block-content">
            <ul>
                <li><a href="{{ route('info.site_map.category') }}">Phụ lục</a></li>
                <li class="current"><a href="{{ route('info.search_terms') }}">Thẻ tìm kiếm</a></li>
                <li><a href="{{ route('info.advanced_search.index') }}">Tìm kiếm nâng cao</a></li>
                <li><a href="{{ route('info.contact_us.index') }}">Liên hệ với chúng tôi</a>
                </li>
                <li><a href="{{ route('info.about_us') }}">Thông tin về chúng tôi</a>
                </li>
            </ul>
        </div>
    </div>
</aside>
{{-- end of account side bar --}}
