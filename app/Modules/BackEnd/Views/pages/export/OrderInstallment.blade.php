<html>
<style>
    td {
        text-align: center !important;
        width: auto !important;
        font-size: 13px;
        font-family: 'Times New Roman';
    }
</style>
<body>
<div class="row">
    <div class="col-lg-12">
        <div class="card card-accent-info" >
            <div class="card-body">
                <table border="1" style="border: 1px solid black; border-collapse: collapse" class="table table-striped">
                    <thead style="border: 1px solid black">
                        <tr>
                            <td colspan="27" style="text-align: center; font-weight: bold">DANH SÁCH KHÁCH HÀNG TRẢ GÓP</td>
                        </tr>
                        <tr>
                            <td bgcolor="#f0f8ff">STT</td>

                            <td bgcolor="#00ffff">ĐƠN HÀNG</td>
                            <td bgcolor="#00ffff">TÊN KHÁCH HÀNG</td>
                            <td bgcolor="#00ffff">NGÀY SINH</td>
                            <td bgcolor="#00ffff">CMTND</td>
                            <td bgcolor="#00ffff">SỐ ĐTDD</td>

                            <td bgcolor="#2ecc71">SẢN PHẨM</td>
                            <td bgcolor="#2ecc71">GIÁ</td>
                            <td bgcolor="#2ecc71">SỐ LƯỢNG</td>
                            <td bgcolor="#2ecc71">TỔNG TIỀN</td>

                            <td bgcolor="yellow">HÌNH THỨC</td>

                            <td bgcolor="#3498db">CÔNG TY TÀI CHÍNH</td>
                            <td bgcolor="#3498db">SỐ THÁNG TRẢ GÓP</td>
                            <td bgcolor="#3498db">SỐ TIỀN TRẢ TRƯỚC</td>
                            <td bgcolor="#3498db">CHÊNH LỆCH VỚI MUA TRẢ  THẲNG</td>
                            <td bgcolor="#3498db">SỐ TIỀN GÓP MỖI THÁNG</td>
                            <td bgcolor="#3498db">TỔNG CHI PHÍ</td>

                            <td bgcolor="#1abc9c">NGÂN HÀNG</td>
                            <td bgcolor="#1abc9c">THẺ THANH TOÁN</td>
                            <td bgcolor="#1abc9c">SỐ TIỀN TRẢ QUA THẺ</td>
                            <td bgcolor="#1abc9c">SỐ THÁNG</td>
                            <td bgcolor="#1abc9c">SỐ TIỀN GÓP MỖI THÁNG</td>
                            <td bgcolor="#1abc9c">PHÍ CHUYỂN ĐỔI SANG TRẢ GÓP</td>
                            <td bgcolor="#1abc9c">TỔNG TIỀN TRẢ GÓP</td>
                            <td bgcolor="#1abc9c">CHÊNH LỆCH VỚI MUA TRẢ THẲNG</td>
                            <td bgcolor="#1abc9c">SỐ TIỀN THANH TOÁN KHI NHẬN MÁY</td>

                            <td>TÌNH TRẠNG</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($data as $item)
                            <tr>
                                <td bgcolor="#f0f8ff">{{$loop->index+1}}</td>

                                <td bgcolor="#00ffff">#{{$item->id}}</td>
                                <td bgcolor="#00ffff">{{$item->name}}</td>
                                <td bgcolor="#00ffff">{{$item->date_of_birth}}</td>
                                <td bgcolor="#00ffff">{{$item->cmtnd}}</td>
                                <td bgcolor="#00ffff">{{$item->phone}}</td>
                                @if(!empty($item->product))
                                    <td bgcolor="#2ecc71">{{$item->product->title}}</td>
                                    <td bgcolor="#2ecc71"><span class="text-danger">{{\Lib::priceFormatEdit($item->product->price, '')['price']}} đ </span></td>
                                @endif
                                <td bgcolor="#2ecc71">{{$item->quan}}</td>
                                @if(!empty($item->product))
                                    <td bgcolor="#2ecc71"><span class="text-danger">{{\Lib::priceFormatEdit($item->product->price * $item->quan, '')['price']}} đ</span></td>
                                @endif
                                @if($item->type == 0)
                                    <td bgcolor="yellow">
                                        TRẢ GÓP QUA CÔNG TY TÀI CHÍNH
                                    </td>
                                @elseif($item->type == 1)
                                    <td bgcolor="yellow">
                                        TRẢ GÓP BẰNG THẺ VISA, MASTER
                                    </td>
                                @endif
                                @if($item->type == 0)
                                    <td bgcolor="#3498db">{{$item->installment_scenarios->company}}</td>
                                    <td bgcolor="#3498db">{{$item->month}} tháng</td>
                                    <td bgcolor="#3498db"><span class="text-danger">{{\Lib::priceFormatEdit($item->prepaid_amount,'')['price']}} đ</span></td>
                                    <td bgcolor="#3498db"><span class="text-danger">{{\Lib::priceFormatEdit($item->difference,'')['price']}} đ</span></td>
                                    <td bgcolor="#3498db"><span class="text-danger">{{\Lib::priceFormatEdit($item->monthly_installments, '')['price']}} đ</span></td>
                                    <td bgcolor="#3498db"><span class="text-danger">{{\Lib::priceFormatEdit($item->total_cost, '')['price']}} đ</span></td>
                                @else
                                    <td bgcolor="#3498db">---</td>
                                    <td bgcolor="#3498db">---</td>
                                    <td bgcolor="#3498db">---</td>
                                    <td bgcolor="#3498db">---</td>
                                    <td bgcolor="#3498db">---</td>
                                    <td bgcolor="#3498db">---</td>
                                @endif
                                @if($item->type == 1)
                                    <td bgcolor="#1abc9c">{{$item->bank}}</td>
                                    <td bgcolor="#1abc9c">{{$item->payment}}</td>
                                    <td bgcolor="#1abc9c"><span class="text-danger">{{\Lib::priceFormatEdit($item->money_paid_by_card, '')['price']}} đ</span></td>
                                    <td bgcolor="#1abc9c">{{$item->month}} tháng</td>
                                    <td bgcolor="#1abc9c"><span class="text-danger">{{\Lib::priceFormatEdit($item->monthly_installments,'')['price']}} đ</span>d>
                                    <td bgcolor="#1abc9c"><span class="text-danger">{{\Lib::priceFormatEdit($item->conversion_fee, '')['price']}} đ</span></td>
                                    <td bgcolor="#1abc9c"><span class="text-danger">{{\Lib::priceFormatEdit($item->total_cost, '')['price']}} đ</span></td>
                                    <td bgcolor="#1abc9c"><span class="text-danger">{{\Lib::priceFormatEdit($item->difference, '')['price']}} đ</span></td>
                                    <td bgcolor="#1abc9c"><span class="text-danger">{{\Lib::priceFormatEdit($item->payment_upon_receipt, '')['price']}} đ</span></td>
                                @else
                                    <td bgcolor="#1abc9c">---</td>
                                    <td bgcolor="#1abc9c">---</td>
                                    <td bgcolor="#1abc9c">---</td>
                                    <td bgcolor="#1abc9c">---</td>
                                    <td bgcolor="#1abc9c">---</td>
                                    <td bgcolor="#1abc9c">---</td>
                                    <td bgcolor="#1abc9c">---</td>
                                    <td bgcolor="#1abc9c">---</td>
                                    <td bgcolor="#1abc9c">---</td>
                                @endif
                                @if($item->status == -1)
                                    <td>Đơn hủy</td>
                                @elseif($item->status == 0)
                                    <td>Đơn mới</td>
                                @elseif($item->status == 1)
                                    <td>Đơn đã tiếp nhận</td>
                                @elseif($item->status == 2)
                                    <td>Đơn đang trong quá trình trả góp</td>
                                @elseif($item->status == 3)
                                    <td>Đơn hoàn thành</td>
                                @endif
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="4" bgcolor="yellow" >NGÀY GIỜ TẠO:</td>
                            <td colspan="23" style="background-color: #fed330;">{{\Illuminate\Support\Carbon::now()->toDateTimeString()}}</td>
                        </tr>
                        <tr>
                            <td colspan="4" bgcolor="yellow" >NGƯỜI TẠO:</td>
                            <td colspan="23"  style="background-color: #fed330;" >{{mb_strtolower(\Illuminate\Support\Facades\Auth::user()->fullname, "UTF-8")}}</td>
                        </tr>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>
</body>
</html>
