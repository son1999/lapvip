@extends('FrontEnd::layouts.home')

@section('title') {!! \Lib::siteTitle($site_title, $def['site_title']) !!} @stop

@section('content')
    <main>
        <div class="container">
            <div class="px-0">
                {!! \Lib::renderBreadcrumb(false, true,'FrontEnd::layouts.breadcrumb') !!}
            </div>
        </div>
        <div class="container banner_page_laptop">
            <div class="js-carousel" data-items="1" data-arrows="false">
                @foreach($slide as $slide_compare)
                    <a href="{{$slide_compare->link}}" class="banner_item">
                        <img data-src="{{$slide_compare->getImageUrl('original')}}" class="lazyload" alt="">
                    </a>
                @endforeach
            </div>
        </div>

        <div class="container tra-gop tra-gop-step-2">
            <div class="san-pham-head row">
                <div class="info-sp col-12 col-lg-8 mb-3 mb-lg-0">
                    <h5><span>Mua trả góp {{$data['title_pro']}} | </span> <span class="price text-danger">{{\Lib::priceFormatEdit($data['price_pro'], '')['price']}} đ</span>
                    </h5>
                </div>

            </div>
            <hr class="bg-white m-0">
            <div class="row bg-white m-0">
                <div class="col-12 col-lg-8">
                    <h6 class="title-step">Thông tin trả góp :</h6>
                    <div class="tab-thong-tin-tra-gop">
                        <ul class="table-main-line">
                            <li><span>Công ty</span></li>
                            <li>
                                <img data-src="{{asset('upload/original/'.$data['img'])}}" class="lazyload" alt="">
                            </li>
                        </ul>
                        <ul class="table-main-line">
                            <li><span>Số tháng trả góp</span></li>
                            <li><span>{{$data['month']}} Tháng</span></li>
                        </ul>
                        <ul class="table-main-line">
                            <li><span>Số tiền trả trước</span></li>
                            <li><span class="text-danger">{{\Lib::priceFormatEdit($data['prepay'], '')['price']}} đ</span></li>
                        </ul>
                        <ul class="table-main-line">
                            <li><span>Chênh lệch với mua trả thắng</span></li>
                            <li><span class="text-danger">{{\Lib::priceFormatEdit($data['difference'], '')['price']}} đ </span></li>
                        </ul>
                        <ul class="table-main-line">
                            <li><span>Số tiền góp mỗi tháng</span></li>
                            <li><span class="text-danger">{{\Lib::priceFormatEdit($data['paymonth'], '')['price']}} đ </span></li>
                        </ul>
                        <ul class="table-main-line">
                            <li><span>Tổng chi phí</span></li>
                            <li><span class="text-danger">{{\Lib::priceFormatEdit($data['total_af_ins'], '')['price']}} đ </span></li>
                        </ul>
                        <ul class="table-main-line">
                            <li><span>Giấy tờ cần có</span></li>
                            <li><b>{{substr($data['pagers'], 0, -2)}}</b></li>
                        </ul>
                        <ul class="table-main-line">
                            <li><span>Công ty tài chính</span></li>
                            <li><b>{{$data['des']}}</b></li>
                        </ul>
                    </div>
                    <form action="{{route('save.saveSuccess', ['alias' => str_slug($data['title_pro']), '_token' => request()->_token,'index' => request()->index, 'id' => request()->id, 'filter_key' => request()->filter_key, 'quan'=>request()->quan, 'ins'=>request()->ins, 'month'=> request()->month, 'com'=>request()->com])}}" method="POST">
                        @csrf
                        <h6 class="title-step">Thông tin trả góp :</h6>
                        <div class="info-buyer info-buyer-form">
                            <div class="buyer_sex">
                                <div class="buyer_sex-item">
                                    <input type="radio" name="buyer_sex" value="1" @if(old('buyer-sex') == 1) checked @endif>
                                    <span>Anh</span>
                                </div>
                                <div class="buyer_sex-item">
                                    <input type="radio" name="buyer_sex" value="0" @if(old('buyer-sex') == 0) checked @endif>
                                    <span>Chị</span>
                                </div>
                            </div>
                            @error('buyer-sex')
                            <i style="color:  red; ">{{$message}}</i>
                            @enderror
                            <div class="form-info row">
                                <div class="col-12 @error('name') mb-3 @enderror" >
                                    <div style="flex-grow: 1;">
                                        <input type="text" name="name" @error('name') style="margin-bottom: 2px; border-color: red;" @enderror placeholder="Họ và tên" value="{{old('name')}}">
                                        @error('name')
                                        <i style="color:  red; ">{{$message}}</i>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 @error('cmtnd') mb-3 @enderror ">
                                    <div style="flex-grow: 1;">
                                        <input type="text" name="cmtnd" @error('cmtnd') style="margin-bottom: 2px; border-color: red;" @enderror placeholder="Nhập số CMND" value="{{old('cmtnd')}}">
                                        @error('cmtnd')
                                        <i style="color:  red;">{{$message}}</i>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 col-lg-6 @error('time_input') mb-3 @enderror">
                                    <div style="flex-grow: 1;">
                                        <input type="text" name="time_input" @error('time_input') style="margin-bottom: 2px; border-color: red;" @enderror placeholder="Ngày / Tháng / Năm sinh" id="time-input" value="{{old('time_input')}}">
                                        @error('time_input')
                                        <i style="color:  red; ">{{$message}}</i>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-12 @error('phone') mb-3 @enderror">
                                    <div style="flex-grow: 1">
                                        <input type="text" name="phone" @error('phone') style="margin-bottom: 2px; border-color: red;" @enderror placeholder="Số điện thoại" value="{{old('phone')}}">
                                        @error('phone')
                                        <i style="color:  red; margin-top: 1px">{{$message}}</i>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <h6 class="title-step">Chọn shop để duyệt hồ sơ :</h6>
                        <div class="row">
                            <div class="select-shop col-12 col-lg-6">
                                <select name="province_id" id="provinces">
                                    <option value="" selected disabled>Thành Phố</option>
                                    @foreach($pro as  $provin)
                                        <option  value="{!! $provin->id !!}">{!! $provin->Name_VI !!}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="select-shop col-12 col-lg-6">
                                <select name="district" id="districts">
                                </select>
                            </div>
                        </div>

                        <h6 class="title-step">Hãy chọn shop bạn muốn nhận hàng :</h6>

                        <ul class="list-shop">
                            <li>
                                <p>Hãy chọn khu vực để thấy được cửa hàng của chúng tôi</p>
                            </li>
                        </ul>
                        @error('point-shop')
                        <i style="color:  red;">{{$message}}</i>
                        @enderror


                        <button class="btn-buy js-show-success-main pay-installment" >thanh toán</button>
                    </form>

                </div>
            </div>

            @if(\Session::has('thanhcong'))
                <div class="popup-success">
                    <div class="popup-success-main">
                        <img data-src="{{asset('html-viettech/images/dang-ky-tra-gop-thanh-cong.png')}}" class="lazyload" alt="">
                        <h5>Chúc mừng quý khách đã đăng kí Dịch vụ trả góp của {{env('APP_NAME')}}  !!!</h5>
                        <p>Nhân viên tư vấn sẽ hỗ trợ quý khách hàng để thủ tục được duyệt nhanh nhất!</p>
                        <a href="{{route('home')}}" class="btn-success-main js-close-success-main">Đồng ý</a>
                    </div>
                </div>
            @endif
        </div>
    </main>
