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
        <div class="container tra-gop mb-4 mt-4">
            <div class="san-pham-head row">
                <div class="info-sp col-12 col-lg-8 mb-3 mb-lg-0">
                    <h5><span>Mua trả góp {{$data->title}} | </span> <span class="price text-danger">@if($data->out_of_stock == 0){{\Lib::priceFormatEdit($data->price, '')['price']}} đ @else Liên hệ @endif</span></h5>
                </div>
                <div class="search-product col-12 col-lg-4">
                    <div class="wrap position-relative w-100">
                        <form action="">
                            <input type="text" id="serch_product_installment" placeholder="Tìm kiếm sản phẩm  trả góp khác...">
                            <a><i class="fa fa-search" aria-hidden="true"></i></a>
                        </form>
                        <div class="popsearch">
                            <div class="div-product-search ">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="row no-gutters bg-white">
                <div class="dumm w-100">
                    <div class="abczxx flex-wrap" >
                        <div class="col-12 col-md-4 chose-option-pay p-0" style="box-shadow: inset -5px 0 15px -5px rgba(0, 0, 0, 0.1);">
                            <div class="options-pay">
                                <div class="option option-card @if(request()->index == 1) active @endif " style="margin-top: 0px">
                                    <div class="img"> <img data-src="{{asset('html-viettech/./images/visa.png')}}" class="lazyload" alt=""></div>
                                    <div class="cont">
                                        <div class="wrap">
                                            <div class="">Bằng thẻ visa, master</div>
                                            <span>Lãi suất 0%</span>
                                        </div>
                                        <i class="fa fa-angle-right d-none d-md-block"></i>
                                    </div>
                                </div>
                                <div class="option option-comp @if(request()->index == 0) active @endif " style="margin-top: 0px">
                                    <div class="img"> <img data-src="{{asset('html-viettech/./images/debt.png')}}" class="lazyload" alt=""></div>
                                    <div class="cont">
                                        <div class="wrap">
                                            <div class="">Qua công ty tài chính</div>
                                            {{--                                    <span>2.285.000đ/ tháng trong 8 tháng</span>--}}
                                        </div>
                                        <i class="fa fa-angle-right d-none d-md-block"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12 col-md-8 processing p-0">
                            <div class="using-card @if(request()->index != 1) d-none @endif">
                                @if(isset($installment_bank))
                                    <div class="brand-tra-gop">
                                        <h6 class="title-step">Bước 1. Chọn ngân hàng trả góp :</h6>
                                        <div class="brand-tra-gop-list">
                                            <ul>
                                                @foreach($installment_bank as $item_bank)
                                                    <li>
                                                        <input type="radio" data-bank="{{$item_bank->title}}" data-surcharge="{{$item_bank->surcharge}}"  name="ngan_hang" value="{{$item_bank->id}}">
                                                        <figure>
                                                            <img data-src="{{$item_bank->getImageUrl('tiny')}}" class="lazyload" alt="">
                                                        </figure>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="brand-tra-gop d-none" >
                                        <h6 class="title-step">Bước 2. Chọn loại thẻ thanh toán :</h6>
                                        <div class="brand-tra-gop-list" id="payment_by_bank">
                                            <ul></ul>
                                        </div>
                                    </div>
                                    <div class="so-tien-tra bg-white" >
                                        <h6 class="title-step">Bước 3. Số tiền muốn trả góp qua thẻ tín dụng</h6>
                                        <input type="text" placeholder="Trả góp toàn bộ">
                                    </div>
                                    <div class="table-info bg-white " >
                                        <h6 class="title-step">Bước 4. Chọn số tháng trả góp</h6>
                                        <div class="scroll_ins">
                                            <div class="table-main">
                                                <ul class="table-main-line" id="month_support_by_payment">
                                                    <li><b>Số tháng trả góp</b></li>
                                                    <li></li>
                                                </ul>
                                                <ul class="table-main-line" id="price_product">
                                                    <li><span>Giá sản phẩm</span></li>
                                                    <li></li>
                                                </ul>
                                                {{--                                    <ul class="table-main-line">--}}
                                                {{--                                        <li><span>Giá trả góp</span></li>--}}
                                                {{--                                        <li><span class="price">34.990.000</span></li>--}}
                                                {{--                                        <li><span class="price">34.990.000</span></li>--}}
                                                {{--                                    </ul>--}}
                                                <ul class="table-main-line" id="pay_a_month">
                                                    <li><span>Góp mỗi tháng</span></li>
                                                    <li></li>
                                                </ul>
                                                <ul class="table-main-line" id="conver_fee">
                                                    <li><span>Phí chuyển đổi sang trả góp</span></li>
                                                    <li></li>
                                                </ul>
                                                <ul class="table-main-line" id="total">
                                                    <li><span>Tổng tiền trả góp</span></li>
                                                    <li></li>
                                                </ul>
                                                <ul class="table-main-line" id="difference">
                                                    <li><span>Chênh lệch với mua trả thẳng</span></li>
                                                    <li></li>
                                                </ul>
                                                <ul class="table-main-line" id="need_to_pay">
                                                    <li><span>Số tiền thanh toán khi nhận máy</span></li>
                                                    <li></li>
                                                </ul>
                                                <ul class="table-main-line btn-gr" id="choose_month">
                                                    <li></li>
                                                    <li></li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="info-buyer">
                                            <p>Nhập thông tin người mua:</p>
                                            <div class="buyer_sex">
                                                <div class="buyer_sex-item">
                                                    <input type="radio" name="buyer-sex" value="1" @if(old('buyer-sex') == 1) checked @endif required>
                                                    <span>Anh</span>
                                                </div>
                                                <div class="buyer_sex-item">
                                                    <input type="radio" name="buyer-sex" value="0" @if(old('buyer-sex') == 0) checked @endif required>
                                                    <span>Chị</span>
                                                </div>
                                            </div>
                                            <div class="buyer_input">
                                                <div class="d-flex w-100">
                                                    <input type="text" class="mr-3" name="name_bank" id="name_bank" value="{{old('name_bank')}}" placeholder="Họ và tên" required>
                                                    <input type="text" name="phone_bank" value="{{old('phone_bank')}}" placeholder="Số điện thoại" required>
                                                </div>
                                                <div class="d-flex w-100">
                                                    <input type="text" class="mr-3" name="time_input_bank" placeholder="Ngày / Tháng / Năm sinh" id="time_input_bank" value="{{old('time_input_bank')}}" required>
                                                    <input type="text" name="cmtnd_bank"  placeholder="Nhập số CMND" value="{{old('cmtnd_bank')}}" required>
                                                </div>
                                                <button class="btn-buy" id="pay_installment_bank">thanh toán</button>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="using-comp bg-white tra-gop-step-2 @if(request()->index != 0) d-none @endif">
                                <div class="col-12">
                                    <h6 class="title-step">Chọn số tháng trả góp :</h6>
                                    <ul class="list-months">
                                        @if(isset($installment))
                                            @foreach($installment as $item_in)
                                                <li class="months-item">
                                                    <input type="radio" data-installment="{{$item_in->month}}" name="month"  value="{{$item_in->id}}">
                                                    <span>{{$item_in->month}} tháng</span>
                                                </li>
                                            @endforeach
                                        @endif
                                    </ul>
                                </div>
                                <div class="col-12 " id="dataInstallment">
                                    <div class="table-info bg-white">
                                        <div class="scroll_ins">
                                            <div class="table-main">
                                                <ul class="table-main-line" id="company_sc_img">
                                                    <li><span>Công ty</span></li>
                                                    <li></li>
                                                </ul>
                                                <ul class="table-main-line" id="month_sc">
                                                    <li><span>Số tháng trả góp</span></li>
                                                    <li><span></span></li>
                                                </ul>
                                                <ul class="table-main-line" id="prepay_sc">
                                                    <li><span>Số tiền trả trước</span></li>
                                                    <li><span></span></li>
                                                </ul>
                                                <ul class="table-main-line" id="diff_sc">
                                                    <li><span>Chênh lệch với mua trả thắng</span></li>
                                                    <li><span></span></li>
                                                </ul>
                                                <ul class="table-main-line" id="pay_a_month_sc">
                                                    <li><span>Số tiền góp mỗi tháng</span></li>
                                                    <li><span></span></li>
                                                </ul>
                                                <ul class="table-main-line" id="total_cost_sc">
                                                    <li><span>Tổng chi phí</span></li>
                                                    <li><span></span></li>
                                                </ul>
                                                <ul class="table-main-line" id="pagers_sc">
                                                    <li><span>Giấy tờ cần có</span></li>
                                                    <li><b></b></li>
                                                </ul>
                                                <ul class="table-main-line" id="company_sc">
                                                    <li><span>Công ty tài chính</span></li>
                                                    <li><b></b></li>
                                                </ul>
                                                <ul class="table-main-line btn-gr" id="choose_month_sc">
                                                    <li></li>
                                                    <li></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="tra-gop-btn-gr">
                                        {{--                                <a style="color: #fff" id="pay_installment_scenarios" class="btn-buy-this clickable-rowb">Chọn mua</a>--}}
                                        <a style="color: #fff" data-href="{{route('save.saveInfo', ['alias' => $data->alias, '_token' => request()->_token,'index' => request()->index, 'id' => request()->id, 'filter_key' => request()->filter_key, 'quan'=>request()->quan])}}" id="pay_installment_scenarios" class="btn-buy-this clickable-row">Chọn mua</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
            <div class="popup-success d-none" id="popup">
                <div class="popup-success-main">
                    <img data-src="{{asset('html-viettech/images/dang-ky-tra-gop-thanh-cong.png')}}" class="lazyload" alt="">
                    <h5>Chúc mừng quý khách đã đăng kí Dịch vụ trả góp của {{env('APP_NAME')}}  !!!</h5>
                    <p>Nhân viên tư vấn sẽ hỗ trợ quý khách hàng để thủ tục được duyệt nhanh nhất!</p>
                    <a href="{{route('home')}}" class="btn-success-main js-close-success-main">Đồng ý</a>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('js_bot')
    <script>
        $('#serch_product_installment').keyup(function(){
            var data = $(this).val();
            var url = new URL(window.location.href);
            var request_index = url.searchParams.get("index");
            var product_alias = '{{@$data->alias}}';
            if(data != ''){
                $('.popsearch').addClass('active');
                $('.div-product-search').show();
            }else{
                $('.popsearch').removeClass('active');
                $('.div-product-search').hide();
            }
            $('.div-product-search').html("");
            $.ajax({
                type: 'POST',
                url: ENV.BASE_URL+"ajax/searchProductInstallment",
                data: {_token:ENV.token, alias: product_alias, index: request_index, data:data},
                dataType: 'json',
                async:true
            }).done(function(json) {
                $('.div-product-search').html(json.data);
            })
        })
        $(document).ready(function () {
            //Select Bank
            $(document).on("change", "input[name=ngan_hang]", function (e) {
                if (this.checked) {
                    var bank = $('[name=ngan_hang]:checked').val();
                    var surcharge = $(this).data('surcharge');
                    var bank_name = $(this).data('bank');
                    $.ajax({
                        url: ENV.BASE_URL+'ajax/loadPaymentByBankID',
                        type: 'POST',
                        data: {
                            _token: ENV.token,
                            idBank: bank,
                        },
                        success: function (json) {
                            if (json !== undefined ){
                                if(json.data != null){
                                    var data = JSON.parse(json.data.properties);
                                    var html = '';
                                    var payment = '';
                                    var val_tra_gop = '';
                                    $('#payment_by_bank ul').empty();
                                    $('#month_support_by_payment li:not(:first)').remove();
                                    $('#price_product li:not(:first)').remove();
                                    $('#pay_a_month li:not(:first)').remove();
                                    $('#conver_fee li:not(:first)').remove();
                                    $('#total li:not(:first)').remove();
                                    $('#difference li:not(:first)').remove();
                                    $('#need_to_pay li:not(:first)').remove();
                                    $('#choose_month li:not(:first)').remove();
                                    $.each(data,function (ind,value) {
                                        var image_payment = '{{asset('upload/original/')}}/'+value.payment_image;
                                        html += '<li>' +
                                            '<input type="radio" id="payment" name="payment" value="'+value.payment_title+'">' +
                                            '<figure>' +
                                            '<img class="w-50 rounded mx-auto d-block" src="'+image_payment+'" alt="">' +
                                            '</figure>' +
                                            '</li>'
                                    })
                                    $("#payment_by_bank ul").append(html);
                                    $('.brand-tra-gop').removeClass('d-none');
                                    $(document).on("change", "input[name=payment]", function (  e) {
                                        $(payment).empty();
                                        var price = {!! $data->price !!};
                                        var data_Price = shop.numberFormat(price);
                                        if (this.checked) {
                                            $('#month_support_by_payment li:not(:first)').remove();
                                            $('#price_product li:not(:first)').remove();
                                            $('#pay_a_month li:not(:first)').remove();
                                            $('#conver_fee li:not(:first)').remove();
                                            $('#total li:not(:first)').remove();
                                            $('#difference li:not(:first)').remove();
                                            $('#need_to_pay li:not(:first)').remove();
                                            $('#choose_month li:not(:first)').remove();

                                            payment = $('#payment:checked').val();
                                            var month_pay = '';
                                            var dataPrice = '';
                                            var pay_a_month = '';
                                            var conver = '';
                                            var total_cos = '';
                                            var diff = '';
                                            var need = '';
                                            var choose = '';
                                            $.each(data, function (index, value) {
                                                if (value.payment_title == payment){
                                                    $.each(value.month, function (index_month, data_month) {
                                                        var conversion_fee = '';
                                                        $.each(data_month.props, function (index_props, data_props) {
                                                            //phí chuyển đổi = phụ phí + (giá * % phí chuyển đổi) + (kỳ hạn * % lãi suất)
                                                            conversion_fee = surcharge + (price * (parseInt(data_props.conversion_fee)/100)) + (data_month.month * (parseInt(data_props.interest_rate)/100));
                                                        })
                                                        //góp mỗi tháng =  (giá + phí chuyển đổi) / kỳ hạn
                                                        var pay_a_m = (price + conversion_fee) / data_month.month;
                                                        //tổng trả góp = giá + phí chuyển đổi
                                                        var total = price + conversion_fee;

                                                        month_pay += '<li><b class="date-time">'+data_month.month+' Tháng</b></li>';
                                                        dataPrice += '<li><span>'+data_Price+' VNĐ</span></li>';
                                                        pay_a_month += '<li><span>'+shop.numberFormat(pay_a_m)+' VNĐ</span></li>';
                                                        conver += '<li><span>'+shop.numberFormat(conversion_fee)+' VNĐ</span></li>';
                                                        total_cos += '<li><span>'+shop.numberFormat(total)+' VNĐ</span></li>';
                                                        diff += '<li><span>'+shop.numberFormat(conversion_fee)+' VNĐ</span></li>';
                                                        need += '<li><span>0 VNĐ</span></li>';
                                                        choose += '<li><a href="javascript:;" class="js-select-tra-gop" data-month="'+data_month.month+'">Chọn</a></li>';
                                                    })
                                                }
                                            });
                                            $('#month_support_by_payment ').append(month_pay);
                                            $('#price_product ').append(dataPrice);
                                            $('#pay_a_month ').append(pay_a_month);
                                            $('#conver_fee ').append(conver);
                                            $('#total ').append(total_cos);
                                            $('#difference ').append(diff);
                                            $('#need_to_pay ').append(need);
                                            $('#choose_month ').append(choose);


                                            $('.js-select-tra-gop').click(function () {
                                                $('.js-select-tra-gop').removeClass('active');
                                                $(this).addClass('active');
                                                val_tra_gop = $(this).data('month');
                                            });


                                        }
                                    });
                                    $('#pay_installment_bank').click(function () {
                                        var product_id = {{$data->id}};
                                        var filter = '{{request()->filter_key}}';
                                        var quan = '{{request()->quan}}';
                                        var type = '{{request()->index}}';
                                        var buyer_sex = $('[name=buyer-sex]:checked').val();
                                        var name = $('[name=name_bank]').val();
                                        var phone = $('[name=phone_bank]').val();
                                        var dateof = $('[name=time_input_bank]').val();
                                        var cmtnd = $('[name=cmtnd_bank]').val();

                                            if (payment){
                                                if (val_tra_gop){
                                                    if (buyer_sex){
                                                        if (name){
                                                            if (phone){
                                                                if (shop.is_phone(phone)){
                                                                    if (dateof){
                                                                        if (cmtnd){
                                                                            if (shop.is_num(cmtnd)){
                                                                                if (cmtnd.length == 12){
                                                                                    $.ajax({
                                                                                        url: ENV.BASE_URL + 'ajax/savePaymentByBankID',
                                                                                        type: 'POST',
                                                                                        data: {
                                                                                            _token: ENV.token,
                                                                                            product:product_id,
                                                                                            filter:filter,
                                                                                            quan:quan,
                                                                                            type:type,
                                                                                            nameBank: bank_name,
                                                                                            payment:payment,
                                                                                            month: val_tra_gop,
                                                                                            buyer_sex: buyer_sex,
                                                                                            name: name,
                                                                                            phone:phone,
                                                                                            dateofbirth:dateof,
                                                                                            cmtnd:cmtnd,
                                                                                        },
                                                                                        success: function (json) {
                                                                                            if(json.error == 0){
                                                                                                $('#popup').removeClass('d-none');
                                                                                            }
                                                                                        }
                                                                                    })
                                                                                } else {
                                                                                    Swal.fire({
                                                                                        type: 'warning',
                                                                                        title: 'Oops...',
                                                                                        text: 'Số CMND không đúng định dạng',
                                                                                    });
                                                                                }

                                                                            } else{
                                                                                Swal.fire({
                                                                                    type: 'warning',
                                                                                    title: 'Oops...',
                                                                                    text: 'Số CMND không đúng định dạng',
                                                                                });
                                                                            }

                                                                        } else{
                                                                            Swal.fire({
                                                                                type: 'warning',
                                                                                title: 'Oops...',
                                                                                text: 'Bạn không được để trống trường số CMND',
                                                                            });
                                                                        }

                                                                    } else{
                                                                        Swal.fire({
                                                                            type: 'warning',
                                                                            title: 'Oops...',
                                                                            text: 'Bạn không được để trống trường Ngày / Tháng / Năm sinh',
                                                                        });
                                                                    }

                                                                } else{
                                                                    Swal.fire({
                                                                        type: 'warning',
                                                                        title: 'Oops...',
                                                                        text: 'Số điện thoại không đúng định dạng',
                                                                    });
                                                                }

                                                            } else{
                                                                Swal.fire({
                                                                    type: 'warning',
                                                                    title: 'Oops...',
                                                                    text: 'Bạn không được để trống trường Số điện thoại',
                                                                });
                                                            }
                                                        }else{
                                                            Swal.fire({
                                                                type: 'warning',
                                                                title: 'Oops...',
                                                                text: 'Bạn không được để trống trường Họ và Tên',
                                                            });

                                                        }

                                                    }else{
                                                        Swal.fire({
                                                            type: 'warning',
                                                            title: 'Oops...',
                                                            text: 'Bạn không được để trống  trường giới tính',
                                                        });
                                                    }
                                                }else{
                                                    Swal.fire({
                                                        type: 'warning',
                                                        title: 'Oops...',
                                                        text: 'Bạn chưa chọn gói trả góp',
                                                    });
                                                }

                                            }else{
                                                Swal.fire({
                                                    type: 'warning',
                                                    title: 'Oops...',
                                                    text: 'Bạn chưa chọn thẻ thanh toán',
                                                });
                                            }

                                    });
                                }else{
                                    Swal.fire({
                                        type: 'warning',
                                        title: 'Oops...',
                                        text: 'Đã có lỗi từ nhà cung cấp, Vui lòng chọn gói khác',
                                    }).then((result) => {
                                        shop.reload();
                                    });
                                }
                            }
                        }
                    })
                }

            });

            //Select Month
            $(document).on("change", "input[name=month]", function (e) {
                if (this.checked) {
                    var selMon = this.value;
                    var month_number = $(this).data('installment');
                    var month = $('[name=month]:checked')[0].value;
                    $.ajax({
                        url: ENV.BASE_URL+'ajax/loadInstallmentScenariosByID',
                        type: 'POST',
                        data: {
                            _token:ENV.token,
                            termID: month,
                        },
                        success: function (json) {
                            if (json !== undefined ) {
                                if (json.data != null){
                                    var data = JSON.parse(json.data.properties);
                                    var price = {!! $data->price !!};

                                    var html_img = '';
                                    var html_month = '';
                                    var html_prepay = '';
                                    var html_difference = '';
                                    var html_paymonth = '';
                                    var html_total_cost = '';
                                    var html_pagers_required_sc = '';
                                    var html_des = '';
                                    var choose_sc = '';

                                    $('#company_sc_img li:not(:first)').remove();
                                    $('#month_sc li:not(:first)').remove();
                                    $('#prepay_sc li:not(:first)').remove();
                                    $('#diff_sc li:not(:first)').remove();
                                    $('#pay_a_month_sc li:not(:first)').remove();
                                    $('#total_cost_sc li:not(:first)').remove();
                                    $('#pagers_sc li:not(:first)').remove();
                                    $('#company_sc li:not(:first)').remove();
                                    $('#choose_month_sc li:not(:first)').remove();
                                    $.each(data,function (ind,value) {
                                        var pagers_requried = '';
                                        $.each(value.pagers_required, function (pa,pagers) {
                                            pagers_requried += ''+pagers+' + ';
                                        })
                                        var image_sc = '{{asset('upload/original/')}}/'+value.image;
                                        var prepay_sc = (price * parseInt(value.prepay)) / 100; //trả trước = giá máy * % trả trước / 100
                                        var paymonth_sc =  ((price - prepay_sc) / month_number) + ((price - prepay_sc) * (value.per_pay_mo / 100)) + parseInt(value.surcharge); //trả mỗi tháng = ((giá máy - trả trước) / số tháng) + ((giá máy - trả trước) * (% tháng / 100 )) + phụ phí
                                        var total_pay_sc = paymonth_sc * month_number; // tổng tiền trả góp = trả mỗi tháng * số tháng
                                        var total_sc = total_pay_sc + prepay_sc //tổng tiền sau trả góp = tổng tiền trả góp + trả trước
                                        var difference_sc = total_sc - price; //chênh lệch = tổng tiền sau trả góp - giá máy

                                        html_img += '<li>' +
                                            '<img data-src="'+image_sc+'" class="lazyload" alt="">' +
                                            '</li>';
                                        html_month += '<li><span>'+month_number+' Tháng</span></li>';
                                        html_prepay += '<li><span>'+shop.numberFormat(prepay_sc)+' VNĐ</span></li>';
                                        html_difference += '<li><span>'+shop.numberFormat(difference_sc)+' VNĐ</span></li>';
                                        html_paymonth += '<li><span>'+shop.numberFormat(paymonth_sc)+' VNĐ</span></li>';
                                        html_total_cost += '<li><span>'+shop.numberFormat(total_sc)+' VNĐ</span></li>';
                                        html_pagers_required_sc += '<li><b>'+pagers_requried.substring(0, pagers_requried.length - 2)+'</b></li>';
                                        html_des += '<li><b>'+value.des+'</b></li>';
                                        choose_sc += '<li><a href="javascript:;" class="js-select-tra-gop-sc" data-company="'+value.company+'" data-ins="'+month+'" data-sc="'+month_number+'">Chọn</a></li>';
                                    })
                                    $('#company_sc_img').append(html_img);
                                    $('#month_sc').append(html_month);
                                    $('#prepay_sc').append(html_prepay);
                                    $('#diff_sc').append(html_difference);
                                    $('#pay_a_month_sc').append(html_paymonth);
                                    $('#total_cost_sc').append(html_total_cost);
                                    $('#pagers_sc').append(html_pagers_required_sc);
                                    $('#company_sc').append(html_des);
                                    $('#choose_month_sc').append(choose_sc);

                                    var installment_id_choose = '';
                                    var company_installment_choose = '';
                                    var month_installment_choose = '';
                                    $('.js-select-tra-gop-sc').click(function () {
                                        $('.js-select-tra-gop-sc').removeClass('active');
                                        $(this).addClass('active');
                                        month_installment_choose = $(this).data('sc');
                                        company_installment_choose = $(this).data('company');
                                        installment_id_choose = $(this).data('ins');
                                    });
                                    $('#pay_installment_scenarios').click(function () {
                                        thisdata = $(this).attr('data-href')+'&ins='+installment_id_choose+'&month='+month_installment_choose+'&com='+company_installment_choose;
                                        window.location.href = thisdata;
                                    });
                                }else{
                                    Swal.fire({
                                        type: 'warning',
                                        title: 'Oops...',
                                        text: 'Đã có lỗi từ nhà cung cấp, Vui lòng chọn gói khác',
                                    }).then((result) => {
                                        shop.reload();
                                    });
                                }
                            }else {
                                Swal.fire({
                                    type: 'error',
                                    title: 'Oops...',
                                    text: 'Đã có lỗi từ nhà cung cấp, Vui lòng chọn gói khác',
                                });
                            }
                        },

                    });
                }
            });
        });

    </script>
@endsection