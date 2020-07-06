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
                            <div class="form-group">
                                <label for="title">Tên danh mục</label>
                                <input type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old('title') }}" required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="type">Loại danh mục</label>
                                <select id="type" name="type" class="form-control{{ $errors->has('type') ? ' is-invalid' : '' }}" onchange="shop.getCat(this.value, $('#lang').val())">
                                    <option value="-1">-- Chọn --</option>
                                    @foreach($type as $k => $v)
                                        <option value="{{ $k }}" @if(old('type') == $k) selected="selected" @endif>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="pid">Danh mục cha</label>
                                <select id="pid" name="pid" class="form-control{{ $errors->has('pid') ? ' is-invalid' : '' }}">
                                    <option value="0">-- Chọn --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="sort">Sắp xếp</label>
                                <input type="text" class="form-control{{ $errors->has('sort') ? ' is-invalid' : '' }}" id="sort" name="sort" value="{{ old('sort') }}" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="lang">Ngôn ngữ</label>
                                <select id="lang" name="lang" class="form-control{{ $errors->has('lang') ? ' is-invalid' : '' }}" onchange="shop.getCat($('#type').val(), this.value)">
                                    @foreach($langOpt as $k => $v)
                                        <option value="{{ $k }}" @if(old('lang') == $k) selected="selected" @endif>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="checkbox"></label>
                                <div class="checkbox checkbox-info mb-2 mt-2 col-md-12 align-self-center">
                                    <input type="checkbox" name="show_detail_accessory" id="show_detail_accessory" value="1">
                                    <label for="show_detail_accessory" class="ml-2">
                                        Show in Page Detail Accessory
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="checkbox"></label>
                                <div class="checkbox checkbox-info mb-2 mt-2 col-md-12 align-self-center">
                                    <input type="checkbox" name="show_installment_page" id="show_installment_page" value="1">
                                    <label for="show_installment_page" class="ml-2">
                                        Show in Page Installment 0%
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="icon">Icon</label> <br>
                                <input type="file" id="icon" name="icon">
                                <br />
                                <i>Ảnh vuông kích thước chiều ngang: 50x50</i>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="icon">Icon Hover</label>
                                <div>
                                    <input type="file" id="icon_hover" name="icon_hover">
                                    <br />
                                    <i>Ảnh vuông kích thước chiều ngang: 50x50</i>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="fe-sidebar"></i>Page Format
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3 ">
                            <img class="w-100 lazyload" data-src="{{asset('images/page-format-1.jpg')}}" alt="">
                            <input type="radio" class=" mt-2" name="page_format" id="page_format" value="1" > Page Format 1
                        </div>
{{--                        <div class="col-sm-3 ">--}}
{{--                            <img class="w-100" src="{{asset('images/page-format-2.jpg')}}" alt="">--}}
{{--                            <input type="radio" class="mt-2" name="page_format" id="page_format" value="2" >  Page Format 2--}}
{{--                        </div>--}}
                        <div class="col-sm-3 ">
                            <img class="w-100 lazyload" data-src="{{asset('images/page-format-3.jpg')}}" alt="">
                            <input type="radio" class=" mt-2" name="page_format" id="page_format" value="3" >  Page Format 3
                        </div>
                        <div class="col-sm-3">
                            <label class="form-control">Format Khác</label>
                            <input type="radio" class=" mt-2" name="page_format" id="page_format" value="4" >  Page Format Trả Góp
                        </div>

                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <i class="fa fa-image"></i>DÀNH CHO SEO
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="description">Description</label>
                                <input type="text" class="form-control" id="description" name="description" value="{{ old('description') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="keywords">Keywords</label>
                                <input type="text" class="form-control" id="keywords" name="keywords" value="{{ old('keywords') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="file" id="image" name="image">
                            <br />
                            <i>Ảnh vuông kích thước chiều ngang: 800x800px</i>
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

@section('js_bot')
    <script type="text/javascript">
        shop.ready.add(function(){
            shop.getCat($('#type').val(), $('#lang').val(), {{ old('pid', 0) != null ? old('pid', 0) : 0 }});
        },true);
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
                $('#pid').html(html);
            });
        };
    </script>
@stop