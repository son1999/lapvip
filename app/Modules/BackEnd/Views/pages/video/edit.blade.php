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
                        <div class="col-sm-8">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="title">Groups</label>
                                    <select name="gr_id" id="gr_id" class="form-control">
                                        <option value="" selected disabled>---Chọn---</option>
                                        @foreach($video_groups as $item_groups)
                                            <option value="{{$item_groups->id}}" @if(old_blade('gr_id') == $item_groups->id) selected @endif>{{$item_groups->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-6">
                                    <label for="title">Type</label>
                                    <select name="type" id="type" class="form-control">
                                        <option value="" selected disabled>---Chọn---</option>
                                        @foreach($catOpt as $item_cat)
                                            <option value="{{$item_cat['id']}}" @if(old_blade('type') == $item_cat['id']) selected @endif>{{$item_cat['title']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="title">Link</label>
                                        <input type="text" class="form-control{{ $errors->has('video_id') ? ' is-invalid' : '' }}" id="youtube_id" name="video_id" value="{{ old_blade('video_id') }}" placeholder="URL Video Youtube" onchange="shop.admin.previewVideo($(this).val())">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="embed-responsive embed-responsive-16by9">
                                <iframe class="embed-responsive-item" id="preview_videos" src="@if(!empty($data['video_id']))https://www.youtube.com/embed/{{ @$data['video_id'] }} @endif"></iframe>
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
