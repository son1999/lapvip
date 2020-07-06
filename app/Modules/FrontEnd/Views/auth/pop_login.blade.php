<div class="modal fade" id="login" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <a href="javascript:;" class="text-right close-popup pt-4 pr-4" data-dismiss="modal"><span aria-hidden="true">&times;</span></a>
            <div class="modal-header flex-column align-items-center text-center border-0">
                <h5 class="modal-title">Đăng nhập</h5>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="pop-phone-login">Email</label>
                    <input id="pop-email-login" type="text" class="form-control" placeholder="{{ __('Email đã đăng ký') }}">
                </div>
                <div class="form-group">
                    <label for="">Nhập mật khẩu</label>
                    <input id="pop-pw-login" type="password" class="form-control" placeholder="{{ __('auth.matkhau') }}">
                </div>
                <div class="d-flex flex-md-nowrap flex-wrap justify-content-between mb-3">
                    <div class="remember-pass">
                        <input type="checkbox" name="rememberLogin">
                        <span>Ghi nhớ đăng nhập </span>
                    </div>
                    <a href="{{ route('password') }}" data-dismiss="modal" data-toggle="modal" data-target="#forgetpass">Quên mật khẩu?</a>
                </div>
                <div class="text-center">
                    <button onclick="shop.login()"   class="btn btn-theme">ĐĂNG NHẬP</button>
                </div>
                <p class="text-center">Bạn chưa có tài khoản? <a href="javascript:;" data-dismiss="modal" data-toggle="modal" data-target="#singup">Đăng ký tài khoản</a></p>
            </div>
        </div>
    </div>
</div>