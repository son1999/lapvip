<footer>
    <div class="container">
        <div class="row">
            <div class="d-flex d-lg-none order-2 col-12 text-center pb-2 mb-2 ft-view-more">
                <a href="javascript:;" class="show-info-mobile">Thông tin khác <i class="fa fa-angle-down" aria-hidden="true"></i></a>
                <a href="{{route('trangtinh', ['link_seo' => 'he-thong-cua-hang'])}}">Cửa hàng Lapvip <i class="fa fa-angle-down" aria-hidden="true"></i></a>
            </div>
            @foreach($menu_footer as $i_m_f )
                @if($i_m_f['title'] != 'Hướng dẫn mua trả góp')
                <div class="foot-col foot-col-1">
                    <ul class="fs-ftul">
{{--                        <li><a target="_blank" rel="nofollow" href="{{route('trangtinh', ['link_seo' => \Illuminate\Support\Str::slug($i_m_f['title'])])}}">{{$i_m_f['title']}}</a></li>--}}
                        @if(!empty($i_m_f['sub']))
                            @foreach($i_m_f['sub'] as $i_m_f_s)
                                <li><a href="{{route('trangtinh', ['link_seo' => \Illuminate\Support\Str::slug($i_m_f_s['title'])])}}" title="{{$i_m_f_s['title']}}">{{$i_m_f_s['title']}}</a></li>
                            @endforeach
                        @endif
                    </ul>
                </div>
                @endif
            @endforeach

            <div class="foot-col foot-col-2">
                <ul class="fs-ftr2 clearfix">
                    <li>
                        <p>Tư vấn mua hàng (Miễn phí)</p> <a href="tel:{{$def['hotline']}}" title="">{{$def['hotline']}}</a>
                    </li>
                    <li>
                        <p>Góp ý, khiếu nại dịch vụ ({{$def['res_open']}} - {{$def['res_close']}})</p> <a href="tel:{{$def['hotline']}}" title="">{{$def['hotline']}}</a>
                    </li>
                </ul>
                <div class="fs-ftrright">
                    <div class="fs-ftr1">
                        <p class="fs-ftrtit">Hỗ trợ thanh toán:</p>
                        <a class="fs-ftr-vs" href="javascript:;" title=""></a>
                        <a class="fs-ftr-mt" href="javascript:;" title=""></a>
                        <a class="fs-ftr-atm" href="javascript:;" title=""></a>
                    </div>
                    <div class="fs-ftr3">
                        <p class="fs-ftrtit">Chứng nhận:</p>
                        <ul>
                            <li>
                                {{--                            <a href='http://online.gov.vn/Home/WebDetails/61223'><img alt='' title='' src='http://online.gov.vn/Content/EndUser/LogoCCDVSaleNoti/logoSaleNoti.png'/></a>--}}
                                <a class="fs-ftr-cthuong" href="http://online.gov.vn/Home/WebDetails/61223" title=""></a>
                            </li>
                        </ul>
                    </div>
                </div>

            </div>

            <div class="d-block d-lg-none order-3">
                <p class="p-2 text-center">{{$def['address']}}</p>
            </div>
        </div>
    </div>
{{--    <div class="social-button">--}}
{{--        <div class="social-button-content" style="">--}}
{{--            <a href="tel:{{$def['hotline']}}" class="call-icon" rel="nofollow">--}}
{{--                <i class="fa fa-whatsapp" aria-hidden="true"></i>--}}
{{--                <div class="animated alo-circle"></div>--}}
{{--                <div class="animated alo-circle-fill  "></div>--}}
{{--                <span>Hotline: {{$def['hotline']}}</span>--}}
{{--            </a>--}}
{{--            <a href="sms:{{$def['hotline']}}" class="sms">--}}
{{--                <i class="fa fa-weixin" aria-hidden="true"></i>--}}
{{--                <span>SMS: {{$def['hotline']}}</span>--}}
{{--            </a>--}}
{{--            <a href="{{$def['facebook']}}" class="mes">--}}
{{--                <i class="fa fa-facebook-square" aria-hidden="true"></i>--}}
{{--                <span>Nhắn tin Facebook</span>--}}
{{--            </a>--}}
{{--            <a href="http://zalo.me/{{$def['hotline']}}" class="zalo">--}}
{{--                <i class="fa fa-commenting-o" aria-hidden="true"></i>--}}
{{--                <span>Zalo: {{$def['hotline']}}</span>--}}
{{--            </a>--}}
{{--        </div>--}}
{{--        <a class="user-support">--}}
{{--            <i class="fa fa-user-circle-o" aria-hidden="true"></i>--}}
{{--            <div class="animated alo-circle"></div>--}}
{{--            <div class="animated alo-circle-fill"></div>--}}
{{--        </a>--}}
{{--    </div>--}}
        {{--    {!! $def['script_box_zalo']!!}--}}
    <a href="javascript:;" class="back-to-top d-md-none"><i class="fa fa-arrow-up" aria-hidden="true"></i></a>
</footer>
<div class=" d-none d-lg-block order-3">
    <div class="container">
        <p class="p-2 text-center" style="font-size: 12px">{{$def['address']}}</p>
    </div>
</div>
