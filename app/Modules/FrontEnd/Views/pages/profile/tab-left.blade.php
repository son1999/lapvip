
<div class="tab-left-content mb-5">
    <div class="head-tab-left">
        <a href="{{route('profile')}}">
            <img src="{{asset('html-tienmatnhanh/images/user.jpg')}}" alt="">
            <p>
                <span>Tài khoản</span>

                @if(\Auth::guard('customer')->check())
                    <b>{{ \Lib::str_limit(\Auth::guard('customer')->user()->fullname, 10) }}</b>
                @endif

            </p>
        </a>
    </div>
    @include('FrontEnd::pages.profile.menu')
</div>
