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
                <div class="user-tab-main">
                    <div class="my-order main-wrap">
                        <h4 class="main-wrap-title">Danh sách đơn hàng của tôi</h4>
                        <div class="main-wrap-table">
                            <div class="wrap-table-thead">
                                <ul>
                                    <li><span>Mã đơn</span></li>
                                    <li><span>Sản phẩm</span></li>
                                    <li><span>Tổng đơn</span></li>
                                    <li><span>Tình trạng đơn hàng</span></li>
                                </ul>
                            </div>
                            @if (count($orders) > 0)
                            @foreach ($orders as $ord)
                            <ul class="wrap-table-item">
                                <li>
                                    <a href="{{route('orders.detail', ['id' => $ord['code']])}}" class="id_order">#{{$ord['code']}}</a>
                                </li>
                                <li>
                                    @foreach ($ord['items'] as $item)
                                    @if (count($ord['items']) > 1)
                                        <a href="{{route('product.detail',['safe_title' => \Illuminate\Support\Str::slug($item['name']), 'id' => $item['product_id']])}}" class="p-item">
                                            <span>{{$item['name']}}</span>
                                        </a>
                                    @else
                                        <a href="{{route('product.detail',['safe_title' => \Illuminate\Support\Str::slug($item['name']), 'id' => $item['product_id']])}}" class="p-item">
                                            <span>{{$item['name']}}</span>
                                        </a>
                                    @endif
                                    @endforeach
                                </li>
                                <li>
                                    <b class="price t">{{\Lib::priceFormatEdit($ord['total_price'] + $ord['fee_shipping'])['price']}} đ </b>
                                </li>
                                <li>
                                    <span class="status_2">
                                        @if ($ord['status_for_cus'] == 1)
                                            Đang đóng gói
                                        @elseif($ord['status_for_cus'] == 2)
                                            Đang giao hàng
                                        @elseif($ord['status_for_cus'] == 3)
                                            Giao hàng thành công
                                        @elseif($ord['status_for_cus'] == 0)
                                            Đặt hàng thành công
                                        @endif
                                    </span>
                                </li>
                            </ul>
                            @endforeach
                            @else
                            <ul class="wrap-table-item flex-column py-5">
                                <h4 class="text-center">Bạn chưa có đơn đặt hàng</h4>
                                <p>Hãy quay lại và chọn cho mình sản phẩm phù hợp nhé!</p>
                                <a href="{{url('/')}}" class="go-back btn btn-order">Quay lại mua sắm<i class="icon-next"></i></a>
                            </ul>
                                
                            @endif
                        </div>
                    </div>
                </div>
                <div class="row justify-content-center mt-3">
                    <div class="col-6">
                        {{$orders->render('FrontEnd::layouts.pagin')}}
                    </div>
                </div>
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