@endsection

@section('js_bot')
    <script type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#provinces').change(function(){
                var province_id = $(this).val();

                if(province_id){
                    $.ajax({
                        type:"GET",
                        url:"{{url('getDistrict')}}?pro_id="+province_id,
                        dataType: 'json',
                        success:function(response){
                            var len  = 0;
                            $("#districts").empty();
                            if (response['data'] != null){
                                len = response['data'].length;
                            }
                            if(len > 0){
                                $("#districts").append('<option selected disabled>Quận/Huyện</option>');
                                for (var i =0 ; i < len; i++){
                                    var name = response['data'][i].Name_VI;
                                    var id = response['data'][i].id;
                                    $("#districts").append('<option  value="'+id+'">'+name+'</option>');
                                }
                            }else{
                                $("#districts").empty();
                            }
                        }
                    });
                }else{
                    $("#districts").empty();
                }
            });

            $('#districts').change(function () {
                var province_id = $('#provinces').val();
                var districts = $(this).val();
                if(province_id){
                    if (districts){
                        $.ajax({
                            type:"GET",
                            url:"{{url('getWarehouse')}}?pro_id="+province_id+'&dis_id='+districts,
                            dataType: 'json',
                            success:function(response){
                                var len  = 0;
                                $(".list-shop").empty();
                                if (response['data'] != null){
                                    len = response['data'].length;
                                }
                                if(len > 0){
                                    for (var i =0 ; i < len; i++){
                                        var location = response['data'][i].location;
                                        var id = response['data'][i].id;
                                        var html = '<li class="shop_item">'+
                                                        '<input type="radio" name="point_shop" value="'+id+'">'+
                                                        '<p>'+location+'</p>'+
                                                    '</li>'
                                        $(".list-shop").append(html);
                                    }
                                }else{
                                    $(".list-shop").append('<li>'+
                                                            '<p>Thật tiếc, chúng tôi chưa có sơ sở tại đây !!!</p>'+
                                                            '</li>');
                                }
                            }
                        });
                    }
                }
            });

        });
    </script>
@endsection