@extends('FrontEnd::layouts.home', ['bodyClass' => 'homepage'])

@section('title') {!! \Lib::siteTitle($site_title, $def['site_title']) !!} @stop

@section('content')
    <main>
        <div class="container mt-2">
            {!! \Lib::renderBreadcrumb(false, true,'FrontEnd::layouts.breadcrumb') !!}
            <div class="bg-white static-page">
                <div class="row">
                    <div class="col-12 col-md-4 d-none d-lg-block">
                        <div class="static-sidebar">
                            @foreach($menu_footer as $i_m_f )
                                <h4><i class="fa fa-bars" aria-hidden="true"></i> {{$i_m_f['title']}}</h4>
                                <ul class="my-2">
                                    @if(!empty($i_m_f['sub']))
                                        @foreach($i_m_f['sub'] as $i_m_f_s)
                                            <li><a href="{{route('trangtinh', ['link_seo' => \Illuminate\Support\Str::slug($i_m_f_s['title'])])}}" @if(str_slug($i_m_f_s['title']) == $data['alias']) class="active" @endif> {{$i_m_f_s['title']}}</a></li>
                                        @endforeach
                                    @endif
                                </ul>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-12 col-md-8">
                        <div class="static-content">
                            <h3>{{$data['title']}}</h3>
                            <div class="box-static-content">
                                @if(request()->link_seo != 'he-thong-cua-hang')
                                    <ul class="p-2">
                                        {!! $data['body'] !!}
                                    </ul>
                                @else
                                    <div class="mapouter"><div class="gmap_canvas"><iframe width="100%" height="500" id="gmap_canvas" src="{{$warehouse['map']}}" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a href="https://www.embedgooglemap.net/blog/elementor-pro-discount-code-review/">elementor review</a></div><style>.mapouter{position:relative;text-align:right;height:500px;width:100%;}.gmap_canvas {overflow:hidden;background:none!important;height:500px;width:100%;}</style></div>
                                    <div id="info_div"></div>
                                    <div class="list_store">
                                        <div class="stores py-4">
                                            <div class="loc"><span>{{$warehouse['title']}}</span></div>
                                        </div>

{{--                                        <p style="font-weight: 700; font-size: 16px;">Một số hình ảnh nhận dạng hệ thống cửa hàng Lapvip:</p>--}}
{{--                                        <div class="introduce">--}}
{{--                                            <div class="group-reg">--}}
{{--                                                <a class="fancybox" rel="group" href="./images/cuahang.jpg">--}}
{{--                                                    <div class="wrap-img">--}}
{{--                                                        <img src="./images/cuahang.jpg" alt="" />--}}
{{--                                                    </div>--}}
{{--                                                </a>--}}
{{--                                                <a class="fancybox" rel="group" href="./images/cuahang.jpg">--}}
{{--                                                    <div class="wrap-img">--}}
{{--                                                        <img src="./images/cuahang.jpg" alt="" />--}}
{{--                                                    </div>--}}
{{--                                                </a>--}}
{{--                                                <a class="fancybox" rel="group" href="./images/cuahang.jpg">--}}
{{--                                                    <div class="wrap-img">--}}
{{--                                                        <img src="./images/cuahang.jpg" alt="" />--}}
{{--                                                    </div>--}}
{{--                                                </a>--}}
{{--                                                <a class="fancybox" rel="group" href="./images/cuahang.jpg">--}}
{{--                                                    <div class="wrap-img">--}}
{{--                                                        <img src="./images/cuahang.jpg" alt="" />--}}
{{--                                                    </div>--}}
{{--                                                </a>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
                                    </div>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

@endsection

{{--@section('js_bot_all')--}}
{{--    <script type="text/javascript">--}}
{{--        function initMap() {--}}
{{--            var myMapCenter = {lat: 40.785091, lng: -73.968285};--}}

{{--            // Create a map object and specify the DOM element for display.--}}
{{--            var map = new google.maps.Map(document.getElementById('map'), {--}}
{{--                center: myMapCenter,--}}
{{--                zoom: 14--}}
{{--            });--}}


{{--            function markStore(storeInfo){--}}

{{--                // Create a marker and set its position.--}}
{{--                var marker = new google.maps.Marker({--}}
{{--                    map: map,--}}
{{--                    position: storeInfo.location,--}}
{{--                    title: storeInfo.name--}}
{{--                });--}}

{{--                // show store info when marker is clicked--}}
{{--                marker.addListener('click', function(){--}}
{{--                    showStoreInfo(storeInfo);--}}
{{--                });--}}
{{--            }--}}

{{--            // show store info in text box--}}
{{--            function showStoreInfo(storeInfo){--}}
{{--                var info_div = document.getElementById('info_div');--}}
{{--                info_div.innerHTML = 'Store name: '--}}
{{--                    + storeInfo.name--}}
{{--                    + '<br>Hours: ' + storeInfo.hours;--}}
{{--            }--}}

{{--            var stores = [--}}
{{--                {--}}
{{--                    name: 'Store 1',--}}
{{--                    location: {lat: 40.785091, lng: -73.968285},--}}
{{--                    hours: '8AM to 10PM'--}}
{{--                },--}}
{{--                {--}}
{{--                    name: 'Store 2',--}}
{{--                    location: {lat: 40.790091, lng: -73.968285},--}}
{{--                    hours: '9AM to 9PM'--}}
{{--                }--}}
{{--            ];--}}

{{--            stores.forEach(function(store){--}}
{{--                markStore(store);--}}
{{--            });--}}
{{--        }--}}

{{--    </script>--}}
{{--    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCjSi0T3S40gqcRxUQk47Y8Z-_rIwtHCN4&callback=initMap" async defer></script>--}}
{{--@endsection--}}