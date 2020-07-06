{{--<div class="check-out-head">--}}
    {{--<div class="wrap-head">--}}
        {{--<a href="{{route('cart')}}" class="@if($done >= 1) current_check @endif">{{ __('site.thongtindonhang') }}</a>--}}
        {{--<i class="fa fa-angle-right" aria-hidden="true" ></i>--}}
        {{--<a href="{{route('cart_infor')}}" class="@if($done >= 2) current_check @endif">{{ __('site.thongtingiaohang') }}</a>--}}
        {{--<i class="fa fa-angle-right" aria-hidden="true"></i>--}}
        {{--<a href="{{route('cart_complete')}}" class="@if($done >= 3) current_check @endif">{{ __('site.hoantat') }}</a>--}}
    {{--</div>--}}
{{--</div>--}}

<div class="w-100 item-steps flex_center ">
    <div class="veg_items d-flex flex-column flex_center {{ $done == 1 ? 'active' : ''  }}">
        <span class="i-check"></span>
        <div>THÔNG TIN GIAO HÀNG</div>
    </div>
    <div class="veg_items d-flex flex-column flex_center {{ $done == 2 ? 'active' : ''  }}">
        <span class="i-check"></span>
        <div>THÔNG TIN THANH TOÁN</div>
    </div>
    <div class="veg_items d-flex flex-column flex_center {{ $done == 3 ? 'active' : ''  }}">
        <span class="i-check"></span>
        <div>HOÀN THÀNH</div>
    </div>
</div>