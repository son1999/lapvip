@extends('BackEnd::layouts.default')

@section('content')
    <div class="row" id="app">
        <div class="col-sm-12">
            {!! Form::open(['url' => route('admin.'.$key.'.edit.post', $data->id), 'files' => true ]) !!}

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
                    <div class="row" id="slug-alias">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="title">Tiêu đề, tên</label>
                                <input v-model="input" @change="slug" type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old('title', $data->title) }}" required>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">
                            <label for="alias">Alias</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3">{{env('APP_URL')}}/</span>
                                </div>
                                <input :value="alias" type="text" name="alias" class="form-control{{ $errors->has('alias') ? ' is-invalid' : '' }}" id="alias" name="alias" autocomplete="off" value="{{ old('alias', $data->alias) }}" aria-describedby="basic-addon3">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="title">Tags</label>
                                @if(\Lib::can($permission, 'tag'))
                                    <input type="text" class="form-control" id="tags" name="tags" value="{{ old('tags', $tags) }}">
                                @else
                                    <input type="text" class="form-control text-danger" value="Chưa có quyền gán Tag" disabled>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="cat_id">Danh mục tin</label>
                                <select  id="cat_id" name="cat_id" class="form-control{{ $errors->has('cat_id') ? ' is-invalid' : '' }}">
                                    <option value="0" selected>--- Chọn Danh Mục Tin ---</option>
                                    @foreach($catOpt as $k => $v)
                                        <option value="{{ $v['id'] }}" @if(old('cat_id', $data->cat_id) == $v['id']) selected="selected" @endif>{{ $v['title'] }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="link">Ngày xuất bản</label>
                                <input type="text" autocomplete="off" class="form-control{{ $errors->has('published') ? ' is-invalid' : '' }}" id="published" name="published" value="{{ old('published', \Lib::dateFormat($data->published, 'd/m/Y')) }}" placeholder="dd/mm/yyyy">
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="form-group">
                                <label for="link">Ngôn ngữ</label>
                                <select id="lang" name="lang" class="form-control{{ $errors->has('lang') ? ' is-invalid' : '' }}" onchange="shop.admin.getCat(2, $('#cat_id').val(), this.value, '#cat_id')">
                                    @foreach($langOpt as $k => $v)
                                        <option value="{{ $k }}" @if(old('lang', $data->lang) == $k) selected="selected" @endif>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fa fa-image"></i>ẢNH ĐẠI DIỆN
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
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-image"></i>Tin đính kèm | &nbsp;
                    <i class="text-danger">Nhập ID của Tin tức </i> | &nbsp;
                    <i class="text-danger">Hiển thị tối đa 3 Tin tức</i> | &nbsp;
                    <i class="text-danger">Mỗi ID cách nhau bởi dấu phẩy (,)</i>
                </div>
                <div class="card-body">
                    <div class="row">

                        <div class="col-sm-12">
                            <input type="text" class="form-control" id="id_news" name="id_news" value="{{old('id_news', $data->id_news)}}">
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
                                {{ old('sort_body', $data->sort_body) }}
                            </textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="link">Nội dung</label>
                                <textarea class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" id="body" name="body">
                                {{ old('body', $data->body) }}
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

{{--            <div class="card">--}}
{{--                <div class="card-header">--}}
{{--                    <i class="fe-youtube"></i>Video (dành cho Video Hot)--}}
{{--                </div>--}}
{{--                <div class="card-body">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-sm-12">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="link_youtube">Links Youtube</label>--}}
{{--                                <input type="text" class="form-control" name="links_video" id="links_video" placeholder="Links Youtube" value="{{old('links_video', $data->links_video) }}">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-image"></i>DÀNH CHO SEO
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="description">Title SEO</label>
                                <input type="text" class="form-control" id="title_seo" name="title_seo" value="{{ old('title_seo', $data->title_seo) }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="description">Description SEO</label>
                                <input type="text" class="form-control" id="description_seo" name="description_seo" value="{{ old('description_seo', $data->description_seo) }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="keywords">Keywords SEO</label>
                                <input type="text" class="form-control" id="keywords" name="keywords" value="{{ old('keywords', $data->keywords) }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="file" id="image_seo" name="image_seo">
                            <br />
                            <i>Ảnh vuông kích thước chiều ngang: 800x800px</i>
                            @if(!empty($data->image_seo))
                                <div class="pull-right">
                                    <img src="{{ $data->getImageSeoUrl('small') }}" />
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
    {!! \Lib::addMedia('admin/js/library/tag/jquery.tag-editor.css') !!}
    {!! \Lib::addMedia('admin/js/library/uploadifive/uploadifive.css') !!}
@stop

@section('js_bot')
    {!! \Lib::addMedia('admin/js/library/uploadifive/jquery.uploadifive.min.js') !!}
    {!! \Lib::addMedia('admin/js/library/uploadifive/multiupload.js') !!}
    {!! \Lib::addMedia('admin/js/library/ckeditor/ckeditor.js') !!}
    {!! \Lib::addMedia('admin/js/library/tag/jquery.caret.min.js') !!}
    {!! \Lib::addMedia('admin/js/library/tag/jquery.tag-editor.min.js') !!}
    {!! \Lib::addMedia('admin/js/library/slug.js') !!}

    <script type="text/javascript">
        Vue.config.devtools = true,
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