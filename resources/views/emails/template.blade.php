<table cellpadding="0" cellspacing="0" bgcolor="#ffffff" align="center" style="width: 100%; max-width: 699px; padding: 0;font-size: 13px;line-height: 14px;color: #585858;font-family: Arial , sans-serif; border: 1px solid #c3c3c3">
    <tbody>

    <tr>
        <td height="144">
            <div style="width: 100%;height: 144px;padding: 0;margin:0;background:#fff no-repeat top center">
            </div>
        </td>
    </tr>

    <tr style="background: #fff;">
        <td>@yield('content')</td>
    </tr>

    <tr>
        <td>
            <table cellpadding="0" cellspacing="0" border="0" style="width: 100%;height: 150px;padding: 0;background:#f6fcfe no-repeat center center;position: relative;">
                <tbody>
                <tr>
                    <td width="20"></td>
                    <td valign="bottom">
                        <div style="text-transform: uppercase; font-weight: 700;margin-bottom: 16px;">
                            <a href="{{ env('APP_URL') }}">{{ env('APP_NAME') }}</a></div>
                        <table cellpadding="0" cellspacing="0" border="0">
                            <tr>
                                <td valign="top" align="center">
                                    <img src="{{asset('images/email/location.png')}}" alt=""/>
                                </td>
                                <td>
                                    <div style="margin-left: 11px;line-height: 20px;margin-bottom: 10px;">
                                        <span style="font-weight: 700;">Địa chỉ:</span> {!!  $def['address'] !!}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" align="center">
                                    <img src="{{asset('images/email/email.png')}}" alt=""/>
                                </td>
                                <td>
                                    <div style="margin-left: 11px;line-height: 20px;margin-bottom: 10px;">
                                        <span style="font-weight: 700;">Email:</span> {{ $def['email'] }}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" align="center">
                                    <img src="{{asset('images/email/phone.png')}}" alt=""/>
                                </td>
                                <td>
                                    <div style="margin-left: 11px;line-height: 20px;margin-bottom: 10px;">
                                        <span style="font-weight: 700;">Hotline:</span> {{ $def['hotline'] }}
                                    </div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="40"></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>