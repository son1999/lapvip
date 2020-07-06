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

            @if(old_blade('editMode'))
            <div class="mb-3">
                <a target="_blank" href="{{route('product.detail',['alias' => $data->alias])}}" class="btn btn-sm btn-primary"><i class="fa fa-eye"></i> Xem sản phẩm</a>
            </div>
            @endif
            <div class="card">
                <div class="card-header">
                    <i class="fe-menu"></i>THÔNG TIN CƠ BẢN
                </div>
                <div class="card-body" >
                    <div class="row" id="slug-alias">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Tiêu đề, tên <span class="text-danger">*</span></label>
                                <input v-model="input" @if(old_blade('editMode')) @endif type="text" class="form-control{{ $errors->has('title') ? ' is-invalid' : '' }}" id="title" name="title" value="{{ old_blade('title') }}" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <label for="alias">Alias <span class="text-danger">* * *</span></label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text" id="basic-addon3">{{env('APP_URL')}}/</span>
                                </div>
                                <input @if(old_blade('editMode')) :value="alias" @else :value="slug" @endif type="text" name="alias" class="form-control" id="basic-url" aria-describedby="basic-addon3">
                            </div>
                        </div>
{{--                        <div class="col-sm-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="title">Tiêu đề phụ <span class="text-danger">*</span></label>--}}
{{--                                <input type="text" class="form-control{{ $errors->has('title_sub') ? ' is-invalid' : '' }}" id="title_sub" name="title_sub" value="{{ old_blade('title_sub') }}" required>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="col-sm-3 d-none">
                            <div class="form-group">
                                <label for="lang">Ngôn ngữ</label>
                                <select id="lang" name="lang" class="form-control{{ $errors->has('lang') ? ' is-invalid' : '' }}" onchange="shop.admin.getCat(1, $('#cat_id').val(), this.value, '#cat_id')">
                                    @foreach($langOpt as $k => $v)
                                        <option value="{{ $k }}"{{ old_blade('lang') == $k ? ' selected="selected"' : '' }}>{{ $v }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="code">Danh mục <span class="text-danger">*</span> </label>
                                <select onchange="load_filter_cate(this)" id="cat_id" name="cat_id" class="form-control{{ $errors->has('cat_id') ? ' is-invalid' : '' }}">
                                    <option value="">-- Chọn danh mục --</option>
                                    @include('BackEnd::pages.category.option', [
                                        'options' => $catOpt,
                                        'def' => old_blade('cat_id'),
                                        'mode' => 1
                                    ])
                                </select>
                            </div>
                        </div>

                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="sort">Sắp xếp</label>
                                <input type="text" class="form-control{{ $errors->has('sort') ? ' is-invalid' : '' }}" id="sort" name="sort" value="{{ old_blade('sort') }}" onkeypress="return shop.numberOnly()">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 ml-3">
                            <div class="form-group checkbox">
                                <input type="checkbox" class="form-control{{ $errors->has('detail_ap') ? ' is-invalid' : '' }}" id="detail_ap" name="detail_ap" value="1" @if(old_blade('detail_ap') == 1) checked @endif>
                                <label for="detail_ap">Hiển thị Filter</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <label>Trả góp?</label>
                            <div class="d-flex flex-wrap">
                                <div class="radio radio-info mb-2 col-3">
                                    <input type="radio" id="is_tragop_0" name="is_tragop" {{@$data->is_tragop == 0 ? 'checked' : ''}} value="0">
                                    <label for="is_tragop_0">Không</label>
                                </div>
                                <div class="radio radio-info mb-2 col-3">
                                    <input type="radio" id="is_tragop_1" name="is_tragop" {{@$data->is_tragop == 1 ? 'checked' : ''}} value="1">
                                    <label for="is_tragop_1">Có</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row d-none" id="cat_ins" >
                        <div class="card-body">
                            <div class="col-sm-3">
                                <div class="form-group">
                                    <label for="code">Danh mục trả góp</label>
                                    <select id="cat_ins" name="cat_ins" class="form-control{{ $errors->has('cat_ins') ? ' is-invalid' : '' }}">
                                        <option value="">-- Chọn danh mục --</option>
                                        @include('BackEnd::pages.category.option', [
                                            'options' => $catIns,
                                            'def' => old_blade('cat_ins'),
                                            'mode' => 1
                                        ])
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <label>Tiết kiệm?</label>
                            <div class="d-flex flex-wrap">
                                <div class="radio radio-info mb-2 col-3">
                                    <input type="radio" id="is_tietkiem_0" name="is_tietkiem" {{@$data->is_tietkiem == 0 ? 'checked' : ''}} value="0">
                                    <label for="is_tietkiem_0">Không</label>
                                </div>
                                <div class="radio radio-info mb-2 col-3">
                                    <input type="radio" id="is_tietkiem_1" name="is_tietkiem" {{@$data->is_tietkiem == 1 ? 'checked' : ''}} value="1">
                                    <label for="is_tietkiem_1">Có</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="product_have_sale">
                        <div class="row">
                            <div class="col-sm-12">
                                <label>Khuyễn mại ?</label>
                                <div class="d-flex flex-wrap">
                                    <div class="radio radio-info mb-2 col-3">
                                        <input type="radio" id="is_sale_0" name="is_sale" {{@$data->is_sale == 0 ? 'checked' : ''}} value="0">
                                        <label for="is_sale_0">Không</label>
                                    </div>
                                    <div class="radio radio-info mb-2 col-3">
                                        <input type="radio" id="is_sale_1" name="is_sale" {{@$data->is_sale == 1 ? 'checked' : ''}} value="1">
                                        <label for="is_sale_1">Có</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card d-none" id="sale">
                            <div class="card-header">
                                <i class="fe-menu"></i>THÔNG TIN KHUYỄN MẠI
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <textarea class="w-100" name="sale_detail" id="sale_detail" cols="100%" rows="10">{{ old_blade('sale_detail') }}</textarea>
                                    </div>
                                </div>
                            </div>
{{--                            <div class="card-body">--}}
{{--                                <div class="row" >--}}
{{--                                    <div class="col-12" >--}}
{{--                                        <div class="fa-border mt-3" v-for="(topic, key_topic) in data.val">--}}
{{--                                            <div class="row">--}}
{{--                                                <div class="col-6">--}}
{{--                                                    <label for="">Chủ đề</label>--}}
{{--                                                    <input class="col-6 p-1" type="text" name="prop_topics_sale[]"  v-model="topic.title"/>--}}
{{--                                                    <span @click="addTopic" class="btn btn-success ml-2 mb-1"><i class="fa fa-plus" aria-hidden="true"></i></span>--}}
{{--                                                    <span @click="trashTopic(key_topic)" v-if="isShow" class="btn btn-danger ml-2 mb-1"><i class="fas fa-trash"></i></span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}

{{--                                            <div class="row" v-for="(item, key) in topic.props">--}}
{{--                                                <div class="col-12 m-3">--}}
{{--                                                    <label for="">Thuộc tính</label>--}}
{{--                                                    <input class="col-md-3 p-1" type="text" v-bind:name="'property_sale_titles_'+key_topic+'[]'"  v-model="item.title">--}}
{{--                                                    <label for="">Nội dung</label>--}}
{{--                                                    <input class="col-md-3 p-1" type="text" v-bind:name="'property_sale_values_'+key_topic+'[]'"  v-model="item.value">--}}
{{--                                                    <span @click="addLine(topic)" class="btn btn-success ml-2 mb-1"><i class="fa fa-plus" aria-hidden="true"></i></span>--}}
{{--                                                    <span @click="trashLine(topic.props,key)" v-if="isShow" class="btn btn-danger ml-2 mb-1"><i class="fas fa-trash"></i></span>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div><!-- end col -->--}}
{{--                            </div>--}}
                        </div>


                    </div>

                    <div class="row">
                        <div class="col-sm-12">
                            <label>Có thuộc Mục Sản phẩm trang chủ nào không?</label>
                            <div class="d-flex flex-wrap">
                                <div class="radio radio-info mb-2 col-3">
                                    <input type="radio" id="special_box_home" name="special_box_home" checked value="0">
                                    <label for="special_box_home">Không</label>
                                </div>
                                @foreach($special_tags as $item)
                                    <div class="radio radio-info mb-2 col-3">
                                        <input type="radio" id="special_box_home{{$item->id}}" name="special_box_home" @if(old_blade('special_box_home') == $item->id)checked @endif value="{{$item->id}}">
                                        <label for="special_box_home{{$item->id}}">{{$item->title}}</label>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="image">Ảnh đại diện <span class="text-danger">*</span></label>
                                <input type="file" id="image" name="image" class="form-control{{ $errors->has('image') ? ' is-invalid' : '' }}">
                                <div class="mt-2">
                                    @if(!empty(@$data->image))
                                        <div class="pull-right">
                                            <img src="{{ $data->getImageUrl('small') }}" />
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="image">Ảnh hiển thị trang Home (PC)</label>
                                <input type="file" id="image_home" name="image_home" class="form-control{{ $errors->has('image_home') ? ' is-invalid' : '' }}">
                                <div class="mt-2">
{{--                                    <i>Kích thước 800x445px</i>--}}
                                    @if(!empty(@$data->image_home))
                                        <div class="pull-right w-100" >
                                            <img class="w-100" src="{{ $data->getImageHome('original') }}" />
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header">
                            <i class="fe-menu"></i>BỘ HÌNH ẢNH
                        </div>
                        <div class="card-body">
                            @include('BackEnd::pages.product.gallery_product', [
                                        'object_id' => old_blade('id')
                                    ])
                        </div>
                    </div>
                </div>
            </div>

            <div class="card text-dark">
                <div class="card-header">
                    <i class="fe-menu"></i>Bộ sản phẩm
                </div>
                <div class="card-body" id="fill_collection">
                    <div class="form-group">
                        <div class="row" >
                            <div class="checkbox checkbox-info mb-2 col-md-3" v-for="collect in collection">
                                <input type="checkbox" class="cate_fe" v-bind:id="'collect_' + collect.id" v-bind:checked="collect.checked == 1" v-bind:name="'collection_id[]'" v-bind:value="collect.collect_id" >
                                <label v-bind:for="'collect_' + collect.id" class="ml-2">
                                    @{{ collect.title }}
                                </label>
                            </div>
                        </div>
                    </div>
                    @include('BackEnd::layouts.loader')
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fe-menu"></i>THÔNG TIN GIÁ CƠ SỞ
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="base_price">Giá hiển thị</label>
                                <input type="text" class="form-control{{ $errors->has('price') ? ' is-invalid' : '' }}" id="base_price" name="base_price" value="{{ old_blade('price') ? \Lib::numberFormat(old_blade('price')) : 0 }}" required onkeypress="return shop.numberOnly()" onkeyup="mixMoney(this)" onfocus="this.select()">
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="form-group">
                                <label for="base_priceStrike">Giá gạch</label>
                                <input type="text" class="form-control{{ $errors->has('priceStrike') ? ' is-invalid' : '' }}" id="base_priceStrike" name="base_priceStrike" value="{{ \old_blade('priceStrike') ? Lib::numberFormat(old_blade('priceStrike')) : 0 }}" onkeypress="return shop.numberOnly()" onkeyup="mixMoney(this)" onfocus="this.select()">
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fe-menu"></i>THÔNG TIN GIÁ VÀ SỐ LƯỢNG
                </div>
                <div class="card-body">
                    @include('BackEnd::pages.product.components.price_infor')
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fe-menu"></i>THÔNG TIN HẠNG MỤC SẢN PHẨM
                </div>
                <div class="card-body">
                    @include('BackEnd::pages.product.components.filter_infor')
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card" id="addLine">
                        <div class="card-header">
                            <i class="fa fa-newspaper-o"></i>THÔNG SỐ MÔ TẢ
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="link">Video giới thiệu</label>
                                        <input type="text" id="link" name="link" class="form-control" value="{{ old_blade('link') }}" placeholder="Link Video Youtube">
                                    </div>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="description">Mô tả ngắn <span class="text-danger">*</span></label>
                                        <div class="mb-3" v-for="(item, key) in data.val" >
                                            <input class="col-md-6 p-1" type="text" name="parameter[]" required v-model="data.val[key]">
                                            <span @click="addLine" class="btn btn-success ml-2 mb-1"><i class="fa fa-plus" aria-hidden="true"></i></span>
                                            <span @click="trashLine(data.val,key)" v-if="isShow" class="btn btn-danger ml-2 mb-1"><i class="fe-trash-2" aria-hidden="true"></i></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div> <!-- end card-body-->
                    </div> <!-- end card-->
                </div><!-- end col -->
            </div>

            <div class="card">
                <div class="card-header" >
                    <i class="fa fa-newspaper-o"></i>THÔNG SỐ SẢN PHẨM
                </div>
                <div >
                    <div class="card-body" >
                        @include('BackEnd::pages.product.components.prd_spec_template')
                    </div>
                </div>
            </div>
                <div class="card">
                    <div class="card-header">
                        <i class="fe-menu"></i>Show Box
                    </div>
                    <div class="card-body" >
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="title">Trong hộp bạn có gì ? </label>
                                    <div class="mb-3"  >
                                        <input class="form-control{{ $errors->has('image_box') ? ' is-invalid' : '' }}" type="text" name="lineBox" value="{{old_blade('lineBox')}}">
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="title">Image Show Box</label>
                                    <input type="file" id="image_box" name="image_box" class="form-control{{ $errors->has('image_box') ? ' is-invalid' : '' }}">
                                    <div class="mt-2">
                                        @if(!empty(@$data->image_box))
                                            <div class="pull-right">
                                                <img src="{{ $data->getImageBox('medium') }}" class="w-100"/>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
{{--            <div class="card">--}}
{{--                <div class="card-header" >--}}
{{--                    <i class="fa fa-newspaper-o"></i>THÔNG SỐ SO SÁNH SẢN PHẨM--}}
{{--                </div>--}}
{{--                <div >--}}
{{--                    <div class="card-body" >--}}
{{--                        @include('BackEnd::pages.product.components.prd_compare_template')--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}

            <div class="card">
                <div class="card-header">
                    <i class="fa fa-image"></i>Thông tin sản phẩm liên kết
                </div>
                <div class="card-body">
                    @include('BackEnd::pages.product.components.relate_products')
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <i class="fa fa-newspaper-o"></i>NỘI DUNG CHI TIẾT
                </div>
                <div class="card-body">
{{--                    <div class="row">--}}
{{--                        <div class="col-sm-6">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="sapo">Mô tả ngắn</label>--}}
{{--                                <textarea class="form-control{{ $errors->has('sapo') ? ' is-invalid' : '' }}" id="sapo" name="sapo" rows="5">{{ old_blade('sapo') }}</textarea>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <span class="text-center text-danger">--}}
{{--                        <strong>Chi tiết hiển thị ngắn (P1)</strong>--}}
{{--                        sẽ là phần đầu tiên của chi tiết (sẽ luôn hiển thị tại trang detail). <br>--}}
{{--                        <strong>Chi tiết (P2)</strong> chỉ hiển thị khi khách hàng click <u style="color: #0d95e8">Đọc thêm</u>--}}
{{--                    </span>--}}
{{--                    <br>--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-sm-12">--}}
{{--                            <div class="form-group">--}}
{{--                                <label for="sapo">Chi tiết hiển thị ngắn (P1)</label>--}}
{{--                                <textarea class="form-control{{ $errors->has('sort_body') ? ' is-invalid' : '' }}" id="sort_body" name="sort_body" required>{{ old_blade('sort_body') }}</textarea>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-sm-12">--}}
{{--                            <div class="imgContainer">--}}
{{--                                <div id="queue"></div>--}}
{{--                                <input id="uploadify_sort_body" name="uploadify_sort_body" type="file" multiple="true">--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}

                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label for="sapo">Chi tiết </label>
                                <textarea class="form-control{{ $errors->has('body') ? ' is-invalid' : '' }}" id="body" name="body" required>{{ old_blade('body') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="imgContainer">
                                <div id="queue"></div>
                                <input id="uploadify_body" name="uploadify_body" type="file" multiple="true">
                            </div>
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
                                <label for="title_seo">Tiêu đề SEO</label>
                                <input type="text" class="form-control{{ $errors->has('title_seo') ? ' is-invalid' : '' }}" id="title_seo" name="title_seo" value="{{ old_blade('title_seo') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title_seo">Descriptions SEO</label>
                                <input type="text" class="form-control{{ $errors->has('description_seo') ? ' is-invalid' : '' }}" id="description_seo" name="description_seo" value="{{ old_blade('description_seo') }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title_seo">Keywords <span class="text-danger">Phân tách các từ khóa bởi dấu phẩy (,)</span></label>
                                <textarea name="keywords" id="keywords"  rows="2" class="w-100 form-control">{{old_blade('keywords')}}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="title">Tags</label>
                                @if(\Lib::can($permission, 'tag'))
                                    <input type="text" class="form-control" id="tags" name="tags" value="{{ old_blade('tags') }}">
                                @else
                                    <input type="text" class="form-control text-danger" value="Chưa có quyền gán Tag" disabled>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <input type="file" id="image_seo" name="image_seo">
                            <br />
                            <i>Ảnh vuông kích thước chiều ngang: 800x800px</i>
                            @if(!empty(@$data->image_seo))
                                <div class="pull-right">
                                    <img src="{{ $data->getImageSeoUrl('small') }}" />
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex">
                <div class="mb-3">
                    <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-dot-circle-o"></i> Cập nhật</button>
                    &nbsp;&nbsp;{!! Form::close() !!}
                    <a onclick="shop.admin.Createduplicate({{@$data->id}}, 'product')" class="btn btn-sm btn-primary text-light"><i class="fe-file-plus"></i> Nhân đôi</a>

                    <a class="btn btn-sm btn-danger" href="{{ redirect()->back()->getTargetUrl() }}"><i class="fa fa-ban"></i> Hủy bỏ</a>
                </div>
            </div>


        </div>
    </div>
@stop

@section('css')
    {!! \Lib::addMedia('admin/js/library/tag/jquery.tag-editor.css') !!}
{{--    {!! \Lib::addMedia('admin/js/library/chosen/chosen.min.css') !!}--}}
    {!! \Lib::addMedia('admin/js/library/uploadifive/uploadifive.css') !!}
@stop

@section('js_bot')
    {!! \Lib::addMedia('admin/js/vue.js') !!}
    {!! \Lib::addMedia('admin/js/library/uploadifive/jquery.uploadifive.min.js') !!}
    {!! \Lib::addMedia('admin/js/library/uploadifive/multiupload.js') !!}
    {!! \Lib::addMedia('admin/js/library/ckeditor/ckeditor.js') !!}
    {!! \Lib::addMedia('admin/js/library/tag/jquery.caret.min.js') !!}
    {!! \Lib::addMedia('admin/js/library/tag/jquery.tag-editor.min.js') !!}
    {!! \Lib::addMedia('admin/js/library/jquery.form.js') !!}
    {!! \Lib::addMedia('admin/js/library/jquery.sortable.js') !!}
    {!! \Lib::addMedia('admin/js/library/slug.js') !!}

    <script type="text/javascript">

        $('#is_tragop_1').click(function () {
            if (this.checked == true){
                $('#cat_ins').removeClass('d-none');
            }
        });
        $('#is_sale_0').click(function () {
            if (this.checked == true){
                $('#cat_ins').hide();
            }
        });

        {{--var promote = '{!! isset($data->detail->promote_props) && $data->detail-> promote_props ? $data->detail->promote_props : '' !!}';--}}
        {{--var pro = new Vue({--}}
        {{--    el: '#product_have_sale',--}}
        {{--    data() {--}}
        {{--        return {--}}
        {{--            data: {--}}
        {{--                val: promote != '' ? JSON.parse(promote) : [{title:'',props:[{}]}],--}}
        {{--                templates: []--}}
        {{--            },--}}
        {{--            isShow: true,--}}
        {{--            objDefault: [{title:'aaa',props:[{}]}]--}}
        {{--        }--}}
        {{--    },--}}
        {{--    methods: {--}}
        {{--        addLine: function (topic) {--}}
        {{--            this.isShow = true;--}}
        {{--            return topic.props.push({});--}}
        {{--        },--}}
        {{--        addTopic: function () {--}}
        {{--            this.isShow = true;--}}
        {{--            var abg = {title:'',props:[{}]};--}}
        {{--            return this.data.val.push(abg);--}}
        {{--        },--}}
        {{--        trashLine: function (topic_props,index) {--}}
        {{--            if(topic_props.length > 1) {--}}
        {{--                topic_props.splice(index, 1);--}}
        {{--            }else {--}}
        {{--                topic_props.splice(index, 1);--}}
        {{--                return topic_props.push({});--}}
        {{--            }--}}
        {{--        },--}}
        {{--        trashTopic: function (index) {--}}
        {{--            if(this.data.val.length > 1) {--}}
        {{--                this.data.val.splice(index, 1);--}}
        {{--            }else {--}}
        {{--                this.data.val.splice(index, 1);--}}
        {{--                var abg = {title:'',props:[{}]};--}}
        {{--                return this.data.val.push(abg);--}}
        {{--            }--}}
        {{--        },--}}

        {{--    }--}}
        {{--});--}}
        $(window).bind('load', function(){
            if ($('#is_sale_1').is(':checked')){
                $('#sale').removeClass('d-none');
            }
            else {
                $('#sale').addClass('d-none');
            }
            if ($('#is_tragop_1').is(':checked')){
                $('#cat_ins').removeClass('d-none');
            }
            else {
                $('#cat_ins').addClass('d-none');
            }
        });
        $('#is_sale_1').click( () => {
            $('#sale').removeClass('d-none');
        });
        $('#is_sale_0').click( () => {
            $('#sale').addClass('d-none');
        });

        $('#is_tragop_1').click( () => {
            $('#cat_ins').removeClass('d-none');
        });
        $('#is_tragop_0').click( () => {
            $('#cat_ins').addClass('d-none');
        });

        // $('.cate_fe').click(function(){
        //     if(this.checked == true){
        //         var coll_val = $(this).attr('data-val');
        //         $(this).parent('.checkbox-info').append('<input type="hidden" class="cate_val" name="collection_id[]" value="' + coll_val + '">');
        //     }else {
        //         $(this).siblings('.cate_val').remove();
        //     }
        // });
        // $('.cate_fe').each(function() {
        //     if(this.checked == true){
        //         var coll_val = $(this).attr('data-val');
        //         $(this).parent('.checkbox-info').append('<input type="hidden" class="cate_val" name="collection_id[]" value="' + coll_val + '">');
        //     }
        // });
        shop.ready.add(function(){
            // shop.admin.system.ckEditor('sort_body', 100 +'%', 500, 'moono',[
            //     ['Undo','Redo','-'],
            //     ['Bold','Italic','Underline','Strike'],
            //     ['Link','Unlink','Anchor'],['Image','Youtube','Table'],
            //     ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
            //     '/',
            //     ['Font','FontSize', 'Format'],
            //     ['TextColor','BGColor','SelectAll','RemoveFormat'],['PasteFromWord','PasteText'],['Subscript','Superscript','SpecialChar'],['Source'],['ImgUploadBtn']
            // ],false,'uploadify_sort_body');
            // shop.multiupload_ele('sort_body','','#uploadify_sort_body');
            shop.admin.system.ckEditor('body', 100 + '%', 500, 'moono',[
                ['Undo','Redo','-'],
                ['Bold','Italic','Underline','Strike'],
                ['Link','Unlink','Anchor'],['Image','Youtube','Table'],
                ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','NumberedList','BulletedList','-','Outdent','Indent','Blockquote'],
                '/',
                ['Font','FontSize', 'Format'],
                ['TextColor','BGColor','SelectAll','RemoveFormat'],['PasteFromWord','PasteText'],['Subscript','Superscript','SpecialChar'],['Source']
            ]);
            // shop.admin.system.ckEditor('sale_detail', 100 + '%', 300, 'moono',[
            //     ['Undo','Redo','-'],
            //     ['Bold','Italic','Underline','Strike'],
            //     ['Link','Unlink'],
            //     ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','NumberedList','BulletedList','-','Outdent','Indent'],
            //     // '/',
            //     // ['Font','FontSize'],
            //     ['TextColor','BGColor','SelectAll','RemoveFormat']
            // ]);

            @if(\Lib::can($permission, 'tag'))
                shop.admin.tags.init({{ $tagType }}, '#tags', {{ @$data->id }});
            @endif

{{--            @if(\Lib::can($permission, 'filter'))--}}
{{--                shop.admin.filters.init('{{$filterType}}', '#filters', {{ @$data->id }},false,false,1);--}}
{{--            @endif--}}
//             shop.multiupload('body');
            shop.multiupload_ele('body','','#uploadify_body');
            {{--shop.admin.getCat(1, {{ old_blade('cat_id') }}, '{{ old_blade('lang') }}', '#cat_id');--}}
        }, true);

        function mixMoney(myfield) {
            var thousands_sep = '.',
                val = parseInt(myfield.value.replace(/[.*+?^${}()|[\]\\]/g, ''));
            myfield.value = shop.numberFormat(val, 0, '', thousands_sep);
        }

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
            shop.ajax_popup('collection/get-collect', 'POST', {id:value}, function(json) {
                app_fill_collection.show_loader();
                if (json.error == 0){
                    app_fill_collection.collection = json.data.collection;
                    app_fill_collection.hide_loader();
                }else{
                    alert(json.msg);
                }
            });

        }

        function load_filter_collection(ele) {
            var value = $(ele).val();
            shop.ajax_popup('product/get-filter-collection-by-prd-collection', 'POST', {id: value}, function (json) {
                if (json.error == 0) {
                    // app_prd_prices.filter_cate_price = json.data.filter_cate_price;
                    // app_prd_filters.filter_cate_not_price = json.data.filter_cate_not_price;
                    // app_prd_prices.hide_loader();
                    // app_prd_filters.hide_loader();
                } else {
                    alert(json.msg);
                }
            });
        }

        var parameter = new Vue({
            el: '#addLine',
            data() {
                return {
                    data: {
                      val: [],
                    },
                    item: [
                        {value: "", method: "addLine"}
                    ],
                    isShow: true,
                    objDefault: []
                }
            },
            mounted(){
                this.getParameter();
            },
            methods: {
                addLine: function () {
                    this.isShow = true;
                    return this.data.val.push('');
                },
                trashLine: function (topic_props,index) {
                    if(topic_props.length > 1) {
                        topic_props.splice(index, 1);
                    }else {
                        topic_props.splice(index, 1);
                        return topic_props.push('');
                    }
                },
                getParameter: function() {
                    var id = {{@$data->id ?? 0}};
                    $.ajax({
                        type: 'POST',
                        url: "/admin/ajax/product/get-all-Parameter",
                        data: {
                            _token:ENV.token,
                            id:id
                        },
                        // dataType: 'json',
                    }).done(function(json) {
                        parameter.data.val = typeof  json.data != 'undefined' && json.data != '' ? json.data.split('|') : [''] ;
                    });
                },
            },
        });

        {{--var lineBox = new Vue({--}}
        {{--    el: '#addLineBox',--}}
        {{--    data() {--}}
        {{--        return {--}}
        {{--            data: {--}}
        {{--              val: [],--}}
        {{--            },--}}
        {{--            item: [--}}
        {{--                {value: "", method: "addLine"}--}}
        {{--            ],--}}
        {{--            isShow: true,--}}
        {{--            objDefault: []--}}
        {{--        }--}}
        {{--    },--}}
        {{--    mounted(){--}}
        {{--        this.getlineBox();--}}
        {{--    },--}}
        {{--    methods: {--}}
        {{--        addLine: function () {--}}
        {{--            this.isShow = true;--}}

        {{--        },--}}
        {{--        trashLine: function (topic_props,index) {--}}
        {{--            if(topic_props.length > 1) {--}}
        {{--                topic_props.splice(index, 1);--}}
        {{--            }else {--}}
        {{--                topic_props.splice(index, 1);--}}
        {{--                return topic_props.push('');--}}
        {{--            }--}}
        {{--        },--}}
        {{--        getlineBox: function() {--}}
        {{--            var id = {{@$data->id ?? 0}};--}}
        {{--            $.ajax({--}}
        {{--                type: 'POST',--}}
        {{--                url: "/admin/ajax/product/get-all-LineBox",--}}
        {{--                data: {--}}
        {{--                    _token:ENV.token,--}}
        {{--                    id:id--}}
        {{--                },--}}
        {{--                // dataType: 'json',--}}
        {{--            }).done(function(json) {--}}
        {{--                lineBox.data.val = typeof  json.data != 'undefined' && json.data != '' ? json.data.split('|') : [''] ;--}}
        {{--            });--}}
        {{--        },--}}
        {{--    },--}}
        {{--});--}}




    </script>
@stop