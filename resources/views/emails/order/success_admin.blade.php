<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org=/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title></title>
</head>
<body style="height: 100%;     background-color: #eaebed;">

<table width="100%" cellpadding="0" cellspacing="0" border="0" data-mobile="true" dir="ltr" align="center"  style="text-align: justify;color:#353535;background-color: #eaebed;font-family: arial,sans-serif;padding: 30px 0px;line-height: 24px; font-size: 14px">
    <tbody>
    <tr>
        <td align="center" valign="" style="padding:0;margin:0;">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="640" style="border: 1px solid #DDDDDD;background: #FFF;padding:20px; border-bottom: 3px solid #ff9600">
                <tbody>
                <tr>
                    <td colspan="2" style="color: #53a11b; font-size: 20px; padding: 10px 0">Có đơn đặt hàng thành công</td>
                </tr>
                <tr>
                    <td colspan="2">{{env('APP_NAME')}} rất vui lòng xin thông báo dơn hàng #{{$order->code}} của quý khách đã được tiếp nhận và đang trong quá trình xử lý</td>
                </tr>
                <tr>
                    <td style="width: 50%; padding-right: 15px; height: 100%; font-size: 12px; padding-top: 15px">
                        <div style="border: 1px solid #d1d1d1; border-radius: 5px; padding: 10px; height: 210px;">
                            <div style="font-weight: bold; padding: 10px 0; border-bottom: 1px solid #d1d1d1;">Địa chỉ nhận hàng</div>
                            <div>
                                <span style="font-weight: bold">Họ và tên: </span> {{$order->fullname}}
                            </div>
                            <div>
                                <span style="font-weight: bold">Địa chỉ: </span> {{ $order->address ? $order->address. ', ' : ''}} {{$order->province->Name_VI}} - {{$order->district->getType().' '.$order->district->Name_VI}}
                            </div>
                            <div>
                                <span style="font-weight: bold">Điện thoại: </span> {{$order->phone}}
                            </div>
                            <div>
                                <span style="font-weight: bold">Ghi chú: </span> {{$order->phone}}
                            </div>
                        </div>
                    </td>
                    <td style="width: 50%; padding-left: 15px; height: 100%; font-size: 12px; padding-top: 15px">
                        <div style="border: 1px solid #d1d1d1; border-radius: 5px; padding: 10px; height: 210px">
                            <div style="font-weight: bold; padding: 10px 0; border-bottom: 1px solid #d1d1d1;">Hình thức thanh toán</div>
                            <div>
                                {{$payment_types[$order->payment_type]}}
                            </div>

                            @if(isset($order->bank))
                                <div>
                                    Thông tin chuyển khoản: {{@$order->bank->getInfor()}}
                                </div>
                            @endif
                            <div>
                                Trạng thái: <span style="color: #10b710">{{$order->status()}}</span>
                            </div>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center"><a href="{{route('admin.order',['code' => @$order->code])}}" style="color: #fff; background-color: #008aff; border-radius: 18px; line-height: 36px ; display: inline-block; margin: 25px auto 50px; padding: 0 25px; text-decoration: none">Xem đơn hàng</a></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr class="copyright">
        <td align="center">
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="640" style="text-align: center">
                <tr style="color: #464646;text-align: center; font-weight: bold">
                    <td style="padding-top: 10px">
                        Copyright © {{\Lib::dateFormat(time(),'Y')}} - Ohlala
                    </td>
                </tr>
                <tr style="color: #8b8b8b">
                    <td>
                        {!! $def['address'] !!}
                    </td>
                </tr>
                <tr style="font-size: 13px; color: #878787">
                    <td>Liên hệ với chúng tôi</td>
                </tr>
                <tr>
                    <td>
                        <a href="{{$def['facebook']}}"><img src="{{asset('html/html-vegfruit/images/icon-fb.png')}}"/></a>
                        <a href="tel:{{ $def['hotline'] }}"><img src="{{asset('html/html-vegfruit/images/icon-zalo.png')}}"/></a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</td>
</tr>
</tbody>
</table>

<style>
    *{
        padding: 0;
        margin: 0;
    }
</style>



</body>
</html>