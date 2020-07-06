
@if($data->type == 0)
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Hình thức</th>
            <th>Công ty</th>
            <th>Số tháng</th>
            <th>Trả trước</th>
            <th>Chênh lệch </th>
            <th>Góp mỗi tháng</th>
            <th>Tổng</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td align="center">TRẢ GÓP QUA CÔNG TY TÀI CHÍNH</td>
            <td>{{$data->installment_scenarios->company}}</td>
            <td>{{$data->month}} tháng</td>
            <td>{{\Lib::priceFormatEdit($data->prepaid_amount,'')['price']}} đ</td>
            <td>{{\Lib::priceFormatEdit($data->difference,'')['price']}} đ </td>
            <td>{{\Lib::priceFormatEdit($data->monthly_installments, '')['price']}} đ </td>
            <td>{{\Lib::priceFormatEdit($data->total_cost, '')['price']}} đ </td>
        </tr>
        </tbody>
    </table>
@elseif($data->type == 1)
    <table class="table table-bordered table-striped">
        <thead>
        <tr>
            <th>Hình thức</th>
            <th>Ngân hàng</th>
            <th>Thẻ thanh toán</th>
            <th>Số tiền trả qua thẻ</th>
            <th>Số tháng</th>
            <th>Góp mõi tháng</th>
            <th>Phí chuyển đổi</th>
            <th>Chênh lệch</th>
            <th>Số tiền thanh toán khi nhận máy</th>
            <th>Tổng</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td align="center">TRẢ GÓP BẰNG THẺ VISA, MASTER</td>
            <td>{{$data->bank}}</td>
            <td>{{$data->payment}}</td>
            <td>{{\Lib::priceFormatEdit($data->money_paid_by_card, '')['price']}} đ</td>
            <td>{{$data->month}} tháng</td>
            <td>{{\Lib::priceFormatEdit($data->monthly_installments,'')['price']}} đ</td>
            <td>{{\Lib::priceFormatEdit($data->conversion_fee, '')['price']}} đ</td>
            <td>{{\Lib::priceFormatEdit($data->difference, '')['price']}} đ</td>
            <td>{{\Lib::priceFormatEdit($data->payment_upon_receipt, '')['price']}} đ</td>
            <td>{{\Lib::priceFormatEdit($data->total_cost, '')['price']}} đ</td>
        </tr>
        </tbody>
    </table>
@endif