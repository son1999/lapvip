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
                        <div class="card-header">Thông tin Câu hỏi</div>
                        <br>

                        <div class="row ml-2 mb-3">
                            <div class="col-sm-2"><strong>Người dùng: </strong>&nbsp;</div>
                            <div class="col-sm-10">{{$data->name}}</div>
                        </div>
                        <div class="row ml-2 mb-3">
                            <div class="col-sm-2"><strong>Email: </strong>&nbsp;</div>
                            <div class="col-sm-10">{{$data->email}}</div>
                        </div>
                        <div class="row ml-2 mb-3">
                            <div class="col-sm-2"><strong>Câu hỏi: </strong>&nbsp;</div>
                            <div class="col-sm-10">{{$data['question']}}</div>
                        </div>
                        <hr>
                        <div class="card-header mt-3">Câu trả lời BQT</div>
                        <br>
                        <div class="row ml-2 mb-3">
                            @foreach($answer as $item_user)
                                <div class="col-sm-2"><strong>{{$item_user->name}}</strong><i> (đã trả lời)</i></div>
                                <div class="col-sm-8">{{$item_user->answer}}</div>
{{--                                <div class="col-sm-2"><i class="icon-trash icons text-danger" onclick="return confirm('Bạn muốn xóa ?')"></i></div>--}}
                                <a href="{{ route('admin.'.$key.'.delete', $item_user->id) }}"  class="text-danger" onclick="return confirm('Bạn muốn xóa ?')"><i class="icon-trash icons"></i></a>
                            @endforeach
                        </div>
                        <div class="row ml-2 mb-3">
                            <div class="col-sm-2"><strong>Trả lời: </strong>&nbsp;</div>
                            <div class="col-sm-10">
                                <input type="text" id="answer" class="form-control" value="{{$data['answer']}}">
                                <div align="right" class="mt-2 mr-4">
                                    <button class="btn btn-success" onclick="shop.admin.answerQuestion({{$data['id']}}, $('#answer').val(), 'answer_question')"><i class="fe-send"></i></button>
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