@extends('BackEnd::layouts.default')

@section('breadcrumb') {!! \Lib::renderBreadcrumb(false, true) !!} @stop

@section('content')
    <div class="row">
        @if(empty($data))
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">Cảnh báo!</h4>
                <p>Không tìm thấy đơn hàng hoặc đơn hàng đã bị xóa</p>
                <hr>
                <p class="mb-0" align="right">
                    <a class="btn btn-outline-warning" href="{{ redirect()->back()->getTargetUrl() }}"><i class="fa fa-angle-left"></i>&nbsp; Quay lại</a>
                </p>
            </div>
        @else
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">THÔNG TIN ĐƠN HÀNG</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <p>Mã đơn hàng: #<b>{{$data->code}}</b></p>
                                {{--<p>Loại đơn hàng: {{$data->typeName() }}</p>--}}
                                <p>Thời gian đặt: {{ \Lib::dateFormat($data->created, 'd/m/Y H:i') }}</p>
                                @if($data->price > 0)
                                <p>Tổng tiền: <b>{{ \Lib::priceFormatEdit($data->price)['price']}}<sup class="text-danger">đ</sup></b></p>
                                @endif
                                <p>Trạng thái: <b>{{ $data->status() }}</b></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">THÔNG TIN KHÁCH HÀNG</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-2">Họ và Tên:</div>
                            <div class="col-sm-10">{{ $data->fullname }}</div>
                        </div>
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
                        @if(!empty($data->company_name))
                            <div class="row mt-3">
                                <div class="col-sm-2">Cơ quan:</div>
                                <div class="col-sm-10">{{ $data->company_name }}</div>
                            </div>
                        @endif

                        {{--<div class="row mt-3">--}}
                            {{--<div class="col-sm-2">Nơi đến:</div>--}}
                            {{--<div class="col-sm-10">{{ @$type_addresses[$data->type_address] }}</div>--}}
                        {{--</div>--}}

                        {{--<div class="row mt-3">--}}
                            {{--<div class="col-sm-2">Lưu ý giao hàng:</div>--}}
                            {{--<div class="col-sm-10">{{ @$shipping_notices[$data->shipping_notice] }}</div>--}}
                        {{--</div>--}}

                        @if($data->type == 'order')
                            <div class="row mt-3">
                                <div class="col-sm-2">Địa chỉ:</div>
                                <div class="col-sm-10">{{ $data->address }} - Phường {{ @$data->ward->Name_VI }} - Quận {{ @$data->district->Name_VI }} - {{ @$data->province->Name_VI }}</div>
                            </div>
                        @endif
                        @if(!empty($data->note))
                            <div class="row mt-3">
                                <div class="col-sm-2">Note:</div>
                                <div class="col-sm-10">{{ $data->note }}</div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="card">
                    <div class="card-header">THÔNG TIN CHI TIẾT</div>
                    <div class="card-body">
                        @include('BackEnd::pages.order.view_'.$data->type, ['data' => $data])
                    </div>
                </div>

                <div class="mb-3">
                    <a class="btn btn-sm btn-danger" href="{{ redirect()->back()->getTargetUrl() }}"><i class="fa fa-ban"></i> Quay lại</a>
                </div>
            </div>
        @endif
    </div>
@stop