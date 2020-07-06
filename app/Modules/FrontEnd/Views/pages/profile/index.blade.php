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
            <div class="col-12 col-lg-9 user-tab-main">
                <form action="{{route('profile.post')}}" method="POST">
                    @csrf
                    <div class="form-content user-info">
                        <h4 class="form-title">Thông tin tài khoản</h4>
                        <div class="row user-info-wrap">
                            <div class="user-info-item col-12 col-lg-6 order-lg-0">
                                <label for="">Họ và tên</label>
                                <div style="flex-grow: 1;">
                                    <input type="text" class="form-control @error('user_name') is-invalid @enderror" placeholder="Họ và tên" id="user_name" name="fullname" value="@if($errors->has('user_name')){{old('user_name')}}@else{{ old('fullname', $data->fullname) }}@endif" >
                                    @error('user_name')
                                    <i style="color:  red">{{$message}}</i>
                                    @enderror
                                </div>

                            </div>

                            <div class="user-info-item col-12 col-lg-6 order-1 order-lg-0">
                                <label for="">Giới tính</label>
                                <div class="select-sex">
                                    <div class="select-sex-item">
                                        <input type="radio" name="gender" value="1"  @if($data['gender'] == '1' ) checked @endif >
                                        <span>Nam</span>
                                    </div> 
                                    <div class="select-sex-item">
                                        <input type="radio" name="gender" value="0" @if($data['gender'] == '0' ) checked @endif >
                                        <span>Nữ</span>
                                    </div>
                                </div>
                            </div>

                            <div class="user-info-item col-12 col-lg-6 order-lg-0">
                                <label for="">Số điện thoại</label>
                                <div style="flex-grow: 1;">
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" placeholder="Số điện thoại" id="phone" name="phone" value="@if($errors->has('phone')){{old('phone')}}@else{{ old('phone', $data->phone) }}@endif" >
                                    @error('phone')
                                        <i style="color:  red">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>

                            <div class="user-info-item col-12 col-lg-6 order-1 order-lg-0">
                                <label for="">Tỉnh/Thành phố</label>
                                <div style="flex-grow: 1;">
                                    <select name="province_profile" id="pro_profile" class="form-control @error('province_profile') is-invalid @enderror">
                                        @foreach($pro as  $provin)
                                            <option value="{!! $provin->id !!}" @if($data['province'] == $provin->id) selected @endif > {!! $provin->Name_VI !!} </option>
                                        @endforeach
                                    </select>
                                    @error('province_profile')
                                        <i style="color:  red">{{$message}}</i>
                                    @enderror
                                </div>

                            </div>

                            <div class="user-info-item col-12 col-lg-6 order-0 order-lg-0">
                                <label for="">Email</label>
                                <div style="flex-grow: 1;">
                                    <input type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="@if($errors->has('email')){{old('email')}}@else{{$data['email']}}@endif">
                                    @error('email')
                                    <i style="color:  red">{{$message}}</i>
                                    @enderror
                                </div>

                            </div>

                            <div class="user-info-item col-12 col-lg-6 order-1 order-lg-0">
                                <label for="">Quận/Huyện</label>
                                <div style="flex-grow: 1;">
                                    <select name="district_profile" id="dis_porifile" class="form-control @error('district_profile') is-invalid @enderror"></select>
                                    @error('district_profile')
                                    <i style="color:  red">{{$message}}</i>
                                    @enderror
                                </div>

                            </div>

                            <div class="user-info-item col-12 col-lg-12 order-1 order-lg-0" >
                                <label for="">Địa chỉ</label>
                                <div style="flex-grow: 1;">
                                    <textarea name="address" class=" form-control @error('address') is-invalid @enderror" cols="5" rows="1">@if($errors->has('address')){{old('address')}}@else{!! $data['address'] !!}@endif</textarea>
                                    @error('address')
                                        <i style="color:  red">{{$message}}</i>
                                    @enderror
                                </div>
                            </div>

                            <div class="btn-gr btn-gr-user col order-1 order-lg-0 mt-5">
                                <button type="submit" class="btn btn-theme">Thay đổi thông tin</button>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="col-12 col-lg-9 user-tab-main">
                    <div class="form-content user-info-pass">
                        <h4 class="form-title">Thay đổi mật khẩu</h4>
                        <div class="info-pass-wrap">
                            <div class="user-info-item">
                                <label for="">Mật khẩu cũ</label>
                                <div style="flex-grow: 1;">
                                    <input type="password" id="current_password" class="form-control">
                                    <span class="invalid-feedback"></span>
                                </div>
                            </div>

                            <div class="user-info-item">
                                <label for="">Nhập mật khẩu mới</label>
                                <div style="flex-grow: 1;">
                                    <input type="password" id="new_password" class="form-control">
                                    <span class="invalid-feedback"></span>
                                </div>
                            </div>

                            <div class="user-info-item">
                                <label for="">Nhập lại mật khẩu mới</label>
                                <div style="flex-grow: 1;">
                                    <input type="password" id="re_password" class="form-control">
                                    <span class="invalid-feedback"></span>
                                </div>
                            </div>
                        </div>
                        <div class="btn-gr btn-gr-user">
                            <button onclick="shop.changeCustomerPassword()" class="btn btn-theme">Đổi mật khẩu</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@stop

@section('js_bot')
    <script type="text/javascript">
        $('.tab-left-link').on('click', 'a', function() {
            $('.tab-left-link a.active').removeClass('active');
            $(this).addClass('active');
        });
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#pro_profile').ready(function () {
                var pro_profile_id = $('#pro_profile').val();
                if(pro_profile_id){
                    $.ajax({
                        type:"GET",
                        url:"{{url('getDistrict')}}?pro_id="+pro_profile_id,
                        dataType: 'json',
                        success:function(response){
                            var len  = 0;
                            $("#dis_porifile").empty();
                            if (response['data'] != null){
                                len = response['data'].length;
                            }
                            if(len > 0){
                                for (var i = 0; i < len; i++){
                                    var name = response['data'][i].Name_VI;
                                    var id = response['data'][i].id;
                                    var dis_customer = '{{json_encode($data['district'])}}';
                                    if (dis_customer){
                                        if (dis_customer == id){
                                            $("#dis_porifile").append('<option  value="'+id+'" selected >'+name+'</option>');
                                        }
                                            $("#dis_porifile").append('<option  value="'+id+'"  >'+name+'</option>');
                                    }

                                }
                            }else{
                                $("#dis_porifile").empty();
                            }
                        }
                    });
                }else{
                    $("#dis_porifile").empty();
                }

            })
            $('#pro_profile').change(function(){
                var province_id = $(this).val();
                if(province_id){
                    $.ajax({
                        type:"GET",
                        url:"{{url('getDistrict')}}?pro_id="+province_id,
                        dataType: 'json',
                        success:function(response){
                            var len  = 0;
                            $("#dis_porifile").empty();
                            if (response['data'] != null){
                                len = response['data'].length;
                            }
                            if(len > 0){
                                $("#dis_porifile").append('<option selected disabled>Quận/Huyện</option>');
                                for (var i =0 ; i < len; i++){
                                    var name = response['data'][i].Name_VI;
                                    var id = response['data'][i].id;
                                    $("#dis_porifile").append('<option  value="'+id+'">'+name+'</option>');
                                }
                            }else{
                                $("#dis_porifile").empty();
                            }
                        }
                    });
                }else{
                    $("#dis_porifile").empty();
                }
            });

        });
    </script>
@stop
