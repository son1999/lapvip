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
                                <label for="title">Tiêu đề</label>
                                <input type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old('title') }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="desc">Mô tả ngắn</label>
                                <input type="text" class="form-control{{ $errors->has('desc') ? ' is-invalid' : '' }}" id="desc" name="desc" value="{{ old('desc') }}" required>
                            </div>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="cat_id">Danh mục sản phẩm</label>
                                <select id="cat_id" name="category_id[]" required multiple="multiple" style="width:100%" class="form-control{{ $errors->has('category_id') ? ' is-invalid' : '' }}">
                                    @include('BackEnd::pages.category.option', [
                                        'options' => $cat,
                                        'def' => old('cat_id'),
                                        'mode' => 1
                                    ])
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status">Vị Trí hiện thị |</label>
                                <i class="text-danger">Chỉ dành cho PC</i>
                                <div class="d-flex">
                                    <div class="w-25">
                                        <input type="radio" name="sort" checked value="0"> Không
                                    </div>
                                    <div class="w-25">
                                        <input type="radio" name="sort" value="1"> Trên
                                    </div>
                                    <div>
                                        <input type="radio" name="sort"  value="2"> Dưới
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status">Xét giá bán cho thuộc tính này?</label>
                                <div class="d-flex">
                                    <div class="w-25">
                                        <input type="radio" name="status" checked value="2"> Không
                                    </div>
                                    <div>
                                        <input type="radio" name="status" value="1"> Có
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status">Hiển thị tại mục Sản phẩm cùng hãng (Product Detail) ?</label>
                                <div class="d-flex">
                                    <div class="w-25">
                                        <input type="radio" name="show_detail" checked value="0"> Không
                                    </div>
                                    <div>
                                        <input type="radio" name="show_detail" value="1"> Có
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status">Hiển thị tại Menu 2 ?</label>
                                <div class="d-flex">
                                    <div class="w-25">
                                        <input type="radio" id="show_menu_0" name="show_menu" checked value="0"> Không
                                    </div>
                                    <div>
                                        <input type="radio" id="show_menu_1" name="show_menu" value="1"> Có
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-3 d-none" id="cate_show">
                        <div class="col-sm-4" >
                            <label for="cate_id">Danh Mục Hiển Thị Tại Menu 2</label>
                            <select  id="cate_id" name="cate_id" class="form-control{{ $errors->has('cate_id') ? ' is-invalid' : '' }}">
                                <option value="" selected disabled>---Chọn---</option>
                                @include('BackEnd::pages.category.option', [
                                    'options' => $cat,
                                    'def' => old('cate_id'),
                                    'mode' => 1
                                ])
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status">Hiển thị Filter Đặc Biệt PC | </label>
                                <i class="text-danger">Chỉ hiển thị duy nhất, cân nhắc ưu tiên hiển thị</i>
                                <div class="d-flex">
                                    <div class="w-25">
                                        <input type="radio" name="show_filter" checked value="0"> Không
                                    </div>
                                    <div>
                                        <input type="radio" name="show_filter" value="1"> Có
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="status">Hiển thị Filter Đặc Biệt Mobile | </label>
                                <i class="text-danger">Chỉ hiển thị duy nhất, cân nhắc ưu tiên hiển thị</i>
                                <div class="d-flex">
                                    <div class="w-25">
                                        <input type="radio" name="show_filter_mobile" checked value="0"> Không
                                    </div>
                                    <div>
                                        <input type="radio" name="show_filter_mobile" value="1"> Có
                                    </div>
                                </div>
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
    {!! \Lib::addMedia('admin/js/library/uploadifive/jquery.uploadifive.min.js') !!}
    {!! \Lib::addMedia('admin/js/library/uploadifive/multiupload.js') !!}
    {!! \Lib::addMedia('admin/js/library/ckeditor/ckeditor.js') !!}
    {!! \Lib::addMedia('admin/js/library/tag/jquery.caret.min.js') !!}
    {!! \Lib::addMedia('admin/js/library/tag/jquery.tag-editor.min.js') !!}
    <script>
        $(".cate").select2({
            tags: true,
        });
        $("#cat_id").select2({
            width: 'resolve',
        });
        $('#show_menu_1').click(function () {
            if (this.checked == true){
                $('#cate_show').removeClass('d-none');
            }
        });
        $('#show_menu_0').click(function () {
            if (this.checked == true){
                $('#cate_show').addClass('d-none');
            }
        });
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
        @if(\Lib::can($permission, 'filter'))
            shop.admin.filters.init(1, '#filter', 0);
        @endif
        function load_filter_cate(ele) {
            var value = $(ele).val();
            app_prd_prices.show_loader();
            app_prd_filters.show_loader();
            shop.ajax_popup('product/get-filter-cate-by-prd-cate', 'POST', {id: value}, function (json) {
                if (json.error == 0) {
                    app_prd_prices.filter_cate_price = json.data.filter_cate_price;
                    app_prd_filters.filter_cate_not_price = json.data.filter_cate_not_price;
                    app_prd_prices.hide_loader();
                    app_prd_filters.hide_loader();
                } else {
                    alert(json.msg);
                }
            });
        }
    </script>
@stop