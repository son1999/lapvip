@extends('BackEnd::layouts.default')

@section('content')
    <div class="row" id="slug-alias">
        <div class="col-sm-12">
            {!! Form::open(['url' => route('admin.'.$key.'.add.post'), 'files' => true]) !!}

            @if( count($errors) > 0)
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{!! $error !!}</div>
                    @endforeach
                </div>
            @endif

            <div class="card">
                <div class="card-header">
                    <i class="fe-menu"></i>THÔNG TIN CƠ BẢN
                </div>
                <div class="card-body">
                    <div class="row" id="slug-alias">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Tiêu đề, tên</label>
                                <input v-model="input" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old('title') }}" required>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <label for="alias">Alias</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3">{{env('APP_NAME')}}</span>
                                </div>
                                <input :value="slug" type="text" name="alias" class="form-control" id="basic-url" aria-describedby="basic-addon3">
                            </div>
                        </div>

                    </div>
                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="cat_id">Danh mục sản phẩm muốn hiển thị</label>
                                <select id="cat_id" name="category_id[]" required multiple="multiple" style="width:100%" class="form-control{{ $errors->has('category_id') ? ' is-invalid' : '' }}">
                                    @include('BackEnd::pages.category.option', [
                                        'options' => $catOpt,
                                        'def' => old('cat_id'),
                                        'mode' => 1
                                    ])
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="published">Ngày xuất bản</label>
                                <input type="text" autocomplete="off" class="form-control{{ $errors->has('published') ? ' is-invalid' : '' }}" id="published" name="published" value="{{ old('published') }}" placeholder="dd/mm/yyyy">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="lang">Ngôn ngữ</label>
                                <select id="lang" name="lang" class="form-control{{ $errors->has('lang') ? ' is-invalid' : '' }}" onchange="shop.admin.getCat(2, $('#cat_id').val(), this.value, '#cat_id')">
                                    @foreach($langOpt as $k => $v)
                                        <option value="{{ $k }}" @if(old('lang') == $k) selected="selected" @endif>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fa fa-newspaper-o"></i>NỘI DUNG CHÍNH
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="link">Mô tả ngắn</label>
                                <textarea class="form-control{{ $errors->has('sort_body') ? ' is-invalid' : '' }}" id="sort_body" name="sort_body">
                                    {{ old('sort_body') }}
                                </textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="link">Nội dung</label>
                                <textarea class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" id="body" name="body">
                                    {{ old('body') }}
                                </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="imgContainer">
                                <div id="queue"></div>
                                <input id="uploadify" name="uploadify" type="file" multiple="true">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Thêm mới</button>
                &nbsp;&nbsp;
                <a class="btn btn-sm btn-danger" href="{{ redirect()->back()->getTargetUrl() }}"><i class="fa fa-ban"></i> Hủy bỏ</a>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
@stop

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
    {!! \Lib::addMedia('admin/js/library/tag/jquery.tag-editor.css') !!}
    {!! \Lib::addMedia('admin/js/library/uploadifive/uploadifive.css') !!}
@stop

@section('js_bot')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
    {!! \Lib::addMedia('admin/js/vue.js') !!}
    {!! \Lib::addMedia('admin/js/library/uploadifive/jquery.uploadifive.min.js') !!}
    {!! \Lib::addMedia('admin/js/library/uploadifive/multiupload.js') !!}
    {!! \Lib::addMedia('admin/js/library/ckeditor/ckeditor.js') !!}
    {!! \Lib::addMedia('admin/js/library/tag/jquery.caret.min.js') !!}
    {!! \Lib::addMedia('admin/js/library/tag/jquery.tag-editor.min.js') !!}
    {!! \Lib::addMedia('admin/js/library/slug.js') !!}

    <script type="text/javascript">
        $("#cat_id").select2({
            width: 'resolve',
        });
        shop.ready.add(function(){
            shop.admin.system.ckEditor('body', 100 +'%', 500, 'moono',[
                ['Undo','Redo','-'],
                ['Bold','Italic','Underline','Strike'],
                ['Link','Unlink','Anchor'],['Image','Youtube','Table'],
                ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
                '/',
                ['Font','FontSize', 'Format'],
                ['TextColor','BGColor','SelectAll','RemoveFormat'],['PasteFromWord','PasteText'],['Subscript','Superscript','SpecialChar'],['Source'],['ImgUploadBtn']
            ],false,'uploadify');
            shop.multiupload_ele('body','','#uploadify');
            shop.admin.system.ckEditor('sort_body',  100 +'%', 200, 'moono',[
                ['Undo','Redo','-'],
                ['Bold','Italic','Underline','Strike'],
                ['Link','Unlink'],
                ['Font','FontSize', 'Format'],
                ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
                ['Subscript','Superscript','SpecialChar']
            ]);
            $('#published').datepicker({ dateFormat: 'dd/mm/yy' });
            shop.multiupload('body');
            @if(\Lib::can($permission, 'tag'))
                shop.admin.tags.init(1, '#tags', 0);
            @endif
            {{--shop.admin.getCat(2, {{ old('cat_id', 0) }}, '{{ old('lang', '') }}', '#cat_id');--}}
        }, true);
    </script>

@stop