@extends('FrontEnd::layouts.home')
@section('title') {!! \Lib::siteTitle($site_title, $def['site_title'],false,true) !!} @stop
@section('content')
    <main>
        <div class="breadcrumb-block">
            {!! \Lib::renderBreadcrumb(false, true,'FrontEnd::layouts.breadcrumb') !!}
        </div>
        <div class="container cart-page">
            <h2>Giỏ hàng</h2>
            <div class="cart-step-3">
                <div class="tab-step">
                    <span class="tab-step-item active">THÔNG TIN CÁ NHÂN</span>
                    <span class="tab-step-item active">Thanh toán</span>
                    <span class="tab-step-item">Hoàn thành</span>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-6">
                        {!! Form::open(['url' => route('cart_complete.post'), 'files' => true,'id' => 'complete-form']) !!}
                        @if( count($errors) > 0)
                            <div class="alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <div>{!! $error !!}</div>
                                @endforeach
                            </div>
                        @endif
                        <div class="cart-pay">
                            <div class="cart-address-info">
                                <div class="address-info-top">
                                    <h4>Địa chỉ nhận hàng</h4>
                                    <a href="{{route('cart_infor')}}" >Thay đổi địa chỉ</a>
                                </div>
                                <div class="address-info-content">
                                    <p><b>Họ và tên:</b> {{@$cus_infor['fullname']}}</p>
                                    <p><b>Số điện thoại:</b> {{@$cus_infor['phone']}}</p>
                                    <p><b>Địa chỉ nhận hàng:</b> {{@$cus_infor['address']}}, {{$district->getType().' '.$district->Name_VI}}, {{$province->Name_VI}}</p>
                                    <p><b>Ghi chú:</b> {{@$cus_infor['cus_note']}}</p>
                                </div>
                            </div>
                            <div class="cart-pay-method">
                                <h4 class="method-title">CHỌN HÌNH THỨC THANH TOÁN</h4>
                                <hr>
                                <div class="method-item">
                                    <div class="icon-check">
                                        <input type="radio" value="0" name="payment_type" checked {{old('payment_type') == 0 ? 'checked' : ''}}>
                                        <span></span>
                                    </div>
                                    <div class="method-item-content">
                                        <b class="payment_title">Thanh toán tại nhà (C.O.D)</b>
                                        <p>Giao hàng tận nơi, xem hàng tại chỗ, không thích có thể đổi trả lập tức cho nhân viên giao hàng</p>
                                    </div>
                                </div>
                                <div class="method-item">
                                    <div class="icon-check">
                                        <input type="radio" value="1" name="payment_type" {{old('payment_type') == 1 ? 'checked' : ''}}>
                                        <span></span>
                                    </div>
                                    <div class="method-item-content">
                                        <b class="payment_title">Thanh toán qua BizPay</b>
                                        <p>Wepay mang tới cho anh/chị kênh thanh toán đầy đủ nhất, thuận tiện nhất.</p>
                                    </div>
                                </div>
                                <hr>
                                <span class="pay-method-note des-bottom-banking">*Anh/chị đã chọn <span>Thanh toán tại nhà (C.O.D)</span></span>

                                <button class="btn btn-order mx-auto d-block rounded-0">THANH TOÁN</button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    @include('FrontEnd::pages.checkout.list_prds_checkout')
                </div>
            </div>
        </div>
    </main>
    <script>
        function selfDisable(ele) {
            $(ele).prop('disabled',true);
            $('#complete-form').submit();
        }
    </script>
@endsection