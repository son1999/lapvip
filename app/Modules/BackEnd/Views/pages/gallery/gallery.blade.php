@extends('BackEnd::layouts.default')

@section('content')
<div id="gallery-vue">
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-5"><h1>{{ $site_title }}</h1></div>
            <div class="card card-accent-info">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Chuyên mục
                    <div class="card-actions">
                        <a href="javascript:void(0)" class="btn-setting" @click="catAdd" data-toggle="modal" data-target=".category-form" title="Thêm danh mục mới"><i class="icon-plus"></i></a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="gallery-category">Chọn chuyên mục</label>
                        <div class="col-md-3">
                            <select id="gallery-category" class="form-control" @change="catChange" v-model="selected">
                                <option v-for="item in category" :value="item.id" :selected="selected==item.id" v-text="item.title + ' ('+item.total+')'"></option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <button v-if="notDefault()" type="button" class="btn btn-outline-primary mr-2" @click="catEdit" data-toggle="modal" data-target=".category-form" title="Sửa thông tin"><i class="fe-menu"></i>&nbsp; Sửa</button>
                            <button v-if="notDefault()" type="button" class="btn btn-outline-danger mr-2" @click="catRemove" title="Xóa danh mục"><i class="fa fa-trash"></i>&nbsp; Xóa</button>
                            <button type="button" class="btn btn-outline-success" @click="catUpdate" title="Cập nhật lại số lượng"><i class="fa fa-refresh"></i>&nbsp; Cập nhật</button>
                        </div>
                        <div class="col-md-1 coverImage" v-if="cover!=''"><img :src="cover"></div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label" for="uploadify">
                            Upload nhiều ảnh<br>
                            <em>Size: <b class="text-danger">840x425 px</b></em>
                        </label>
                        <div class="col-md-9">
                            <input type="file" name="uploadify" id="uploadify" />
                            <div id="fileQueue" class="mTop10"></div>
                            <div id="descUploadStatus"><ul></ul></div>
                            <div id="logUploadResult"><ul></ul></div>
                        </div>
                    </div>
                    <hr>
                    <div class="form-group row">
                        <label class="col-md-3 form-control-label">Bộ lọc</label>
                        <div class="col-md-3">
                            <select class="form-control" id="filter-lang" @change="filter">
                                <option v-for="(item, index) in lang" :value="index" :selected="langDef==index" v-text="item"></option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-accent-info">
                <div class="card-header">
                    <i class="fa fa-align-justify"></i> Danh sách ảnh - <b class="text-success" v-text="catTitle"></b>
                </div>
                <div class="card-body">
                    <div class="gallery" id="gallery">
                        <ul v-if="showList">
                            <li class="image-item" v-for="item in images" :data-id="item.id">
                                <img :src="item.image_sm" />
                                <div class="actions">
                                    <a href="javascript:void(0)" @click="setCover(item)" title="Ảnh đại diện"><i class="fa fa-image"></i></a>
                                    <a href="javascript:void(0)" @click="imageEdit(item)" title="Xem & Share" data-toggle="modal" data-target=".image-view"><i class="fa fa-search"></i></a>
                                    <a href="javascript:void(0)" @click="imageEdit(item)" title="Sửa ảnh" data-toggle="modal" data-target=".image-form"><i class="fe-menu"></i></a>
                                    <a href="javascript:void(0)" @click="imageDel(item)" title="Xóa ảnh"><i class="fa fa-trash"></i></a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade category-form" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-text="catPopupTitle"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-tag"></i></span>
                                    <input type="text" id="upload-form-title-cat" class="form-control" placeholder="Tiêu đề" :value="catForm.title" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-sm-12">
                                <div class="input-group">
                                    <span class="input-group-addon"><i class="icon-note"></i></span>
                                    <textarea rows="3" id="upload-form-des-cat" class="form-control" placeholder="Mô tả" :value="catForm.des"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"> <i class="fa fa-close"></i> Hủy bỏ</button>
                    <button type="button" class="btn btn-sm btn-primary" @click="catSubmit"><i class="fa fa-arrow-circle-right"></i> <span v-text="catMode==1?'Cập nhật':'Thêm mới'"></span></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade image-form" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-sl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Sửa thông tin ảnh</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form id="upload-image-form" enctype="multipart/form-data" method="POST" action="{{ route('admin.'.$key.'ajax', ['cmd' => 'edit']) }}">
                        <div class="form-group">
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-tag"></i></span>
                                        <input type="text" id="image-title" class="form-control" placeholder="Tiêu đề" :value="curImage?curImage.title:''" required onfocus="this.selected()">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-arrow-up-circle"></i></span>
                                        <input type="text" id="image-sort" class="form-control" placeholder="Sắp xếp" :value="curImage?curImage.sort:''" required onfocus="this.selected()">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-puzzle"></i></span>
                                        <select class="form-control" id="image-lang">
                                            <option v-for="(item, index) in lang" :value="index" :selected="curImage&&(curImage.lang==index)" v-text="item"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-list"></i></span>
                                        <select id="image-cat" class="form-control">
                                            <option v-for="item in category" :value="item.id" :selected="curImage&&(curImage.cat_id==item.id)" v-text="item.title"></option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-sm-12">
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="icon-picture"></i></span>
                                        <input type="file" name="image" size="30" id="new_image" class="form-control" value="" />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"> <i class="fa fa-close"></i> Hủy bỏ</button>
                    <button type="button" class="btn btn-sm btn-primary" @click="imageSubmit"><i class="fa fa-arrow-circle-right"></i> <span>Cập nhật</span></button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade image-view" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" v-html="curImage?curImage.title:''"></h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <a href="javascript:void(0)" @click="viewMore=!viewMore">Xem thêm thông tin</a>
                        </div>
                    </div>
                    <div class="row mb-3" v-if="viewMore">
                        <div class="col-md-12">
                            <div class="card card-accent-info">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3 font-weight-bold">Danh mục:</div>
                                        <div class="col-md-9" v-text="catTitle"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 font-weight-bold">Thể loại:</div>
                                        <div class="col-md-9" v-text="curImage?curImage.type:''"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 font-weight-bold">Dung lượng:</div>
                                        <div class="col-md-9" v-text="size(curImage)"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 font-weight-bold">Ngày tạo:</div>
                                        <div class="col-md-9" v-text="dateFormat(curImage?curImage.created:0)"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 font-weight-bold">Ngày sửa:</div>
                                        <div class="col-md-9" v-text="dateFormat(curImage?curImage.changed:0)"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3 font-weight-bold">Người thay đổi:</div>
                                        <div class="col-md-9" v-text="curImage?curImage.uname:''"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div align="center" class="mb-3"><img :src="curImage?curImage.image:''" height="400"></div>
                    <div class="form-group">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="imageLink">Link Ảnh</label>
                            <div class="col-md-9">
                                <input type="text" id="imageLink" class="form-control" :value="curImage?curImage.image:''">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="imageLink">Link Ảnh gốc</label>
                            <div class="col-md-9">
                                <input type="text" id="imageLink" class="form-control" :value="curImage?curImage.image_org:''">
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="form-group row">
                            <label class="col-md-3 form-control-label" for="imageLink">HTML Code</label>
                            <div class="col-md-9">
                                <input type="text" id="imageLink" class="form-control" :value="htmlCode(curImage)" onfocus="this.select()">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal"> <i class="fa fa-close"></i> Hủy bỏ</button>
                </div>
            </div>
        </div>
    </div>

    <div class="gallery-loading" v-if="loading">
        <div class="bg-loading" align="center">
            <img src="{{asset('admin/js/gallery/loading.gif')}}">
            <div class="loading-notify">Vui lòng không tắt trình duyệt và chờ trong giây lát...</div>
        </div>
    </div>
