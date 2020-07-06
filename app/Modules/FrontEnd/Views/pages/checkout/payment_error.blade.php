@extends('FrontEnd::layouts.default')
@section('title') {!! \Lib::siteTitle($site_title, $def['site_title'],false,true) !!} @stop

@section('content')
    <main>
        @include('FrontEnd::pages.product.components.breadcrumb',['first_title' => $site_title])
        <div class="w-100 veg-main veg-list">
            <div class="container">
                <div class="row finis-thanhtoan">
                    <div class="col-md-6">
                        <div class="w-100 item-info info-left">
                            <div class="top d-flex flex-row align-items-center">
                                <div><img data-src="{{asset('html/html-vegfruit/images/cart/Banned-icon.png')}}" class="lazyload"/></div>
                                <div>
                                    <h5 class="rs">Thanh toán thất bại</h5>
                                    <span></span>
                                </div>
                            </div>
                            <div class="box-review-info w-100">
                                <p>Mã số đơn hàng : <b>{{$order->code}}</b></p>
                                <p>Thanh toán đơn hàng có lỗi xảy ra do: <b>{{$err}}</b></p>
                            </div>
                            <h6 class="title-review">Kiểm tra lại thông tin</h6>
                            <div class="box-info w-100">
                                <h5>ĐỊA CHỈ NHẬN HÀNG</h5>
                                <div><b>Họ và tên: </b> {{$order->fullname}}</div>
                                <div><b>Số điện thoại: </b>{{$order->phone}}</div>
                                <div><b>Địa chỉ nhận hàng: </b>{{ $order->address ? $order->address. ', ' : ''}} {{$order->province->Name_VI}} - {{$order->district->getType().' '.$order->district->Name_VI}}</div>
                                <div><b>Ghi chú: </b>{{$order->note}}</div>
                                <div class="title-center">HÌNH THỨC THANH TOÁN</div>
                                <p class="rs">{{$payment_types[$order->payment_type]}}</p>
                                @if(!empty($bank))
                                    <p>
                                        <span style="color:black;">{{__('site.thongtinchuyenkhoan')}}</span>: {{$bank->getInfor()}}
                                    </p>
                                @endif
                            </div>
                            <div class="d-flex flex_center">
                                <a href="{{route('home')}}" class="btn-thanhtoan">Tiếp tục mua sắm</a>
                            </div>
                        </div>

                    </div>
                    <div class="col-md-6">
                        <div class="w-100 item-info detail-product">
                            <div class="position-relative top-right">Đơn hàng <span>(3 sản phẩm)</span></div>
                            @foreach($order->items as $itm)
                                <div class="lst-item d-flex">
                                    <div class="img"><img data-src="{{$itm->product->getImageUrl('small')}}" class="lazyload"/></div>
                                    <div class="flex_1">
                                        <h5 class="rs">{{$itm->name}}</h5>
                                        <span>Khối lượng: {{$itm->product->kl->title ?? ''}}</span>
                                        <div class="num-st">x{{$itm->quantity}}</div>
                                    </div>
                                    <div class="price">{{Lib::price_format($itm->price*$itm->quantity,'')}}<small>đ</small></div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection