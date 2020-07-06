@extends('BackEnd::layouts.default')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            @if(old_blade('editMode'))
                {!! Form::open(['url' => route('admin.'.$key.'.edit.post', old_blade('id')) , 'files' => true]) !!}
            @else
                {!! Form::open(['url' => route('admin.'.$key.'.add.post') , 'files' => true]) !!}
            @endif
            @if( count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{!! $error !!}</div>
                    @endforeach
                </div>
            @endif
            @if (session('status'))
                <div class="alert alert-success">
                    {!! session('status') !!}
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <i class="fe-menu-square-o"></i>  SỬA THÔNG TIN
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Tiêu đề</label>
                                <input type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old_blade('title') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="sort">Vị trí ( Nhỏ hơn thì lên đầu )</label>
                                <input type="text" class="form-control{{ $errors->has('sort') ? ' is-invalid' : '' }}" id="sort" name="sort" value="{{ old_blade('sort') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="type">Hiển thị Danh Mục đính kèm</label> <br>
                                <i class="text-danger">Nhập ID của Danh Mục </i> <br>
                                <i class="text-danger">Hiển thị tối đa 5 Danh Mục</i> <br>
                                <i class="text-danger">Mỗi ID cách nhau bởi dấu phẩy (,)</i>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <input type="text" class="form-control" id="id_sell" name="id_sell" placeholder="ID của sản phẩm, phân tách nhau bởi dấu phẩy (,)" value="{{old_blade('id_sell')}}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status">Hiển thi trang chủ hay không?</label>
                                <div>
                                    <input type="radio"  name="is_show_home" id="pshow_list" @if(@$data['is_show_home'] == 1)checked @endif value="1"> Có
                                    <input type="radio" name="is_show_home" id="pclose_list" @if(@$data['is_show_home'] == 0)checked @endif value="0"> Không
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row d-none" id="pslide" >
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status">Danh mục Sản phẩm trang chủ</label>
                                <div>
                                    <select id="pid" name="pid" class="form-control{{ $errors->has('pid') ? ' is-invalid' : '' }}">
                                        @include('BackEnd::pages.category.option', [
                                            'options' => $cat,
                                            'def' => old_blade('pid'),
                                            'mode' => 1
                                        ])
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status">Layouts Mobile Sản phẩm trang chủ</label>
                                <div>
                                    <input type="radio" name="layouts_mobile"  value="1" @if(@$data['layouts_mobile'] == 1)checked @endif> Layouts 1 sản phẩm
                                    <input type="radio" name="layouts_mobile" value="2" @if(@$data['layouts_mobile'] == 2)checked @endif> Layouts 2 sản phẩm
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status">Hiển thi Slide trang chủ hay không?</label>
                                <div>
                                    <input type="radio" id="show_list" name="is_show_slide_home" @if(@$data['is_show_slide_home'] == 1)checked @endif value="1"> Có
                                    <input type="radio" id="close_list" name="is_show_slide_home" @if(@$data['is_show_slide_home'] == 0)checked @endif value="0"> Không
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row d-none" id="slide" >
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status">Danh mục Slide</label>
                                <div>
                                    <select id="cid" name="cid" class="form-control{{ $errors->has('pid') ? ' is-invalid' : '' }}">
                                        @include('BackEnd::pages.category.option', [
                                            'options' => $cat,
                                            'def' => old_blade('cid'),
                                            'mode' => 1
                                        ])
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Cập nhật</button>
                &nbsp;&nbsp;
                <a class="btn btn-sm btn-danger" href="{{ redirect()->back()->getTargetUrl() }}"><i class="fa fa-ban"></i> Hủy bỏ</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop
@section('js_bot')
    <script>
        $(window).bind('load', function(){
            if ($('#pshow_list').is(':checked')){
                $('#pslide').removeClass('d-none');
            }
            else {
                $('#pslide').addClass('d-none');
            }
            if ($('#show_list').is(':checked')){
                $('#slide').removeClass('d-none');
            }
            else {
                $('#slide').addClass('d-none');
            }
        });
        $('#pshow_list').click( () => {
            $('#pslide').removeClass('d-none');
        });
        $('#pclose_list').click( () => {
            $('#pslide').addClass('d-none');
        });

        $('#show_list').click( () => {
            $('#slide').removeClass('d-none');
        });
        $('#close_list').click( () => {
            $('#slide').addClass('d-none');
        });
    </script>
{{--    <script type="text/javascript">--}}
        {{--shop.ready.add(function(){--}}
        {{--    shop.getCat(1, 'vi', {{ old('pid', 0) != null ? old('pid', 0) : 0 }});--}}
        {{--},true);--}}
{{--        // shop.getCat = function (type, lang, def) {--}}
{{--        //     var html = '<option value="0">-- Chọn --</option>';--}}
{{--        //     shop.ajax_popup('category/get-cat', 'POST', {type:type, lang:lang}, function(json) {--}}
{{--        //         $.each(json.data,function (ind,value) {--}}
{{--        //             html += '<option value="'+value.id+'"'+(def == value.id?' selected':'')+'>'+value.title+'</option>';--}}
{{--        //             if(value.sub.length != 0){--}}
{{--        //                 $.each(value.sub,function (k,sub) {--}}
{{--        //                     html += '<option value="'+sub.id+'"'+(def == sub.id?' selected':'')+'> &nbsp;&nbsp;&nbsp; '+sub.title+'</option>';--}}
{{--        //                 });--}}
{{--        //             }--}}
{{--        //         });--}}
{{--        //         $('#pid').html(html);--}}
{{--        //     });--}}
{{--        // };--}}
{{--    </script>--}}
@endsection
