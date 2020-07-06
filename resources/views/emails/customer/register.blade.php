@extends('emails.template')

@section('content')
<div style="padding:25px 20px;text-align:left;font-size: 14px;">
    <div style="font-style: italic;font-weight: 600;color: #333;">
        Xin chào <strong>{{$data->fullname}}</strong>,
    </div>
    <div style="padding:10px 0 15px;text-align:left;line-height: 20px">
        <p>Chào mừng bạn đến với <b>{{env('APP_NAME')}}</b> - Trang thương mại điện tử lớn nhất Việt Nam.</p>

        <p>Bạn nhận được email này do bạn hoặc một ai đó đã sử dụng địa chỉ email <b>{{$data->email}}</b> để đăng ký tài khoản tại <b>{{ env('APP_NAME') }}</b></p>

        <p>Nếu bạn không tạo tài khoản trên <b>{{ env('APP_NAME') }}</b>, vui lòng bỏ qua nội dung của email này, lệnh sẽ được huỷ trong vòng 24 giờ và thông tin của bạn sẽ được bảo mật hoàn toàn.</p>

        <p>Xin mời bạn <a href="{{ route('register.verify') }}?email={{ $data->email  }}&token={{ $data->token->token }}">Nhấn vào đây</a> để kích hoạt tài khoản sử dụng.</p>

        <p>Trong trường hợp trình duyệt web không tự chuyển được vui lòng copy link dưới đây vào trình duyệt và nhấn Enter: <br> <a href="{{ route('register.verify') }}?email={{ $data->email  }}&token={{ $data->token->token }}">{{ route('register.verify') }}?email={{ $data->email  }}&token={{ $data->token->token }}</a></p>

        <p>Cảm ơn bạn đã sử dụng dịch vụ của <b>{{env('APP_NAME')}}</b></p>
    </div>
</div>
@stop

