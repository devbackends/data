<div class="sidebar js-user-nav">
    <a href="#" class="sidebar__toggle js-toggle-user-nav">
        <i class="far fa-user-cog"></i>
    </a>
    <div class="close-wrapper"></div>
    <div class="customer-sidebar">
        <div class="account-details">
            <div class="customer-name">{{ strtoupper(substr(auth()->guard('web')->user()->first_name,0,1))}}</div>
            <div class="customer-name-text">{{ucfirst(auth()->guard('web')->user()->first_name)}}</div>
            <div class="customer-email">{{ auth()->guard('web')->user()->email }}</div>
        </div>
        <span class="paragraph bold">My account</span>
        <ul class="navigation">
            <li>
                <a href="/users/home">
                    <i class="far fa-user"></i>
                    <span>Dashboard</span>
                    <i class="fal fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a href="/users/api-keys">
                    <i class="far fa-receipt"></i>
                    <span>API keys</span>
                    <i class="fal fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a href="/products/price-changes">
                    <i class="far fa-receipt"></i>
                    <span>Notifications</span>
                    <i class="fal fa-angle-right"></i>
                </a>
            </li>
            <li>
                <a target="_blank" href="https://documenter.getpostman.com/view/17031655/UyrGCumZ">
                    <i class="far fa-receipt"></i>
                    <span>Developer Documentation</span>
                    <i class="fal fa-angle-right"></i>
                </a>
            </li>

            <li>
                <a href="/account">
                    <i class="far fa-receipt"></i>
                    <span>Account</span>
                    <i class="fal fa-angle-right"></i>
                </a>
            </li>
        </ul>
    </div>
</div>