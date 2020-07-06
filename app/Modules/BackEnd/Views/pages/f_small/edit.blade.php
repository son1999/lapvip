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
                        <div class="col-sm-12">
                            <label for="title">Tiêu đề</label>
                            <div class="row">
                                <div class="col-sm-4 ">
                                    <label for="title"><small>Small Title</small></label>
                                    <input type="text" class="form-control{{ $errors->has('small_title_top') ? ' is-invalid' : '' }}" id="small_title_top" name="small_title_top" value="{{ old_blade('small_title_top') }}" >
                                </div>
                                <div class="col-sm-4 ">
                                    <label for="title"><strong>Big Title</strong></label>
                                    <input type="text" class="form-control{{ $errors->has('big_title') ? ' is-invalid' : '' }}" id="big_title" name="big_title" value="{{ old_blade('big_title') }}" >
                                </div>
                                <div class="col-sm-4 ">
                                    <label for="title"><small>Small Title</small></label>
                                    <input type="text" class="form-control{{ $errors->has('small_title_bottom') ? ' is-invalid' : '' }}" id="small_title_bottom" name="small_title_bottom" value="{{ old_blade('small_title_bottom') }}"    >
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Link</label>
                                <input type="text" class="form-control{{ $errors->has('link') ? ' is-invalid' : '' }}" id="link" name="link" value="{{ old_blade('link') }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="link">Ngôn ngữ</label>
                                <select id="lang" name="lang" class="form-control{{ $errors->has('lang') ? ' is-invalid' : '' }}">
                                    @foreach($langOpt as $k => $v)
                                        <option value="{{ $k }}" @if(old_blade('lang') == $k) selected="selected" @endif>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="link">Mô tả ngắn</label>
                                <textarea class="form-control{{ $errors->has('description') ? ' is-invalid' : '' }}" id="description" name="description">{!! old_blade('description') !!} </textarea>

                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="link">Nội dung</label>
                                <textarea class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" id="body" name="body">
                                {!! old_blade('body') !!}
                            </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="display: none">
                        <div class="col-sm-12">
                            <div class="imgContainer">
                                <div id="queue"></div>
                                <input id="uploadify" name="uploadify" type="file" multiple="true">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">Vị trí hiển thị</div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                @php($positions = old_blade('positions'))
                                @foreach($options as $k => $r)
                                    <div class="radio">
                                        <label for="radio{{ $k }}">
                                            <input type="radio" id="radio{{ $k }}" name="positions" value="{{ $k }}"{{ str_contains($positions, $k) ? ' checked' : '' }}>&nbsp; {{ $r }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fa fa-image"></i>ẢNH
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="file" id="image" name="image">
                            @if(!empty($data->image))
                                <div class="pull-right">
                                    <img src="{{ $data->getImageUrl('small') }}" />
                                </div>
                            @endif
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
@section('css')
    {!! \Lib::addMedia('admin/js/library/uploadifive/uploadifive.css') !!}
@stop
@section('js_bot')
    {!! \Lib::addMedia('admin/js/library/uploadifive/jquery.uploadifive.min.js') !!}
    {!! \Lib::addMedia('admin/js/library/uploadifive/multiupload.js') !!}
    {!! \Lib::addMedia('admin/js/library/ckeditor/ckeditor.js') !!}
    <script type="text/javascript">
        shop.ready.add(function(){
            shop.admin.system.ckEditor('body', 100 + '%', 500, 'moono',[
                ['Undo','Redo','-'],
                ['Bold','Italic','Underline','Strike'],
                ['Link','Unlink','Anchor'],['Image','Youtube','Table'],
                ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
                '/',
                ['Font','FontSize'],
                ['TextColor','BGColor','SelectAll','RemoveFormat'],['PasteFromWord','PasteText'],['Subscript','Superscript','SpecialChar'],['Source'],['SuperButton']
            ],false,'uploadify');
            // shop.admin.system.ckEditor('description', 100 + '%', 200, 'moono',[
            //     ['Undo','Redo','-'],
            //     ['Bold','Italic','Underline','Strike'],
            //     ['Link','Unlink'],
            //     ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
            //     ['Subscript','Superscript','SpecialChar']
            // ]);
            shop.multiupload('body');
        }, true);
    </script>
@endsection
