@extends('FrontEnd::layouts.home')
@section('title') {!! \Lib::siteTitle($site_title, $def['site_title'],false,true) !!} @stop

@section('content')
    <main>
        <div class="breadcrumb-block">
            {!! \Lib::renderBreadcrumb(false, true,'FrontEnd::layouts.breadcrumb') !!}
        </div>
        <div class="container cart-page">
            <h2>Giỏ hàng</h2>
            <div class="cart-step-2">
                <div class="tab-step">
                    <span class="tab-step-item active">THÔNG TIN CÁ NHÂN</span>
                    <span class="tab-step-item">Thanh toán</span>
                    <span class="tab-step-item">Hoàn thành</span>
                </div>
                <div class="row">
                    <div class="col-12 col-lg-6">
                        <div class="cart-form border">
                            <div class="cart-form-top">
                                <h4>Nhập địa chỉ nhận hàng</h4>
                                <p>Vui lòng nhập địa chỉ nhận hàng</p>
                            </div>
                            @if( count($errors) > 0)
                                <div class="alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <div>{!! $error !!}</div>
                                    @endforeach
                                </div>
                            @endif
                            <div class="info-custommer">
                                {!! Form::open(['url' => route('cart.checkout.saveinfo'), 'files' => true,'id' => 'booking-complete-form']) !!}
                                <input type="hidden" value="{{@$coupon_code}}" name="coupon_code">
                                <div class="form-group">
                                    <label for="exampleInputEmail3">Họ và tên</label>
                                    <input type="text" name="fullname" value="{{ old('fullname',@$cus_infor['fullname'] ?? @$customer->fullname) }}" class="form-control"  id="exampleInputEmail3" placeholder="Nhập họ và tên">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail4">Số điện thoại</label>
                                    <input type="text" name="phone"  value="{{ old('phone',@$cus_infor['phone'] ?? @$customer->phone) }}" class="form-control"  id="exampleInputEmail4"  placeholder="Số điện thoại">
                                </div>
                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email</label>
                                    <input type="email"  name="email" value="{{ old('email',@$cus_infor['email'] ?? @$customer->email) }}" class="form-control"  id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Nhập email nhận thông tin đơn hàng">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlSelect1">Tỉnh/Thành phố</label>
                                    <select name="province" id="selectProvince" class="form-control" onchange="shop.get_district($(this).val())">
                                        <option value="">--{{__('site.chontinhthanh')}}--</option>
                                        @foreach($list_provinces as $it)
                                            <option @if(old('province',@$cus_infor['province']  ?? @$customer->province_id) == $it->id) selected @endif value="{{$it->id}}">{{$it->Name_VI}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlSelect2">Quận/Huyện</label>
                                    <select name="district" id="selectDistrict" class="form-control">
                                        <option value="">--{{__('site.chonquanhuyen')}}--</option>
                                        @foreach($list_districts as $it)
                                            <option @if(old('district',@$cus_infor['district']  ?? @$customer->district_id) == $it->id) selected @endif value="{{$it->id}}">{{$it->Name_VI}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="inputAddress">Địa chỉ</label>
                                    <input type="text" class="form-control" name="address" id="inputAddress" value="{{ old('address',@$cus_infor['address'] ?? @$customer->address) }}" placeholder="Vd: số nhà, số ngõ">
                                </div>
                                <div class="form-group">
                                    <label for="exampleFormControlTextarea1">Ghi chú thêm (không bắt buộc)</label>
                                    <textarea name="cus_note" id="cus_note2"  class="form-control" rows="4" placeholder="Nhập thêm thông tin ghi chú">{{ old('cus_note',@$cus_infor['cus_note']) }}</textarea>
                                </div>
                                <button class="btn btn-order mx-auto d-block rounded-0">ĐẶT MUA NGAY</button>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    @include('FrontEnd::pages.checkout.list_prds_checkout')
                </div>
            </div>
        </div>
    </main>
    <script>
        function selfDisable(ele) {
            $(ele).prop('disabled',true);
            $('#booking-complete-form').submit();
        }
    </script>
@endsection