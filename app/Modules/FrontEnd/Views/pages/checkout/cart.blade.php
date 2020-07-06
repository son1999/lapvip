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
        <div class="cart-page container" id="app_cart">
            <div class="bg-white" >
                <div v-if="total_items > 0">
                    <div class="home-title-cate pr-2">
                        <h3 class="name">Giỏ hàng của bạn ( @{{total_items}} sản phẩm )</h3>
                    </div>
                    {!! Form::open(['url' => route('cart.checkout.saveinfo'), 'files' => true,'id' => 'booking-complete-form']) !!}
                            <div class="cart-list" v-for="(item, index) in cart_items">
                                <div class="cart-item">
                                    <div class="p-img">
                                        <img :src="item.opt.img" alt="">
                                    </div>
                                    <div class="p-info">
                                        <a v-bind:href="item.link" class="p-name">@{{ item.name }}</a>
                                        <div class="p-config d-flex align-items-center" v-for="(pros,indexs) in item.opt.meta">
                                            <p style="font-weight: bold; margin-top: 1px">@{{ pros.filter_cate_title }} :</p>
                                            <div class="color">
                                                <label>
                                                    <input :id="'color1'+index+indexs" type="radio" name="radio" />
                                                    <span class="filter-title" v-if="pros.filter_value.indexOf('#') == -1">@{{ pros.filter_value }}</span>
                                                    <span class="checkmark" v-if="pros.filter_value.indexOf('#') != -1" v-bind:style="{backgroundColor: pros.filter_value}"></span>
                                                </label>
                                            </div>
                                        </div>
                                        <div class="p-info">
                                            <a v-bind:href="item.link" class="p-del-cart" @click="remove($event,index,item)"><i class="fa fa-trash" aria-hidden="true"></i> Xóa</a>
                                        </div>
                                    </div>
                                    <div class="p-price-gr">
                                        @if(Auth::guard('customer')->check())
                                            <span class="p-price">@{{ formatPrice(item.opt.price_dl) }} đ</span>
                                        @else
                                            <span class="p-price">@{{ formatPrice(item.price) }} đ</span>
                                        @endif
                                    </div>
                                    <div class="p-quantity">
                                        <div>Số lượng :</div>
                                        <div class="action">
{{--                                            <button class="icon" @click="down_quan($event)">-</button>--}}
                                            <input type="number" name="qty" value="1" min="1" class="count" placeholder="01" @change="change_input(index,item,$event)" v-model="item.quan" data-old="" />
{{--                                            <button class="icon" @click="up_quan($event)">+</button>--}}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="block-cart-price" >
                                <div class="code-gr">
                                    @if($done == 1)
                                        <div class="discount-item">
                                            <b>Sử dụng mã giảm giá</b>
                                            <input  type="text" id="coupons" name="coupons_code" value="{{@$coupon_code}}" placeholder="Mã giảm giá">
                                            <input type="button" onclick="apply_coupon()" class="btn btn-primary" value="Áp dụng" >
                                        </div>
                                    @endif
                                </div>
                                <div class="price-total-block p-0">
                                    <div class="price-item">
                                        <span class="lable-price">Tạm tính :</span>
                                        <span class="price">@{{ formatPrice(provisional) }} đ</span>
                                    </div>
                                    <div class="price-item price-small" v-if="dccoupon">
                                        <span class="price-coupons-title">Khuyến mãi :</span>
                                        <span class="price-coupons">- @{{ formatPrice(dccoupon) }} đ</span>
                                    </div>
                                    <div class="price-item">
                                        <span class="lable-price">Tổng tiền : </span>
                                        <span class="price">@{{  formatPrice(grand_total) }} đ</span>
                                    </div>
                                </div>
                            </div>

                        <div class="pay-method">
                            <h6 class="cart-heading">Chọn hình thức thanh toán :</h6>
                            <div class="tab-control d-flex flex-column flex-lg-row justify-content-start align-items-center">
                                <a href="javascript:;" @click="getPay($event,'ATM')"  data-value="ATM" @if(old('pay_method') == 'ATM') class="active" @endif>Ck qua ngân hàng </a>
                                <span>Hoặc</span>
                                <a href="javascript:;" @click="getPay($event,'COD')"  data-value="COD" @if(old('pay_method') == 'COD') class="active" @endif>thanh toán COD </a>
                                <span>Hoặc</span>
                                <a href="javascript:;" @click="getPay($event,'OFF')"  data-value="OFF" @if(old('pay_method') == 'OFF') class="active" @endif>tại shop </a>
                                <input type="hidden" name="pay_method" id="pay_method" class="pay-method-value" value="">
                            </div>
                            @error('pay_method')
                            <i style="color:  red; float: right">{{$message}}</i>
                            @enderror
                        </div>
                        <div class="form-cart col-lg-8">
                            <h6 class="cart-heading">Thông tin Khách Hàng :</h6>
                            <input type="hidden" name="coupon_code" value="">
                            <div class="form-cart-item">
                                <span>Họ và tên :</span>
                                <div style="flex-grow: 1;">
                                    <input type="text" name="name" id="name" @error('name') class="is-invalid " @enderror placeholder="Nhập họ và tên" value="{{old('name')}}">
                                    @error('name')
                                    <i style="color:  red">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-cart-item">
                                <span>Số điện thoại :</span>
                                <div style="flex-grow: 1;">
                                    <input type="text" name="phone" id="phone" @error('phone') class="is-invalid " @enderror placeholder="Nhập Số điện thoại" value="{{old('phone')}}">
                                    @error('phone')
                                    <i style="color:  red">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-cart-item">
                                <span>Email : <i>(Không bắt buộc)</i></span>
                                <div style="flex-grow: 1;">
                                    <input type="text" name="email" id="email" @error('email') class="is-invalid " @enderror placeholder="Nhập email" value="{{old('email')}}">
                                    @error('email')
                                    <i style="color:  red">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-cart-item">
                                <span>Địa chỉ :</span>
                                <div style="flex-grow: 1;">
                                    <input type="text" name="address" id="address" @error('address') class="is-invalid " @enderror placeholder="Địa chỉ" value="{{old('address')}}">
                                    @error('address')
                                    <i style="color:  red">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-cart-item">
                                <span>Thành phố:</span>
                                <div style="flex-grow: 1;">
                                    <select name="provinces" id="provinces" @change="change_province($event)" class="form-control @error('provinces') is-invalid  @enderror">
                                        <option value="" selected disabled>Thành Phố</option>
                                        @foreach($pro as  $provin)
                                            <option  value="{!! $provin->id !!}" >{!! $provin->Name_VI !!}</option>
                                        @endforeach
                                    </select>
                                    @error('provinces')
                                    <i style="color:  red">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-cart-item">
                                <span>Quận, Huyện :</span>
                                <div style="flex-grow: 1;">
                                    <select name="districts" id="districts" class="form-control @error('districts') is-invalid  @enderror">
                                    </select>
                                    @error('districts')
                                    <i style="color:  red">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-cart-item">
                                <span></span>
                                <button type="submit" class="buy-btn">Đặt hàng</button>
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
                <div v-if="total_items <= 0" class="w-100 veg-main veg-list mt-5 mb-5">
                    <ul class="total-cart row flex-row-reverse">
                        <div class="container mt-5 mb-5">
                            <div class="row finis-thanhtoan justify-content-center">
                                <div class="m-auto text-center">
                                    <h4 class="text-center">Không có sản phẩm nào trong giỏ hàng của bạn</h4>
                                    <p>Hãy quay lại và chọn cho mình sản phẩm phù hợp nhé!</p>
                                    <button type="submit" href="" onclick="history.back();" class="go-back btn btn-primary">Quay lại mua sắm<i class="icon-next"></i></button>
                                </div>
                            </div>
                        </div>
                    </ul>
                </div>
            </div>
        </div>
    </main>

@endsection
@push('js_bot_all')
    <script>
        var coupons = $('#coupons').val();
    </script>
    {!! \Lib::addMedia('js/features/cart/cart.js') !!}
@endpush