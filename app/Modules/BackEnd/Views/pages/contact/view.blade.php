@extends('BackEnd::layouts.default')

@section('breadcrumb') {!! \Lib::renderBreadcrumb(false, true) !!} @stop

@section('content')
    <div class="row" id="vue-order">
        @if(empty($data))
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">Cảnh báo!</h4>
                <p>Không tìm thấy thông tin khách hàng hoặc thông tin khách hàng hàng đã bị xóa</p>
                <hr>
                <p class="mb-0" align="right">
                    <a class="btn btn-outline-warning" href="{{ redirect()->back()->getTargetUrl() }}"><i class="fa fa-angle-left"></i>&nbsp; Quay lại</a>
                </p>
            </div>
        @else
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">THÔNG TIN KHÁCH HÀNG</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-2">Họ và Tên:</div>
                            <div class="col-sm-10">{{ $data->fullname }}</div>
                        </div>
{{--                        @if(!empty($data->gender))--}}
{{--                            <div class="row mt-3">--}}
{{--                                <div class="col-sm-2">Giới tính:</div>--}}
{{--                                <div class="col-sm-10">{{ $data->gender ? 'Nam' : 'Nữ' }}</div>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                        @if(!empty($data->date_of_birth))--}}
{{--                            <div class="row mt-3">--}}
{{--                                <div class="col-sm-2">Ngày sinh:</div>--}}
{{--                                <div class="col-sm-10">{{ \App\Libs\Lib::dateFormat($data->date_of_birth,'d/m/Y')}}</div>--}}
{{--                            </div>--}}
{{--                        @endif--}}
                        @if(!empty($data->email))
                            <div class="row mt-3">
                                <div class="col-sm-2">Email:</div>
                                <div class="col-sm-10">{{ $data->email }}</div>
                            </div>
                        @endif
                        @if(!empty($data->phone))
                            <div class="row mt-3">
                                <div class="col-sm-2">Điện thoại:</div>
                                <div class="col-sm-10">{{ $data->phone }}</div>
                            </div>
                        @endif
{{--                        @if(!empty($data->province))--}}
{{--                            <div class="row mt-3">--}}
{{--                                <div class="col-sm-2">Thảnh phố/Tỉnh:</div>--}}
{{--                                <div class="col-sm-10">{{ $province->Name_VI }}</div>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                        @if(!empty($data->district))--}}
{{--                            <div class="row mt-3">--}}
{{--                                <div class="col-sm-2">Quận/Huyện:</div>--}}
{{--                                <div class="col-sm-10">{{ $district->Name_VI }}</div>--}}
{{--                            </div>--}}
{{--                        @endif--}}
                        @if(!empty($data->description))
                            <div class="row mt-3">
                                <div class="col-sm-2">Description</div>
                                <div class="col-sm-10">{{ $data->description }}</div>
                            </div>
                        @endif
                        @if(!empty($data->code))
                            <div class="row mt-3">
                                <div class="col-sm-2">Mã Code</div>
                                <div class="col-sm-10">{{ $data->code }}</div>
                            </div>
                        @endif
                        @if(!empty($data->content))
                            <div class="row mt-3">
                                <div class="col-sm-2">Nội dung</div>
                                <div class="col-sm-10">{{ $data->content }}</div>
                            </div>
                        @endif
{{--                        @if(!empty($data->detail->oganization))--}}
{{--                            <div class="row mt-3">--}}
{{--                                <div class="col-sm-2">Cơ quan:</div>--}}
{{--                                <div class="col-sm-10">{{ $data->detail->oganization }}</div>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                        @if(!empty($data->detail->oganization_address))--}}
{{--                            <div class="row mt-3">--}}
{{--                                <div class="col-sm-2">Địa chỉ cơ quan:</div>--}}
{{--                                <div class="col-sm-10">{{ $data->detail->oganization_address }}</div>--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        @if(!empty($data->detail->total_income))--}}
{{--                            <div class="row mt-3">--}}
{{--                                <div class="col-sm-2">Tổng thu nhập:</div>--}}
{{--                                <div class="col-sm-10">{{ \Lib::priceFormat($data->detail->total_income) }}</div>--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        @if(!empty($data->detail->bank_disbursement))--}}
{{--                            <div class="row mt-3">--}}
{{--                                <div class="col-sm-2">Tên ngân hàng muốn giải ngân:</div>--}}
{{--                                <div class="col-sm-10">{{ $data->detail->bank_disbursement }}</div>--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        @if(!empty($data->detail->disbursement_digit))--}}
{{--                            <div class="row mt-3">--}}
{{--                                <div class="col-sm-2">Số tài khoản muốn giải ngân:</div>--}}
{{--                                <div class="col-sm-10">{{ $data->detail->disbursement_digit}}</div>--}}
{{--                            </div>--}}
{{--                        @endif--}}

{{--                        @if(!empty($data->detail->other_credit_institutions))--}}
{{--                            <div class="row mt-3">--}}
{{--                                <div class="col-sm-2">Có đang vay ở đâu không:</div>--}}
{{--                                <div class="col-sm-10">{{ $data->detail->other_credit_institutions ? 'Có' : 'Không' }}</div>--}}
{{--                            </div>--}}
{{--                        @endif--}}
                    </div>
                </div>


                <div class="mb-3">
                    <a class="btn btn-sm btn-danger" href="{{ redirect()->back()->getTargetUrl() }}"><i class="fa fa-ban"></i> Quay lại</a>
                </div>
            </div>
        @endif
    </div>
@stop
@section('js_bot')
@stop