</div>
@stop

@section('css')
    {!! \Lib::addMedia('admin/js/library/uploadifive/uploadifive.css') !!}
    {!! \Lib::addMedia('admin/js/gallery/gallery.css') !!}
@stop

@section('js_bot')
{!! \Lib::addMedia('admin/js/library/uploadifive/jquery.uploadifive.min.js') !!}
{!! \Lib::addMedia('admin/js/gallery/gallery.upload.js') !!}
<script type="text/javascript">
    var searchData = {
        category: {!! $cat !!},
        selected: {{$search_data->cat?$search_data->cat:1}},
        curCat:null,
        curImage:null,
        catMode:0,
        catForm:{title:'', des:''},
        cover:'',
        images: null,
        page: {{$search_data->page?$search_data->page:1}},
        loading: true,
        lang: {!! $lang !!},
        langDef: '{{ $search_data->lang?$search_data->lang:\Lib::getDefaultLang() }}',
        viewMore: false,
        storage:[]
    };
</script>
{!! \Lib::addMedia('admin/js/gallery/jquery.form.js') !!}
{!! \Lib::addMedia('admin/js/gallery/jquery.sortable.js') !!}
{!! \Lib::addMedia('admin/js/gallery/vue.js') !!}
{!! \Lib::addMedia('admin/js/gallery/gallery.js') !!}
@stop

