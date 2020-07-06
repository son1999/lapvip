@extends('BackEnd::layouts.default')

@section('breadcrumb') {!! \Lib::renderBreadcrumb(false, true) !!} @stop

@section('content')
    <div class="row" id="vue-order">
        @if(empty($data))
            <div class="alert alert-warning" role="alert">
                <h4 class="alert-heading">Cảnh báo!</h4>
                <p>Không tìm thấy câu hỏi của khách hàng cho sản phẩm hoặc câu hỏi đã bị xóa</p>
                <hr>
                <p class="mb-0" align="right">
                    <a class="btn btn-outline-warning" href="{{ redirect()->back()->getTargetUrl() }}"><i class="fa fa-angle-left"></i>&nbsp; Quay lại</a>
                </p>
            </div>
        @else
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header">THÔNG TIN CHI TIẾT CÂU HỎI</div>
                    <div class="card-body">
                        <div class="card-header">Thông tin sản phẩm</div>
                        <br>

                        <div class="row ml-2 mb-3">
                            <div class="col-sm-2"><strong>Name: </strong>&nbsp;</div>
                            <div class="col-sm-10">{{$pro->product['title']}}</div>
                        </div>
                        <div class="row ml-2 mb-3">
                            <div class="col-sm-2"><strong>Link: </strong>&nbsp;</div>
                            <div class="col-sm-10"><a href="{{route('admin.product',['id'=>$pro->product['id']] )}}">Sản Phẩm</a></div>
                        </div>
                        <hr>

                        <div class="card-header mt-3">Câu hỏi</div>
                        <br>
                        <div class="row ml-2 mb-3">
                            <div class="col-sm-2"><strong>Câu hỏi: </strong>&nbsp;</div>
                            <div class="col-sm-10">{{$data['question']}}</div>
                        </div>
                        <div class="row ml-2 mb-3">
                            <div class="col-sm-2"><strong>Trả lời: </strong>&nbsp;</div>
                            <div class="col-sm-10">
                                <input type="text" id="answer" class="form-control" value="{{$data['answer']}}">
                                <div align="right" class="mt-2 mr-4">
                                    <button class="btn btn-success" onclick="shop.admin.answer({{$data['id']}}, {{$pro->product['id']}}, $('#answer').val(), 'question')"><i class="fe-send"></i></button>
                                </div>
                            </div>
                        </div>
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