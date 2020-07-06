
<div class="modal fade" id="forgetpass" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
                <a href="javascript:;" class="text-right close-popup pt-4 pr-4" data-dismiss="modal"><span aria-hidden="true">&times;</span></a>
                {!! Form::open(['url' => route('password.post')]) !!}
                <div class="modal-header flex-column align-items-center text-center border-0 ">
                    <h5 class="modal-title">Quên mật khẩu</h5>
                    <i>Điền email bạn đã đăng ký với chúng tôi để được cấp lại mật khẩu</i>
                    <input id="pop-email-form" class="form-control " type="text" placeholder="{{ __('auth.email') }}" name="email" value="{{ old('email') }}">
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <input type="submit" class="btn btn-primary" value="Gửi">
                    </div>
                    <p class="text-center">Bạn chưa có tài khoản? <a href="javascript:;" data-dismiss="modal" data-toggle="modal" data-target="#singup">Đăng ký tài khoản</a></p>
                </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
