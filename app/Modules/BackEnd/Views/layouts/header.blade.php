<header class="app-header navbar">
    <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
        <span class="navbar-toggler-icon"></span>
    </button>
    <a class="navbar-brand" href="{{ route('admin.home') }}"></a>
    <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
        <span class="navbar-toggler-icon"></span>
    </button>
    {{--@if(!empty($menuTop))--}}
        {{--<ul class="nav navbar-nav d-md-down-none">--}}
            {{--@foreach ($menuTop as $menu)--}}
            {{--<li class="nav-item px-3">--}}
                {{--<a class="nav-link" href="{{ $menu['link']!='' ? $menu['link'] : 'javascript:void(0)' }}">{{ $menu['title'] }}</a>--}}
            {{--</li>--}}
            {{--@endforeach--}}
        {{--</ul>--}}
    {{--@endif--}}
    <ul class="nav navbar-nav ml-auto mr-2">
        {{--<li class="nav-item d-md-down-none">--}}
            {{--<a class="nav-link" href="#"><i class="icon-bell"></i><span class="badge badge-pill badge-danger">5</span></a>--}}
        {{--</li>--}}
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                <img src="{{ \Lib::get_gravatar(Auth::user()->email, 35) }}" class="img-avatar" alt="{{Auth::user()->email}}">
                <span class="d-md-down-none">{{ Auth::user()->fullname }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-header text-center">
                    <strong>Tài khoản</strong>
                </div>
                <a class="dropdown-item" href="javascript:void(0)" onclick="shop.admin.showChangePasswordForm()"><i class="fa fa-key"></i> Đổi mật khẩu</a>
                <a class="dropdown-item" href="{{ route("admin.user.profile") }}"><i class="fa fa-user"></i> Sửa thông tin cá nhân</a>
                <a class="dropdown-item" href="{{ route("logout") }}"><i class="fa fa-unlock-alt"></i> Đăng xuất</a>
            </div>
        </li>
    </ul>
</header>