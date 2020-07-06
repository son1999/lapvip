<div class="col-12 col-lg-6 cart-info">
    <div class="order_product">
        <div class="header">
            <span class="order_product-title">Đơn hàng (<span>{{count($cart['details'])}}</span> sản phẩm)</span>
            <span class="change"><a href="">Thay đổi</a></span>
        </div>
        <div class="items">
            @foreach($cart['details'] as $item )
                <div class="item">
                    <a href="{{$item['link']}}" class="card-product">
                        <div class="thumbnail"><img src="{{$item['opt']['img']}}" alt=""></div>
                        <div class="info">
                            <div class="name"><span>{{$item['name']}}</span></div>
                            <div>
                                @foreach($item['opt']['meta'] as $meta)
                                    <span>{{$meta['filter_cate_title']}} : <b>{{$meta['filter_value']}}</b></span>
                                @endforeach
                            </div>
                            <span>{{$item['quan']}}</span>
                        </div>
                    </a>
                    @if(Auth::guard('customer')->check())
                        <div class="value"><span class="text-danger"> {{\Lib::priceFormatEdit($item['opt']['price_dl']*$item['quan'])['price']}} đ</span></div>
                    @else
                        <div class="value"><span class="text-danger"> {{\Lib::priceFormatEdit($item['price']*$item['quan'])['price']}} đ</span></div>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
    <div class="mt-4"></div>
    @if($done == 1)
    <div>
        <div class="input-group mb-3">
            <input type="text" class="form-control" name="coupon" id="coupon" value="{{@$coupon_code}}" placeholder="Nhập mã giảm giá" aria-label="Nhập mã giảm giá">
            <div class="input-group-append">
                <button class="btn btn-outline-secondary" type="button" onclick="apply_coupon()" id="button-addon2">Áp dụng</button>
            </div>
        </div>
    </div>
    @endif
    <div class="order-value">
        <div class="temp">
            <span class="des">Tổng số tiền tạm tính:</span>
            <span class="value text-danger">{{\Lib::priceFormatEdit($cart['total'])['price']}} đ</span>
        </div>
        @if($coupon_code != '')
            <div class="delivery">
                <span class="des">Mã giảm giá (<b>{{$coupon_code}}</b>):</span>
                <span class="value text-danger">-{{$dccoupon ? \Lib::priceFormatEdit($dccoupon)['price'] : 0}} đ</span>
            </div>
        @endif
        <div class="delivery">
            <span class="des">Phí vận chuyển cố định:</span>
            <span class="value text-danger">{{$cart['shipping_fee'] ? \Lib::priceFormatEdit($cart['shipping_fee'])['price'] : 0}} đ</span>
        </div>
        <div class="pay">
            <span class="des">Số tiền cần thanh toán:</span>
            <span class="value text-danger">{{\Lib::priceFormatEdit(isset($cart['grand_total']) ? $cart['grand_total'] : $cart['total']+$cart['shipping_fee'])['price']}} đ</span>
        </div>
    </div>
</div>