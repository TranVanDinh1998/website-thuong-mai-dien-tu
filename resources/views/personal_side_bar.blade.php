{{-- Account side bar --}}
<aside class="col-right sidebar col-sm-3 wow">
    <div class="block block-account">
        <div class="block-title">My Account</div>
        <div class="block-content">
            <ul>
                <li class="current"><a href="{{ route('account.dashboard') }}">Account Dashboard</a></li>
                <li><a href="{{ route('account.info.index') }}">Account
                        Information</a></li>
                <li><a href="{{ route('account.address.index') }}">Address Book</a>
                </li>
                <li><a href="{{ route('account.order.index') }}">My Orders</a>
                </li>
                {{-- <li><a href="http://demo.magentomagik.com/computerstore/sales/billing_agreement/">Billing
                        Agreements</a></li>
                <li><a href="http://demo.magentomagik.com/computerstore/sales/recurring_profile/">Recurring
                        Profiles</a></li> --}}
                <li><a href="{{ URL::to('/account/review') }}">My Product
                        Reviews</a></li>
                {{-- <li><a href="http://demo.magentomagik.com/computerstore/tag/customer/">My Tags</a></li> --}}
                <li><a href="{{ URL::to('account/wish-list/') }}">My Wishlist</a></li>
                <li><a href="{{ URL::to('account/password/') }}">My Password</a></li>
                {{-- <li><a href="http://demo.magentomagik.com/computerstore/downloadable/customer/products/">My
                        Downloadable</a></li>
                <li class="last"><a href="http://demo.magentomagik.com/computerstore/newsletter/manage/">Newsletter
                        Subscriptions</a></li> --}}
            </ul>
        </div>
    </div>
    {{-- <div class="block block-compare">
        <div class="block-title "><span>Compare Products (2)</span></div>
        <div class="block-content">
            <ol id="compare-items">
                <li class="item odd">
                    <input type="hidden" value="2173" class="compare-item-id">
                    <a class="btn-remove1" title="Remove This Item" href="#"></a> <a href="#" class="product-name"> Sofa
                        with Box-Edge Polyester Wrapped Cushions</a>
                </li>
                <li class="item last even">
                    <input type="hidden" value="2174" class="compare-item-id">
                    <a class="btn-remove1" title="Remove This Item" href="#"></a> <a href="#" class="product-name"> Sofa
                        with Box-Edge Down-Blend Wrapped Cushions</a>
                </li>
            </ol>
            <div class="ajax-checkout">
                <button type="submit" title="Submit" class="button button-compare"><span>Compare</span></button>
                <button type="submit" title="Submit" class="button button-clear"><span>Clear</span></button>
            </div>
        </div>
    </div> --}}
</aside>
{{-- end of account side bar --}}
