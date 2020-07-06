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
            <div class="row">
                <div class="col-sm-8">
                    <div class="card">
                        <div class="card-header">
                            <i class="fe-menu-square-o"></i>  THÔNG TIN NHÂN VIÊN SUPPORTS
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="title">Name</label>
                                    <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="name" name="name" value="{{ old_blade('name') }}" >
                                </div>
                                <div class="col-sm-6">
                                    <label for="title">Position</label>
                                    <input type="text" class="form-control{{ $errors->has('position') ? ' is-invalid' : '' }}" id="position" name="position" value="{{ old_blade('position') }}" >
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="title">Email</label>
                                        <input type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" value="{{ old_blade('email') }}" placeholder="">
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <label for="title">Phone</label>
                                        <input type="phone" class="form-control{{ $errors->has('phone') ? ' is-invalid' : '' }}" id="phone" name="phone" value="{{ old_blade('phone') }}" placeholder="">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="title">Facebook Link</label>
                                        <input type="text" class="form-control{{ $errors->has('facebook') ? ' is-invalid' : '' }}" id="facebook" name="facebook" value="{{ old_blade('facebook') }}" placeholder="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="card">
                        <div class="card-header">
                            <i class="fe-menu-square-o"></i>  AVATAR <span class="text-danger">( Ảnh vuông 400 x 400 px )</span>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-sm-12">
                                    <input type="file" class="form-control{{ $errors->has('avatar_supports') ? ' is-invalid' : '' }}" id="avatar_supports" name="avatar_supports" value="{{ old_blade('avatar_supports') }}" >
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
