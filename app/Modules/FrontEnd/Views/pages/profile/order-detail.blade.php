@extends('FrontEnd::layouts.default')

@section('title') {!! \Lib::siteTitle($site_title, $def['site_title']) !!} @stop

@section('content')
<main>
    <!-- begin breadcrumb -->
    <div class="breadcrumb-block">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{route('profile')}}">Thông tin tài khoản</a></li>
                </ul>
            </nav>
        </div>
    </div>
    <!-- end breadcrumb -->
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-3 user-tab-left d-none d-md-block" style="margin-top: 35px">
                @include('FrontEnd::pages.profile.tab-left')
            </div>
            <div class="col-12 col-lg-9">
                @foreach ($details as $item)
                <div class="user-tab-main p-3 border rounded my-3">
                    <div class="d-flex align-items-center top-order">
                        <div class="d-none d-md-block"><a href="{{ route('orders')}}" class="text-dark pl-1 fs-18 pr-4"><i class="fa fa-arrow-left" aria-hidden="true"></i></a></div>
                        <div class="flex_1 _box-o">
                            <h5 class="rs">Chi tiết đơn hàng mã <span>#{{$item['code']}}</span></h5>
                            <div>Ngày đặt hàng: {{\App\Libs\Lib::dateFormat($item['created'], 'd/m/Y H:i')}}</div>
                        </div>
                        <!--<div class="">Trạng thái: <b class="sussess"> Giao hàng thành cồn</b></div>-->
                        <div class="">Trạng thái: <b class="pending"> @if($item['status_for_cus'] == 0) Đặt hàng thành công @elseif($item['status_for_cus'] == 1) Đang đóng gói @elseif($item['status_for_cus'] == 2) Đang vận chuyển @elseif($item['status_for_cus'] == 3) Giao hàng thành công @endif</b></div>
                    </div>
                    <div class="d-flex bar-progess flex-column">
                        <div class="bg-progess w-100"><span class="progess-percent progess-percent-{{$item['status_for_cus'] + 1}}"></span></div>
                        <div class="w-100 current-progess">
                            <div class=" item-progess"><span>Đặt hàng thành công</span></div>
                            <div class=" item-progess"><span>Đang đóng gói</span></div>
                            <div class=" item-progess"><span>Đang vận chuyển</span></div>
                            <div class=" item-progess"><span>Giao hàng thành công</span></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 p-bottom-15">
                            <div class="box-add">
                                <h5>Địa chỉ nhận hàng</h5>
                                <div class="content">
                                    <p><b>Họ và tên: </b>{{$item['fullname']}}</p>
                                    <p><b>Địa chỉ: </b>{{$item['address']}}</p>
                                    <p><b>Điện thoại: </b>{{$item['phone']}}</p>
                                    <p><b>Ghi chú: </b>Giao hàng giờ hành chính</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 ">
                            <div class="box-add">
                                <h5>Hình thức thanh toán</h5>
                                <p>@if($item['payment_type'] == 0) Thanh toán khi nhận hàng @elseif($item['payment_type'] == 1) Thanh toán online @elseif($item['payment_type'] == 2) Thanh toán trực tuyến qua ngân hàng Vpbank @endif</p>
                                <p>Trạng thái: <span class="sussess">Thành công</span></p>
                            </div>
                        </div>
                    </div>
            
                    <div class="list-cart w-100 table-responsive d-md-block d-none">
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Danh sách sản phẩm</th>
                                <th scope="col">Số lượng</th>
                                <th scope="col">Giá thành</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($item['items'] as $value)
                            <tr>
                                <td scope="row">
                                    <div class="box-1 d-flex flex-row">
                                        <div class="img flex_center"><img class="w-100" src="{{\ImageURL::getImageUrl($value['img'], \App\Models\Product::KEY, 'medium')}}"/></div>
                                        <div class="detail">
                                            <h4>{{$value['name']}}</h4>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="weight">
                                        x{{$value['quantity']}}
                                    </div>
                                </td>
                                <td>
                                    <div class="new-price">{{\Lib::priceFormatEdit($value['price'] * $value['quantity'])['price']}} đ </div>
                                </td>
                            </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
            
                    @foreach ($details as $item)
                    <!---giỏ hàng mobile-->
                    @foreach ($item['items'] as $value)
                    <div class="lst-cart-mb w-100 d-block d-md-none">
                        <h5>Danh sách sản phẩm <span>(Có {{count($item['items'])}} sản phẩm)</span></h5>
                        <div class="w-100 item-cart-bl1">
                            <div class="d-flex align-items-center">
                                <div class="img-thumb"><img src="{{$value['img']}}" class="w-100"></div>
                                <div class="flex_1">
                                    <div class="tensp"></div>
                                    <div class="new-price">{{\Lib::priceFormatEdit($value['price'] * $value['quantity'])['price']}} đ </div>
                                </div>
            
                            </div>
                            <div class="d-flex align-items-center">
                                <label>Số lượng</label>
                                <div class="weight flex_1">
                                        {{$value['quantity']}}
                                </div>
                            </div>
                        </div>
                    </div>
                    <!---giỏ hàng mobile-->
                    @endforeach
                    @endforeach
                    <div class="w-100  d-flex justify-content-end">
                        <div class="item-price-user col-md-5">
                            <div class="flex_center">
                                <span>Tổng số tiền tạm tính:</span>
                                <span class="d-flex flex_1 justify-content-end"><small>{{\Lib::priceFormatEdit($item['total_price'])['price']}} đ</small></span>
                            </div>
                            <div class="flex_center">
                                <span>Phí vận chuyển cố định:</span>
                                <span class="d-flex flex_1 justify-content-end price_trans"><small>{{\Lib::priceFormatEdit($item['fee_shipping'])['price']}} đ </small></span>
                            </div>
                            <div class="flex_center bottom">
                                <span>Số tiền cần thanh toán:</span>
                                <span class="d-flex flex_1 justify-content-end">{{\Lib::priceFormatEdit($item['total_price'] + $item['fee_shipping'])['price']}} đ </span>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</main>

@stop
@section('js_bot')
<script src="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.6/jquery.simplePagination.js"></script>
<script>
    var items = $(".main-wrap-table .wrap-table-item");
        var numItems = items.length;
        var perPage = 5;
    
        items.slice(perPage).hide().addClass('d-none');
    
        $('#pagination-container').pagination({
            items: numItems,
            itemsOnPage: perPage,
            prevText: "&laquo;",
            nextText: "&raquo;",
            onPageClick: function (pageNumber) {
                var showFrom = perPage * (pageNumber - 1);
                var showTo = showFrom + perPage;
                items.hide().addClass('d-none').slice(showFrom, showTo).show().removeClass('d-none');
            }
        });
</script>
@endsection