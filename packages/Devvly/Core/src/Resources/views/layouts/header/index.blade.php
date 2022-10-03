<!-- HEADER TopBar -->
<nav id="top" class="header-topbar">
    <div class="row no-margin" style="padding: 20px 0px 15px 0px;">
        <div class="col-sm-6 ">
            <a href="/" class="brand-logo"></a>
        </div>
        <div class="col-sm-6 header-topbar__login">
            <div class="action float-right">
                @if(auth()->guard('web')->check())
                <a href="/users/logout" class="btn btn-link">LOGOUT</a>
                @endif
            </div>
        </div>
    </div>
</nav>
<!-- END HEADER -->