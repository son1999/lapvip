@extends('BackEnd::layouts.default')

@section('content')
    <div class="row">
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
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group py-2">
                                <label for="cat_id">Loại thuộc tính</label>
                                <select id="cat_id" name="filter_cate_id"  class="select2 form-control{{ $errors->has('filter_cate_id') ? ' is-invalid' : '' }}">
                                    @foreach($filters_cate_id as $k => $v)
                                        <option {{$request->id == $v['id'] ? 'selected' : ''}}  class="filter_cate" value="{{ $v['id'] }}">
                                            {{ $v['title'] }}
                                        </option>
                                        <option class="text-danger" disabled>{{$v['desc']}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h5 class="text-uppercase mt-0 mb-3 bg-light p-2"><span class="text-uppercase left-0">Sử dụng mã màu (HEX) để làm Filters </span>
                                        <div class="checkbox float-right mt-0" >
                                            <input type="checkbox" class="checkbox" id="colorHEX_check" name="colorHEX_check" value="1" {{ old('colorHEX_check') == 1 ? 'checkded' : ''  }}>
                                            <label for="colorHEX_check" class="mb-3"></label>
                                        </div>
                                    </h5>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row" >
                        <div class="col-sm-3" id="imageFilter">
                            <div class="form-group">
                                <label for="image_filter">
                                    Image Filter
                                </label>
                                <input type="file" class="dropify"  name="image" id="image_filter"  />
                            </div>
                        </div>
                        <div class="col-sm-3 d-none" id="colorHEX">
                            <div class="form-group">
                                <label for="color">Bảng mã màu <span class="text-danger">*(Chỉ chọn với Chủng loại "Màu sắc, Colors")</span></label>
                                <div class="d-flex align-items-center">
                                    <input type="color" style="height: 60px; width: 150px;" class="form-control border-0" id="colorpicker" pattern="^#+([a-fA-F0-9]{6}|[a-fA-F0-9]{3})$" value="#bada55">
                                    <input type="text" name="name" class="form-control" id="hexcolor" placeholder="Tên màu: Đỏ cam, ...">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Tên thuộc tính</label>
                                <input type="text" class="form-control" name="title" id="title" value="{{ old('title') }}">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <button type="submit" id="submit-filter" class="btn btn-sm btn-success"><i class="fa fa-dot-circle-o"></i> Thêm mới</button>
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
    <style>
        .select2-container--default .select2-results__option[aria-disabled=true] {
            color: #999;
            font-size: 12px;
            margin-left: 20px;
            color: red;
            padding-top: 0;
        }

    </style>
@stop

@section('js_bot')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>
    {!! \Lib::addMedia('admin/js/library/uploadifive/jquery.uploadifive.min.js') !!}
    {!! \Lib::addMedia('admin/js/library/uploadifive/multiupload.js') !!}
    {!! \Lib::addMedia('admin/js/library/ckeditor/ckeditor.js') !!}
    {!! \Lib::addMedia('admin/js/library/tag/jquery.caret.min.js') !!}
    {!! \Lib::addMedia('admin/js/library/tag/jquery.tag-editor.min.js') !!}
    {!! \Lib::addMedia('admin/libs/dropzone/dropzone.min.js') !!}
    {!! \Lib::addMedia('admin/js/form-fileuploads.init.js') !!}
    <script>

        $(document).ready(function(){
            $('input[name="colorHEX_check"]').click(function(){
                if($(this).is(":checked")){
                    $('#imageFilter').addClass('d-none');
                    $('#colorHEX').removeClass('d-none');
                }
                else if($(this).is(":not(:checked)")){
                    $('#colorHEX').addClass('d-none');
                    $('#imageFilter').removeClass('d-none');
                    $('#filter').val('');
                }
            });
        });
        $('#colorpicker').on('change', function() {
            $('#hexcolor').val(this.value);
            let this_val = $('#filter').val();
            $('#filter').val(this.value);
            let item_color = '<li><div class="tag-editor-spacer">&nbsp;,</div><div class="tag-editor-tag">' + this.value + '</div><div class="tag-editor-delete"><i></i></div></li>'
            $('#searchForm .tag-editor').append(item_color);
            $('#hexcolor').on('change', function() {
                @if(\Lib::can($permission, 'filter'))
                shop.admin.filters.addName($('#filter').val(), $('#hexcolor').val(), filter_cate_id);
                @endif
            });
        });
        $(".cate").select2({
            tags: true,
        });
        $("#cat_id").select2();
        shop.getMenu = function (type, lang, def) {
            var html = '<option value="0">-- Chọn --</option>';
            shop.ajax_popup('menu/get-menu', 'POST', {type:type, lang:lang}, function(json) {
                $.each(json.data,function (ind,value) {
                    html += '<option value="'+value.id+'"'+(def == value.id?' selected':'')+'>'+value.title+'</option>';
                    if(value.sub.length != 0){
                        $.each(value.sub,function (k,sub) {
                            html += '<option value="'+sub.id+'"'+(def == sub.id?' selected':'')+'> &nbsp;&nbsp;&nbsp; '+sub.title+'</option>';
                        });
                    }
                });
                $('#pid').html(html);
            });
        };


        shop.multiupload('body');
        let filter_cate_id =  $('#cat_id').val();
        $("#cat_id").change(function(event){
            filter_cate_id = $('#cat_id').val();
            window.location = "?id=" + filter_cate_id;
        });
        @if(\Lib::can($permission, 'filter'))
        shop.admin.filters.init(filter_cate_id, '#filter', 0);
        @endif
        // @if(\Lib::can($permission, 'filter'))
        //     shop.admin.filters.init(1, 'filter_cate', 0);
        // @endif
    </script>
@stop