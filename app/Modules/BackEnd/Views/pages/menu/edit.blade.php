@extends('BackEnd::layouts.default')

@section('content')
    <div class="row">
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
                    <i class="fe-menu"></i>THÔNG TIN CƠ BẢN
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Tiêu đề</label>
                                <input type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old('title', $data->title) }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="link">URL hoặc Route name</label>
                                <input type="text" class="form-control{{ $errors->has('link') ? ' is-invalid' : '' }}" id="link" name="link" value="{{ old('link', $data->link) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="sort">Sắp xếp</label>
                                <input type="text" class="form-control{{ $errors->has('sort') ? ' is-invalid' : '' }}" id="sort" name="sort" value="{{ old('sort', $data->sort) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="lang">Ngôn ngữ</label>
                                <select id="lang" name="lang" class="form-control{{ $errors->has('lang') ? ' is-invalid' : '' }}" onchange="shop.getMenu($('#type').val(), this.value)">
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
                    <i class="fe-menu"></i>PHÂN LOẠI MENU
                </div>
                <div class="card-body">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="type">Loại Menu</label>
                            <select id="type" name="type" class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}" onchange="shop.getMenu(this.value, $('#lang').val())">
                                <option value="-1">-- Chọn --</option>
                                @foreach($allType as $k => $v)
                                    <option value="{{ $k }}" @if(old('type', $data->type) == $k) selected="selected" @endif>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-6 icon_type" id="4" style="{{$data->type == 4 ? '' : 'display: none'}}">
                        <div class="form-group">
                            <label for="type">Loại Danh Mục</label>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <select id="type_cate_footer" name="type_cate_footer" class="form-control{{ $errors->has('type_cate_footer') ? ' is-invalid' : '' }}" onchange="shop.getCatfooter(this.value, $('#lang').val())">
                                            <option value="-1">-- Chọn --</option>
                                            @foreach($type as $k => $v)
                                                <option value="{{ $k }}" @if(old('type_cate_footer', $data->type_cate_footer == $k)) selected="selected" @endif>{{ $v }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="type">Danh Mục</label>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <select id="cat_id_footer" name="cat_id_footer" class="form-control{{ $errors->has('cat_id_footer') ? ' is-invalid' : '' }}">
                                            <option value="0">-- Chọn --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6 icon_type" id="3" style="{{$data->type == 3 ? '' : 'display: none'}}">
                        <div class="form-group">
                            <label for="type">Icon</label>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="file" class="form-control" id="img_icon" name="img_icon">
                                        @if(!empty($data->img_icon))
                                            <div class="pull-right">
                                                <img src="{{ $data->getImageUrl('small') }}" class="w-100" />
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="type">Loại Danh Mục</label>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <select id="type_cate" name="type_cate" class="form-control{{ $errors->has('type_cate') ? ' is-invalid' : '' }}" onchange="shop.getCat(this.value, $('#lang').val())">
                                            <option value="-1">-- Chọn --</option>
                                            @foreach($type as $k => $v)
                                                <option value="{{ $k }}" @if(old('type_cate', $data->type_cate) == $k) selected="selected" @endif>{{ $v }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="type">Danh Mục</label>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <select id="cat_id" name="cat_id" class="form-control{{ $errors->has('cat_id') ? ' is-invalid' : '' }}">
                                            <option value="0">-- Chọn --</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="type">Hiển thị sản phẩm Bán Chạy tại Menu 2</label> <br>
                            <i class="text-danger">Nhập ID của sản phẩm </i> <br>
                            <i class="text-danger">Hiển thị tối đa 3 sản phẩm</i> <br>
                            <i class="text-danger">Mỗi ID cách nhau bởi dấu phẩy (,)</i>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control {{ $errors->has('id_selling') ? ' is-invalid' : '' }}" id="id_selling" name="id_selling" value="{{old('id_selling', $data->id_selling)}}" placeholder="ID của sản phẩm, phân tách nhau bởi dấu phẩy (,)">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="type">Banner Menu 2 (nếu có)</label> <br>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="file" class="form-control" id="image" name="image" >
                                        @if(!empty($data->image))
                                            <div class="pull-right">
                                                <img src="{{ $data->getImageBanner('small') }}" class="w-100" />
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="type">Link (nếu có)</label> <br>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <input type="text" class="form-control" id="banner_link" name="banner_link" value="{{old('banner_link', $data->banner_link)}}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-6">
                        <div class="form-group">
                            <label for="pid">Menu cha</label>
                            <select id="pid" name="pid" class="form-control{{ $errors->has('pid') ? ' is-invalid' : '' }}">
                                <option value="0">-- Chọn --</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fe-menu"></i>THÔNG TIN PHỤ
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="icon">Icon Class</label>
                                <input type="text" class="form-control{{ $errors->has('icon') ? ' is-invalid' : '' }}" id="icon" name="icon" value="{{ old('icon', $data->icon) }}">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <label class="checkbox-inline" for="no_follow">
                                <input type="checkbox" id="no_follow" name="no_follow" value="1" @if(old('no_follow', $data->no_follow) == 1) checked @endif>  No Follow
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <label class="checkbox-inline" for="newtab">
                                <input type="checkbox" id="newtab" name="newtab" value="1" @if(old('newtab', $data->newtab) == 1) checked @endif>  Bật Tab mới
                            </label>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="perm">Phân quyền</label>
                                <select id="perm" name="perm" class="form-control{{ $errors->has('perm') ? ' is-invalid' : '' }}">
                                    <option value="">-- Chọn --</option>
                                    @foreach($permList as $k => $v)
                                        <optgroup label="{{ $v['title'] }}">
                                            @foreach($v['perm'] as $p => $t)
                                                <option value="{{ $k.'-'.$p }}" @if(old('perm', $data->perm) == $k.'-'.$p) selected="selected" @endif>{{ $k.'.'.$p.' - '.$t }}</option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
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
    <script type="text/javascript">
        $('#type').change(function(){
            $('.icon_type').hide();
            $('#' + $(this).val()).show();
        });
        shop.ready.add(function(){
            shop.getMenu($('#type').val(), $('#lang').val(), {{ $data->pid }});
            $("#link").autocomplete({
                position: { my : "right top", at: "right bottom" },
                minLength: 1,
                delay: 500,
                scroll: true,
                source: {!! $routes !!}
            });
            shop.getCat($('#type_cate').val(), $('#lang').val(), {{ $data->cat_id }});
            shop.getCatfooter($('#type_cate_footer').val(), $('#lang').val(), {{ $data->cat_id_footer}});
        },true);
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
        shop.getCat = function (type, lang, def) {
            var html = '<option value="0">-- Chọn --</option>';
            shop.ajax_popup('category/get-cat', 'POST', {type:type, lang:lang}, function(json) {
                $.each(json.data,function (ind,value) {
                    html += '<option value="'+value.id+'"'+(def == value.id?' selected':'')+'>'+value.title+'</option>';
                    if(value.sub.length != 0){
                        $.each(value.sub,function (k,sub) {
                            html += '<option value="'+sub.id+'"'+(def == sub.id?' selected':'')+'> &nbsp;&nbsp;&nbsp; '+sub.title+'</option>';
                        });
                    }
                });
                $('#cat_id').html(html);
            });
        };
        shop.getCatfooter = function (type, lang, def) {
            var html = '<option value="0">-- Chọn --</option>';
            shop.ajax_popup('category/get-cat_f', 'POST', {type_f:type, lang:lang}, function(json) {
                $.each(json.data,function (ind,value) {
                    html += '<option value="'+value.id+'"'+(def == value.id?' selected':'')+'>'+value.title+'</option>';
                    if(value.sub.length != 0){
                        $.each(value.sub,function (k,sub) {
                            html += '<option value="'+sub.id+'"'+(def == sub.id?' selected':'')+'> &nbsp;&nbsp;&nbsp; '+sub.title+'</option>';
                        });
                    }
                });
                $('#cat_id_footer').html(html);
            });
        };
    </script>
@stop