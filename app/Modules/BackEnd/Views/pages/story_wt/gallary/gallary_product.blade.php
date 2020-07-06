<div id="gallery-product-vue">
    <div class="row">
        <div class="col-lg-12">
            <div class="form-group">
                <input type="hidden" id="gallery-object_id" value="{{@$object_id}}">
                <input type="hidden" id="img_upload_for_add" name="img_upload_for_add" value="{{ old('img_upload_for_add') }}">
                <div>
                    <input type="file" name="uploadify_hotel_img" id="uploadify_hotel_img" />
                    <div id="fileQueue" class="mTop10"></div>
                    <div id="descUploadStatus"><ul></ul></div>
                    <div id="logUploadResult"><ul></ul></div>
                </div>
            </div>
        </div>
    </div>
    <div>
        <div>
            <div class="gallery" id="gallery">
                <ul v-if="showList">
                    <li class="image-item" v-for="item in images" :data-id="item.id">
                        <img class="test" :src="item.image_sm" />
                        <div class="actions">
                            {{--<a href="javascript:void(0)" @click="setCover(item)" title="Ảnh đại diện"><i class="fa fa-image"></i></a>--}}
                            <a href="javascript:void(0)" @click="imageEdit(item)" title="Xem & Share" data-toggle="modal" data-target=".image-view"><i class="fa fa-search"></i></a>
                            {{--<a href="javascript:void(0)" @click="imageEdit(item)" title="Sửa ảnh" data-toggle="modal" data-target=".image-form"><i class="fa fa-pencil"></i></a>--}}
                            <a href="javascript:void(0)" @click="imageDel(item)" title="Xóa ảnh"><i class="fa fa-trash"></i></a>
                        </div>
                    </li>
                </ul>
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
            <div class="loading-notify">Đang tải ảnh q...</div>
        </div>
    </div>
</div>

@push('css_after_all')
    {!! \Lib::addMedia('admin/js/story_gallery/product_story_gallery.css') !!}
@endpush

@push('js_bot_all')
    {!! \Lib::addMedia('admin/js/story_gallery/product_story_gallery.upload.js') !!}
    <script type="text/javascript">
        var searchDataProduct = {
            object_id: '{{@$object_id}}',
            type: 'product_story',
            curCat:null,
            curImage:null,
            catMode:0,
            catForm:{title:'', des:''},
            cover:'',
            images: null,
            page: {{isset($search_data->page)?$search_data->page:1}},
            loading: true,
            langDef: '{{ isset($search_data->lang)?$search_data->lang:\Lib::getDefaultLang() }}',
            viewMore: false,
            storage:[]
        };
    </script>
    {!! \Lib::addMedia('admin/js/story_gallery/product_story_gallery.js') !!}
@endpush