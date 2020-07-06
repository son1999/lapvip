

<div class="modal fade" id="singup" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <a href="javascript:;" class="text-right close-popup pt-4 pr-4" data-dismiss="modal"><span aria-hidden="true">&times;</span></a>
            <div class="modal-header flex-column align-items-center text-center border-0">
                <h5 class="modal-title">Đăng ký</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="">Họ và tên</label>
                    <input id="pop_name" name="regis_name" type="text" class="form-control" placeholder="Họ và tên">
                </div>
                <div class="form-group">
                    <label for="">Email</label>
                    <input id="pop_email" name="email" type="text" class="form-control" placeholder="Email">
                </div>
                <div class="form-group">
                    <label for="">Nhập mật khẩu</label>
                    <input id="pop-pw" name="password" type="password" class="form-control" placeholder="Nhập mật khẩu">
                </div>
                <div class="form-group">
                    <label for="">Nhập lại mật khẩu</label>
                    <input id="pop-pw-rp" name="password" type="password" class="form-control" placeholder="Nhập lại mật khẩu">
                </div>

                <div class="form-group">
                    <label for="">Chọn Tỉnh/Thành phố</label>
                    <select name="province_id" id="provinces" class="form-control">
                        <option value="" selected disabled>Thành Phố</option>
{{--                        @foreach($pro as  $provin)--}}
{{--                            <option  value="{!! $provin->id !!}">{!! $provin->Name_VI !!}</option>--}}
{{--                        @endforeach--}}
                    </select>
                </div>
                <div class="form-group">
                    <label for="">Chọn Quận/Huyện</label>
                    <select  id="districts" class="form-control">
                    </select>
                </div>
                <div class="d-flex flex-md-nowrap flex-wrap justify-content-between mb-3">
                    <div class="remember-pass mr-0">
                        <input id="icheck" checked="checked" type="checkbox" name="icheck" value="1">
                        <span>Bằng việc đăng ký, bạn đồng ý với chính sách của chúng tôi</span>
                    </div>
                </div>


                <div class="text-center">
                    <button onclick="shop.register()" class="btn-theme btn singup-btn">ĐĂNG KÝ</button>
                </div>
                <p class="text-center">Bạn đã có tài khoản? <a href="javascript:;" data-dismiss="modal" data-toggle="modal" data-target="#login">Đăng nhập ngay</a></p>
            </div>
        </div>
    </div>
</div>
