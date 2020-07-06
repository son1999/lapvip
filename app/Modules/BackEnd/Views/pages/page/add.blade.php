@extends('BackEnd::layouts.default')

@section('content')
    <div class="row" id="app">
        <div class="col-sm-12">
        {!! Form::open(['url' => route('admin.'.$key.'.add.post')]) !!}

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
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Tiêu đề</label>
                                <input v-model="input"  type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old('title') }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title_seo">Tiêu đề SEO</label>
                                <input  type="text" class="form-control{{ $errors->has('title_seo') ? ' is-invalid' : '' }}" id="title_seo" name="title_seo" value="{{ old('title_seo') }}">
                            </div>
                        </div>

                    </div>


                    <div class="row">
                        <div class="col-sm-12">
                            <label for="alias">Alias</label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3">https://leather.todo.vn/</span>
                                </div>
                                <input :value="slug" type="text" name="alias" class="form-control" id="basic-url" aria-describedby="basic-addon3">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="link_seo">Sắp xếp</label>
                                <input type="text" class="form-control{{ $errors->has('sort') ? ' is-invalid' : '' }}" id="sort" name="sort" value="{{ old('sort') }}">
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="type">Phân loại</label>
                                <select id="type" name="type" class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}">
                                    @foreach($type as $k => $v)
                                        <option value="{{ $k }}" @if(old('type') == $k) selected="selected" @endif>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="lang">Ngôn ngữ</label>
                                <select id="lang" name="lang" class="form-control{{ $errors->has('lang') ? ' is-invalid' : '' }}">
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
@stop

@section('css')
    {!! \Lib::addMedia('admin/js/library/uploadifive/uploadifive.css') !!}
@stop

@section('js_bot')
    {!! \Lib::addMedia('admin/js/library/uploadifive/jquery.uploadifive.min.js') !!}
    {!! \Lib::addMedia('admin/js/library/uploadifive/multiupload.js') !!}
    {!! \Lib::addMedia('admin/js/library/ckeditor/ckeditor.js') !!}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>

    <script type="text/javascript">
        const app = new Vue({
            el: '#app',

            data: {
                input: ''
            },
            computed: {
                slug: function () {
                    return this.slugify(this.input)
                }
            },

            methods: {
                slugify (title) {
                    var slug = "";
                    // Change to lower case
                    var titleLower = title.toLowerCase();
                    slug = titleLower.replace(/e|é|è|ẽ|ẻ|ẹ|ê|ế|ề|ễ|ể|ệ/gi, 'e');
                    slug = slug.replace(/a|á|à|ã|ả|ạ|ă|ắ|ằ|ẵ|ẳ|ặ|â|ấ|ầ|ẫ|ẩ|ậ/gi, 'a')
                        .replace(/o|ó|ò|õ|ỏ|ọ|ô|ố|ồ|ỗ|ổ|ộ|ơ|ớ|ờ|ỡ|ở|ợ/gi, 'o')
                        .replace(/u|ú|ù|ũ|ủ|ụ|ư|ứ|ừ|ữ|ử|ự/gi, 'u')
                        .replace(/ị|í|ì|ỉ|ĩ/gi, 'i')
                        .replace(/ý|ỵ|ỳ|ỷ|ỹ/gi, 'y')
                        .replace(/đ/gi, 'd')
                        .replace(/\s*$/g, '')
                        .replace(/\s+/g, '-');
                    return slug;
                }
            }
        });
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

            shop.multiupload('body');
        }, true);
    </script>
@stop