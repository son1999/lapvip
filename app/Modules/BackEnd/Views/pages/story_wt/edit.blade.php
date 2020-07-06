@extends('BackEnd::layouts.default')

@section('content')
    <div class="row" id="app">
        <div class="col-sm-12">
            {!! Form::open(['url' => route('admin.'.$key.'.edit.post', $data->id),'files' => true ]) !!}

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

            {{--thông tin cơ bản--}}
            <div class="card" id="slug">
                <div class="card-header">
                    <i class="fe-menu"></i>THÔNG TIN CƠ BẢN
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Tiêu đề</label>
                                <input v-model="input"  type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old('title', $data->title) }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title_seo">Tiêu đề SEO</label>
                                <input  type="text" class="form-control{{ $errors->has('title_seo') ? ' is-invalid' : '' }}" id="title_seo" name="title_seo" value="{{ old('title_seo', $data->title_seo) }}">
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
                                <input :value="slug" type="text" name="alias" class="form-control" id="basic-url" value="{{old('alias', $data->alias)}}" aria-describedby="basic-addon3">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="link_seo">Sắp xếp</label>
                                <input type="text" class="form-control{{ $errors->has('sort') ? ' is-invalid' : '' }}" id="sort" name="sort" value="{{ old('sort', $data->sort) }}">
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
            {{--banner--}}
            <div class="card">
                <div class="card-header">
                    <i class="fe-image"></i>Banner
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Tiêu đề Banner</label>
                                <input type="text" class="form-control{{ $errors->has('title_banner') ? ' is-invalid' : '' }}" id="title_banner" name="title_banner" value="{{ old('title_banner', $data->title_banner) }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Upload Banner</label><br>
                                <input type="file" id="image" name="image">
                                @if(!empty($data->image))
                                    <div class="pull-right">
                                        <img data-src="{{ $data->getImageUrl('small') }}" class="lazyload" />
                                    </div>
                                @endif
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            {{--intro--}}
            <div class="card">
                <div class="card-header">
                    <i class="fe-align-left"></i>Intro
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Tiêu đề Intro</label>
                                <input type="text" class="form-control{{ $errors->has('title_intro') ? ' is-invalid' : '' }}" id="title_intro" name="title_intro" value="{{ old('title_intro', $data->title_intro) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="title">Description</label>
                                <input type="text" class="form-control{{ $errors->has('description_intro') ? ' is-invalid' : '' }}" id="description_intro" name="description_intro" value="{{ old('description_intro',$data->description_intro) }}" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="link">Nội dung</label>
                                <textarea class="form-control{{ $errors->has('content_intro') ? ' is-invalid' : '' }}" id="content_intro" name="content_intro">
                            {{ old('content_intro',$data->content_intro) }}
                        </textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="display: none">
                    <div class="col-sm-12">
                        <div class="imgContainer">
                            <div id="queue"></div>
                            <input id="uploadintro" name="uploadintro" type="file" multiple="true">
                        </div>
                    </div>
                </div>
            </div>
            {{--store--}}
            <div class="card">
                <div class="card-header">
                    <i class="fe-inbox"></i>Store
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Tiêu đề Store</label>
                                <input type="text" class="form-control{{ $errors->has('title_store') ? ' is-invalid' : '' }}" id="title_store" name="title_store" value="{{ old('title_store', $data->title_store) }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="link">Nội dung</label>
                                <textarea class="form-control{{ $errors->has('content_store') ? ' is-invalid' : '' }}" id="content_store" name="content_store">
                        {{ old('content_store', $data->content_store) }}
                    </textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row" style="display: none">
                        <div class="col-sm-12">
                            <div class="imgContainer">
                                <div id="queue"></div>
                                <input id="uploadstore" name="uploadstore" type="file" multiple="true">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--ptroduct--}}
            <div class="card">
                <div class="card-header">
                    <i class="fe-layers"></i>Products
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Tiêu đề Product</label>
                                <input v-model="input"  type="text" class="form-control{{ $errors->has('title_product') ? ' is-invalid' : '' }}" id="title_product" name="title_product" value="{{ old('title_product', $data->title_product) }}" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="title">Upload hình ảnh sản phẩm</label>
                                @include('BackEnd::pages.story_wt.gallary.gallary_product', ['object_id' => old_blade('id')])
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            {{--slide--}}
            <div class="card">
                <div class="card-header">
                    <i class="fe-image"></i>Slide
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="title">Upload hình ảnh sản phẩm</label>
                                @include('BackEnd::pages.story_wt.gallary.gallary_slide', ['object_id' => old_blade('id')])
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{--CELEBRITIES--}}
            <div class="card" id="celebrities">
                <div class="card-header">
                    <i class="fe-image"></i>Celebrities
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <form accept-charset="UTF-8" enctype="multipart/form-data">
                                <input type="hidden" name="_tokens" value="{{ csrf_token() }}">
                                {{--                                @method('PUT')--}}
                                <div class="form-group">
                                    <label for="title">Upload Celebrities</label>
                                    <div class="row">
                                        <div class="col-sm-4">
                                            <div class="form-group">
                                                <label for="title">Image</label><br>
                                                <input class="form-control"  type="file" id="img" name="img">
                                            </div>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="form-group">
                                                <label for="title">Name</label><br>
                                                <input class="form-control" type="text" id="name" name="name">
                                            </div>
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="form-group">
                                                <a onclick="shop.admin.celebrities()" class="btn btn-outline-primary">Push</a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card">
                                        <div class="card-header">
                                            <i class="fe-user"></i>Info Celebrities
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <input type="hidden" id="gallery-object_id_cele" value="{{$data->id}}">
                                                <div class="gallery" id="gallery">
                                                    <ul v-if="showListCe">
                                                        <li class="image-item" v-for="item in celebrities" :data-id="item.id">
                                                            <img class="test" :src="item.image_sm" />
                                                            <div class="actions">
                                                                <span class="text-light">@{{item.name}}</span>
                                                                <br><br><br>
                                                                <a href="javascript:void(0)" @click="imageDel(item)" title="Xóa ảnh"><i class="fa fa-trash"></i></a>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
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

    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>

    <script type="text/javascript">
        var news = {!! json_encode($data) !!};
        const slug = new Vue({
            el: '#slug',

            data: {
                input: news.title,
                alias: news.alias,
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
            shop.admin.system.ckEditor('content_intro', 100 + '%', 500, 'moono',[
                ['Undo','Redo','-'],
                ['Bold','Italic','Underline','Strike'],
                ['Link','Unlink','Anchor'],['Image','Youtube','Table'],
                ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
                '/',
                ['Font','FontSize'],
                ['TextColor','BGColor','SelectAll','RemoveFormat'],['PasteFromWord','PasteText'],['Subscript','Superscript','SpecialChar'],['Source'],['SuperButton']
            ],false,'uploadintro');
            shop.multiupload('content_intro');

            shop.admin.system.ckEditor('content_store', 100 + '%', 500, 'moono',[
                ['Undo','Redo','-'],
                ['Bold','Italic','Underline','Strike'],
                ['Link','Unlink','Anchor'],['Image','Youtube','Table'],
                ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
                '/',
                ['Font','FontSize'],
                ['TextColor','BGColor','SelectAll','RemoveFormat'],['PasteFromWord','PasteText'],['Subscript','Superscript','SpecialChar'],['Source'],['SuperButton']
            ],false,'uploadstore');
            shop.multiupload2('content_store');

        }, true);
    </script>
@stop

@push('js_bot_all')
    <script type="text/javascript">
        var loadCelebrities = {
            object_id: $('#gallery-object_id_cele').val(),
            type: 'celebrities',
            images: null,
            viewMore: false,
            celebrities:[]
        };
    </script>
    {!! \Lib::addMedia('admin/js/story_gallery/celebrities_story_gallarey.js') !!}
@endpush