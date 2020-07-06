@extends('FrontEnd::layouts.home')
@section('title') {!! \Lib::siteTitle($site_title, $def['site_title'],false,true) !!} @stop
@section('content')
    <main>
        <div class="container">
            <div class="px-0">
                {!! \Lib::renderBreadcrumb(false, true,'FrontEnd::layouts.breadcrumb') !!}
            </div>
        </div>
        <div class="container banner_page_laptop">
            <div class="js-carousel" data-items="1" data-arrows="false">
                @foreach($slide as $i_slide)
                    <a href="{{$i_slide->link}}" class="banner_item">
                        <img data-src="{{$i_slide->getImageUrl('original')}}" class="lazyload" alt="">
                    </a>
                @endforeach
            </div>
        </div>
        <div class="cart-page cart-page-success container">
            <div class="bg-white">
                <div class="text-center">
                    <img data-src="{{asset('html-viettech/images/ic_success.png')}}" class="lazyload" alt="">
                    <h3>
                        cảm ơn quý khách đã mua hàng tại {{ env('APP_NAME') }}
                    </h3>
                    <p>
                        tổng đài viên {{ env('APP_NAME') }} sẽ liên hệ đến quý khách trong vòng 5 phút
                        Xin cảm ơn!!!
                    </p>
                </div>

                <div class="col-12 offset-lg-3 col-lg-7 mt-5 pt-5">
                    <div class="info-success">
                        <h5>thông tin đặt hàng :</h5>
                        <p><span>Mã đơn hàng :</span> <span>{{$order['code']}}</span></p>
                        <p><span>Hình thức thanh toán :</span> @foreach($method as $k => $methods) @if($k == $order['payment_type']) <span>{{$methods}}</span> @break @endif @endforeach</p>
                        <p><span>Họ tên khách hàng :</span> <span>{{$order['fullname']}}</span></p>
                        <p><span>Số điện thoại :</span><span>{{$order['phone']}}</span></p>
                        <p><span>Địa chỉ nhận hàng :</span><span>{{$order['address']}} - {{$order->district->Name_VI}} - {{$order->province->Name_VI}}</span></p>
                    </div>

                    <div class="info-success">
                        <h5>thông tin đơn hàng :</h5>
                        @foreach($order->items as $itm)
                            <p><span>{{ $itm->name }}</span> <span>{{ \Lib::priceFormatEdit($itm->price, '')['price']}}<sup class="text-danger">đ</sup></span></p>
                            @if($itm['opts'] != '')

                                    @php $metas = json_decode($itm['opts']) @endphp
                                    @foreach($metas as $meta)
                                    <p> <span>{{$meta->filter_cate_title}} :</span> <span>{{$meta->filter_value}}</span> </p>
                                    @endforeach
                            @endif
                            <p><span>Số lượng :</span> <span>{{ $itm->quantity }}</span></p>
                            @if(!empty($order['coupon_code']))
                                <p><span>Khuyến mãi :</span> <span> -{{ \Lib::priceFormatEdit($order['coupon_value'], '')['price']}}<sup class="text-danger">đ</sup></span></p>
                            @endif
                            <p><span>Tổng tiền:</span> <span>{{ \Lib::priceFormatEdit($order['total_price'], '')['price']}}<sup class="text-danger">đ</sup></span></p>
                        @endforeach
                        <p><span>Tổng tiền:</span> <span>{{ \Lib::priceFormat($order['total_price'], '') }}</span></p>
                    </div>

                    <a href="/" class="btn-cart-success">Mua thêm sản phẩm khác</a>
                </div>
            </div>
        </div>
    </main>
    <script>
        window.dataLayer = window.dataLayer || []
        dataLayer.push({
            'event': 'ectracking',
            'transactionId': '{{$order['code']}}',                           //id c?a don hàng, b?t bu?c
            'transactionAffiliation': '{{env('APP_NAME')}}',         //tên c?a hàng, không b?t bu?c
            'transactionTotal': {{$order['total_price']}},            //t?ng giá tr? don hàng, b?t bu?c
            'transactionTax': 0,            //thu?, không b?t bu?c
            'transactionShipping': 0,            //shipping, không b?t bu?c
            'transactionProducts': [
                @foreach($order->items as $itm)
                {            //s?n ph?m trên gi? hàng, không b?t bu?c
                    'sku': '{{$itm->product_id}}',              //id c?a s?n ph?m, b?t bu?c
                    'name': '{{$itm->name}}',            //tên s?n ph?m, b?t bu?c
                    'category': '{{$itm->product->category->title}}',            //danh m?c s?n ph?m, không b?t bu?c
                    'price': {{$itm->price}},              //giá m?i s?n ph?m, b?t bu?c
                    'quantity': {{$itm->quantity}}              //s? lu?ng s?n ph?m, b?t bu?c
                },
                @endforeach
            ]
        });
    </script>
@endsection