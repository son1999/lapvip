
@extends('FrontEnd::layouts.home')
@section('title') {!! \Lib::siteTitle($site_title, $def['site_title'],false,true) !!} @stop

@section('content')
    <div class="page_filter_spc page_laptop" id="app_prd_category">
        <div class="container">
            <div class="page_laptop_desktop d-none d-lg-block">
                <div class="home_cate">
                    <div class="list-cates mt-2 mb-2 d-flex align-items-center" v-for="(cate) in filter.filter_cate" v-if="cate.show_filter == 1">
                        <b class="ml-3" >@{{ cate.title }} : </b>
                        <div v-for="filter in cate.filters" >
                            <div v-if="filter.checked == 1" class="cate active" >
                                <a >@{{ filter.title }}</a>
                            </div>
                            <div v-else class="cate" >
                                <a @click="choose_filters($event,filter,cate)">@{{ filter.title }}</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="filter" >
                    <div class="row">
                        <div class="col-md-3 has-padding-none">
                            <div class="sidebar-left-filter-wrap">
                                <div class="title">bộ lọc</div>
                                <div id="accordion" role="tablist">
                                    <div class="card manufacturer" v-for="(cate, index) in filter.filter_cate" v-if="cate.show_filter != 1" >
                                        <div v-if="cate.show_filter_mobile != 1">
                                            <div class="card-header" role="tab" id="headingOne">
                                                <h5 class="mb-0">
                                                    <a v-if="cate.haveCheck == 1" data-toggle="collapse" :href="'#collapseOne'+index" aria-expanded="true" aria-controls="collapseOne" > @{{ cate.title }}</a>
                                                    <a v-else data-toggle="collapse" :href="'#collapseOne'+index" aria-expanded="false" aria-controls="collapseOne" class="collapsed"> @{{ cate.title }}</a>
                                                </h5>
                                            </div>
                                            <div v-if="cate.haveCheck == 1" :id="'collapseOne'+index" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                                                <div class="card-body has-scroll">
                                                    <div class="inner">
                                                        <label v-if="cate.checkall == 1">
                                                            <input type="checkbox" checked="checked"/>Tất cả
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <label v-else>
                                                            <input type="checkbox" @click="checkAll($event,cate)"/>Tất cả
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <label v-for="(filter,i) in cate.filters">
                                                            <input type="checkbox" v-if="filter.checked == 1" checked @click="remove_filter_checkbox($event,filter)"/>
                                                            <input type="checkbox" v-else @click="choose_filters($event,filter,cate)"/>
                                                            <span  v-if="filter.title.indexOf('#') == -1">@{{ filter.title }}</span>
                                                            <span class="dumeno" v-if="filter.title.indexOf('#') != -1" v-bind:style="{backgroundColor: filter.title}"></span>
                                                            <span class="checkmark"></span>
{{--                                                            <span v-if="filter.checked == 1">--}}
                                                                <label v-if="filter.sub != 0" v-for="(filter_sub,i) in filter.sub">
                                                                    <input type="checkbox" v-if="filter_sub.checked == 1" checked @click="remove_filter_checkbox($event,filter_sub)"/>
                                                                    <input type="checkbox" v-else @click="choose_filters($event,filter_sub,cate)"/>
                                                                    <span  v-if="filter_sub.title.indexOf('#') == -1">@{{ filter_sub.title }}</span>
                                                                    <span class="dumeno" v-if="filter_sub.title.indexOf('#') != -1" v-bind:style="{backgroundColor: filter_sub.title}"></span>
                                                                    <span class="checkmark"></span>
                                                                </label>
{{--                                                            </span>--}}

                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                            <div v-else :id="'collapseOne'+index" class="collapse" role="tabpanel" aria-labelledby="headingOne">
                                                <div class="card-body has-scroll">
                                                    <div class="inner">
                                                        <label>
                                                            <input type="checkbox" checked="checked" />Tất cả
                                                            <span class="checkmark"></span>
                                                        </label>
                                                        <label v-for="filter in cate.filters">
                                                            <input type="checkbox" v-if="filter.checked == 1" checked @click="remove_filter_checkbox($event,filter)"/>
                                                            <input type="checkbox" v-else @click="choose_filters($event,filter,cate)"/>
                                                            <span  v-if="filter.title.indexOf('#') == -1">@{{ filter.title }}</span>
                                                            <span class="dumeno" v-if="filter.title.indexOf('#') != -1" v-bind:style="{backgroundColor: filter.title}"></span>
                                                            <span class="checkmark"></span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="filter-product-right">
                                <div class="filter-head">
                                    <div class="fitler-head-title">
                                        @if(isset($cate_title['child']) && $cate_title['child'] != ''){{$cate_title['parent']}} {{$cate_title['child']}}@else{{$cate_title['parent']}}@endif
{{--                                        @if(isset($cate_title['parent']) && $cate_title['parent'] != ''){{$cate_title['parent']}}@endif--}}
                                        <span>({{count($data)}} sản phẩm)</span>
                                    </div>
                                    <div class="filter-sort">
                                        <div class="wrap-btn">
                                            <button type="button"> Sắp xếp theo <span class="sortText" v-for="(item, index) in filter.sort_by" v-if="index == {{!empty(request()->sort_by) ? request()->sort_by : 0}}">@{{ item }}</span></button>
                                            <div class="has-dropdowm">
                                                <ul class="list-unstyled" >
                                                    <li v-for="(item, index) in filter.sort_by">
                                                        <a v-if="index == {{!empty(request()->sort_by) ? request()->sort_by : 0}}" class="has-icon sortdefault active"  @click="pick_sort_by($event,index)">@{{ item }}</a>
                                                        <a v-else class="has-icon sortdefault"  @click="pick_sort_by($event,index)">@{{ item }}</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="filter-box" v-if="filter.choosed_filters.length > 0">
                                    <h5>Lọc theo :</h5>
                                    <div class="filter-value" v-for="choosed_filter in filter.choosed_filters">
                                        @{{ choosed_filter.cate_title }}:
                                        <span  v-if="choosed_filter.filter_title.indexOf('#') == -1">@{{ choosed_filter.filter_title }}</span>
                                        <span class="dumeno" v-if="choosed_filter.filter_title.indexOf('#') != -1" v-bind:style="{backgroundColor: choosed_filter.filter_title}"></span>
                                        <img @click="remove_filter($event,choosed_filter)" data-src="{{asset('html-viettech/images/icon-close-white.png')}}" class="lazyload" alt />
                                    </div>
                                    <div class="delete">
                                        Xóa tất cả
                                        <a href="javascript:;" v-if="filter.choosed_filters.length > 0" @click="remove_all_filter()"><img src="{{asset('html-viettech/images/icon-close-white.png')}}" alt /></a>
                                    </div>
                                </div>
                            </div>
                            <div class="row m-0">
                                @if(count($data) > 0)
                                    @foreach($data as $item_pro)
                                        <div class="product-item-2 col-6 col-md-4 mb-1">
                                            <a href="{{route('product.detail', ['alias' => $item_pro->alias])}}" class="wrap-img">
                                                <img data-src="{{\ImageURL::getImageUrl($item_pro->image, \App\Models\Product::KEY, 'mediumx2')}}" class="lazyload" alt="">
                                            </a>
                                            <div class="body">
                                                <a href="{{route('product.detail', ['alias' => $item_pro->alias])}}" class="name"> {{$item_pro->title}}</a>
                                                @if($item_pro->out_of_stock == 0)
                                                    @if($item_pro->priceStrike > 0)
                                                        <span class="price d-flex">
                                                            <span class="new text-danger">{{\Lib::priceFormatEdit($item_pro->price, '')['price']}} đ </span>
                                                        </span>
                                                        <span class="price d-flex">
                                                            <span class="old">{{\Lib::priceFormatEdit($item_pro->priceStrike, '')['price']}} đ </span>
                                                        </span>
                                                    @else
                                                        <span class="price d-flex">
                                                            <span class="new text-danger">{{\Lib::priceFormatEdit($item_pro->price, '')['price']}} đ </span>
                                                        </span>
                                                        <span class="price d-flex">
                                                            <span class="old"></span>
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="price d-flex">
                                                        <span class="new text-danger"> Liên hệ</span>
                                                    </span>
                                                    <span class="price d-flex">
                                                        <span class="old"></span>
                                                    </span>
                                                @endif
                                                <div class="stars mt-1">
                                                    <span class="vote"><span class="star" data-vote="{{$item_pro->rate_avg != 0 ? $item_pro->rate_avg : 0}}"></span></span>
                                                </div>
{{--                                                @if($item_pro->count > 2)--}}
{{--                                                <div class="count-config">--}}
{{--                                                    <span class="fs-12 text-white">Có <i>{{$item_pro->count}}</i> {{!empty($item_pro->option) ? $item_pro->option : 'lựa chọn cấu hình'}}</span>--}}
{{--                                                </div>--}}
{{--                                                @endif--}}


                                                <div class="des">
                                                    @foreach(explode('|', $item_pro->parameter) as $key => $parameter_product)
                                                        @if($key < 5)
                                                            @php($str = substr( $parameter_product, 0, strpos( $parameter_product, ":" )))
                                                            <div class="prameter-filter d-flex">
                                                                <b >{{$str}}</b>
                                                                <span >{{str_replace($str,'',$parameter_product)}}</span>
                                                            </div>
                                                        @endif
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="w-100 bg-light">
                                        <img class="rounded mx-auto d-block mt-5 lazyload" data-src="{{asset('images/noti-search.png')}}" alt="">
                                        <p class="name text-center w-100">Rất tiếc chúng tôi không tìm thấy kết quả theo yêu cầu của bạn. Vui lòng thử lại .</p>
                                    </div>
                                @endif
                                @if ($data->total() > 15)
                                    <div class="col-12 m-0">
                                        <div class="bg-white row py-5 justify-content-center mt-5">
                                            <nav aria-label="Page navigation" class="main-wrap">
                                                {{$data->links('FrontEnd::layouts.pagin')}}
                                            </nav>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page_laptop_mobile d-block d-lg-none">
            <div class="container">
                {!! \Lib::renderBreadcrumb(false, true,'FrontEnd::layouts.breadcrumb') !!}

                <div class="" v-for="(fill, index) in filter.filter_cate">
                    <div class="head-price" v-if="fill.sort == 1">
                        <ul class="d-flex d-lg-none pl-0 flex-wrap justify-content-start justify-content-lg-around filter-price">
                            <div v-for="cate in fill.filters">
                                <li>
                                    <a class="title" @click="choose_filters($event,cate,fill)"><span>@{{ cate.title }}</span></a>
                                </li>
                            </div>
                        </ul>
                    </div>
                    <div class="head-price" v-if="fill.sort == 2">
                        <ul class="d-flex d-lg-none pl-0 flex-wrap justify-content-start justify-content-lg-around filter-price">
                            <div v-for="cate in fill.filters">
                                <li>
                                    <a class="title" @click="choose_filters($event,cate,fill)"><span>@{{ cate.title }}</span></a>
                                </li>
                            </div>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="banner_page_laptop" v-if="Object.keys(filter.filter_cate).length > 1">
                @if(isset($slide) && !empty($slide))
                    <div class="js-carousel" data-items="1" data-arrows="false" data-autoplay="true">
                        @foreach($slide as $i_slide)
                            <a href="{{$i_slide->link}}" class="banner_item">
                                <img data-src="{{$i_slide->getImageUrl('original')}}" class="lazyload" alt="">
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="box-head" >
                <span class="title">@if(isset($cate_title['child']) && $cate_title['child'] != ''){{$cate_title['parent']}} {{$cate_title['child']}}@else{{$cate_title['parent']}}@endif</span>
                <button class="show_sort">Sắp xếp theo <i class="fa fa-angle-down"></i></button>
                <div class="modal-sort d-none">
                    <div class="head">
                        <span>Sắp xếp theo:</span>
                        <div class="close-modal-sort"><i class="fa fa-times"></i></div>
                    </div>
                    <ul class="cont">
                        <div v-for="(item, index) in filter.sort_by" >
                            <li class="sort active" v-if="index == {{!empty(request()->sort_by) ? request()->sort_by : 0}}" >
                                <a @click="pick_sort_by($event,index)">@{{ item }}</a>
                            </li>
                            <li class="sort" v-else >
                                <a @click="pick_sort_by($event,index)">@{{ item }}</a>
                            </li>
                        </div>
                    </ul>
                </div>
            </div>
            <div class="box-filter" >
                <div v-for="(fill, index) in filter.filter_cate">
                        <div class="child" v-if="fill.sort == 1">
                            <button class=""> <span>@{{ fill.title }}</span> </button>
                            <ul class="dropdown-list d-none" >
                                <div v-for="cate in fill.filters">
                                    <li v-if="cate.checked == 1" class="active"><a @click="choose_filters($event,cate,fill)">@{{ cate.title }}</a></li>
                                    <li v-else><a @click="choose_filters($event,cate,fill)">@{{ cate.title }}</a></li>
                                </div>
                            </ul>
                        </div>
                        <div class="child" v-if="fill.sort == 2">
                            <button class=""> <span>@{{ fill.title }}</span> </button>
                            <ul class="dropdown-list d-none" >
                                <div v-for="cate in fill.filters">
                                    <li v-if="cate.checked == 1" class="active"><a @click="choose_filters($event,cate,fill)">@{{ cate.title }}</a></li>
                                    <li v-else><a @click="choose_filters($event,cate,fill)">@{{ cate.title }}</a></li>
                                </div>
                            </ul>
                        </div>
                        <div class="child" v-if="fill.show_filter_mobile == 1">
                            <button class=""> <span>@{{ fill.title }}</span> </button>
                            <ul class="dropdown-list d-none" >
                                <div v-for="cate in fill.filters">
                                    <li v-if="cate.checked == 1" class="active"><a @click="choose_filters($event,cate,fill)">@{{ cate.title }}</a></li>
                                    <li v-else><a @click="choose_filters($event,cate,fill)">@{{ cate.title }}</a></li>
                                </div>
                            </ul>
                        </div>

                </div>
            </div>
            <div class="box-filter-value d-lg-none" v-if="filter.choosed_filters.length > 0">
                <ul>
                    <div class="d-inline-block" v-for="choosed_filter in filter.choosed_filters">
                        <li>
                            <span class="vlu" @click="remove_filter($event,choosed_filter)">@{{ choosed_filter.filter_title }}</span>
                        </li>
                    </div>
                    <li class="pow">
                        <span v-if="filter.choosed_filters.length > 0" @click="remove_all_filter()" class="delete"><i></i></span>
                    </li>
                </ul>
            </div>
            @if($data->total() > 0)
                <div class="list-products" id="load-data">
                    @foreach($data as $item_mobile)
                        <div class="product_item_laptop_mobile">
                            @if($item_mobile->is_tragop > 0)
                                <span class="sale-mobile">
                                    Trả góp 0%
                                </span>
                            @endif
                            <div class="demo">
                                <a href="{{route('product.detail', ['alias' => $item_mobile->alias])}}" class="wrap-img">
                                    <img class="mobile-demo lazyload" data-src="{{\ImageURL::getImageUrl($item_mobile->image, \App\Models\Product::KEY, 'medium')}}" alt="">
                                </a>
                            </div>
                            <div class="wrap-right">
                                <a href="{{route('product.detail', ['alias' => $item_mobile->alias])}}" class="name">{{$item_mobile->title}}</a>
                                <span class="price text-danger">@if($item_mobile->out_of_stock == 0){{\Lib::priceFormatEdit($item_mobile->price, '')['price']}} đ @else Liên hệ @endif</span>
                                <div class="stars">
                                    <span class="vote"><span class="star" data-vote="{{$item_mobile->rate_avg}}"></span></span>
                                </div>
                                <div class="info">
                                    @foreach(explode('|', $item_mobile->parameter) as $item_info)
                                        <div>
                                            <span>{{$item_info}}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                @if($data->lastPage() - $data->currentPage() > 0)
                    <div id="remove-row">
                        <button id="btn-more" data-page="{{$data->currentPage() + 1}}" @if(!empty(request()->child)) data-child="{{request()->child}}" @endif data-pid="{{request()->cate}}" @if(!empty(request()->sort_by)) data-sort="{{request()->sort_by}}" @endif @if(!empty(request()->filter_ids)) data-filter="{{request()->filter_ids}}" @endif class="more-prd">Xem thêm sản phẩm <i class="fa fa-angle-down"></i></button>
                    </div>
                @endif
            @else
                <div class="list-products">
                    <div class="w-100 bg-light">
                        <img class="rounded mx-auto d-block mt-5 lazyload" data-src="{{asset('images/noti-search.png')}}"  alt="">
                        <p class="name text-center w-100">Rất tiếc chúng tôi không tìm thấy kết quả theo yêu cầu của bạn. Vui lòng thử lại .</p>
                    </div>
                </div>
            @endif
            <div class="bottom-filter mt-3">
                <ul class="detail-filt px-2 pt-3 pb-1" v-for="(fill, index) in filter.filter_cate" v-if="fill.show_filter_mobile == 1">
                    <div v-for="cate in fill.filters">
                        <li v-if="cate.checked == 1" class="active"><a @click="choose_filters($event,cate,fill)">@{{ cate.title }}</a></li>
                        <li v-else ><a @click="choose_filters($event,cate,fill)">@{{ cate.title }}</a></li>
                    </div>
                </ul>
            </div>
        </div>
    </div>
    <div class="bhx-main container"></div>
@endsection

@push('js_bot_all')
    <script>
        var sort_by = '{!! json_encode($orderClauseText) !!}';
        var filter_cate = '{!! json_encode($filter_cates) !!}';
        var choosed_filters = '{!! json_encode($choosed_filters) !!}';
        var choosed_filters_menu = '{!! json_encode($choosed_filters_menu) !!}';
    </script>
    {!! \Lib::addMedia('js/features/product/category.js') !!}
    <script>
        $(document).ready(function(){
            $(".sidebar-left-filter-wrap .card .card-body .inner").each(function(i) {
                $this = $(this);
                $checked = $this.find("input:checked");
                if($checked.length) {
                    $this.animate({
                        scrollTop: $checked.offset().top - $this.offset().top - $this.height() / 2
                    }, 1);
                }
            });
            $(document).on('click','#btn-more',function(){
                var page = $(this).data('page');
                var id = $(this).data('child');
                var pid = $(this).data('pid');
                var sort = $(this).data('sort');
                var filter = $(this).data('filter');
                $("#btn-more").html('<img src={{asset('html-viettech/images/loading.gif')}} >');
                shop.ajax_popup('loadMoreFilterAjax', 'POST', {page:page, pid:pid, id:id, sort_by:sort, filter_ids:filter, _token:"{{csrf_token()}}"}, function(json) {
                    var html = '';
                    $.each(json.data.product.data.data,function (ind,value) {
                        var img = '{!! asset("upload/products/thumb_250x0") !!}/' + value.image;
                        var price = shop.numberFormat(value.price);
                        let newArray = value.parameter.split("|", 3);
                        var sapo = '';
                        $.each(newArray, function (idss, values) {
                            sapo +='<div >' +
                                '       <span>'+values+'</span>' +
                                '    </div>'
                        })

                        if (value.is_tragop > 0){
                            html += '<div class="product_item_laptop_mobile">' +
                                '       <span class="sale-mobile">' +
                                '            Trả góp 0%' +
                                '        </span>'+
                                '        <div class="demo">' +
                                '            <a href="" class="wrap-img">' +
                                '                <img class="mobile-demo" src="'+img+'" alt="">' +
                                '            </a>' +
                                '        </div>' +
                                '        <div class="wrap-right">' +
                                '             <a href="" class="name">'+value.title+'</a>' +
                                '            <span class="price">'+price+' VNĐ</span>' +
                                '            <div class="stars">' +
                                '                <span class="vote"><span class="star" data-vote="'+value.rate_avg+'"></span></span>' +
                                '            </div>' +
                                '            <div class="info">' +
                                '' + sapo + '' +
                                '            </div>' +
                                '        </div>' +
                                '    </div>'
                        }else {
                            html += '<div class="product_item_laptop_mobile">' +
                                '        <div class="demo">' +
                                '            <a href="" class="wrap-img">' +
                                '                <img class="mobile-demo" src="'+img+'" alt="">' +
                                '            </a>' +
                                '        </div>' +
                                '        <div class="wrap-right">' +
                                '             <a href="" class="name">'+value.title+'</a>' +
                                '            <span class="price">'+price+'</span>' +
                                '            <div class="stars">' +
                                '                <span class="vote"><span class="star" data-vote="'+value.rate_avg+'"></span></span>' +
                                '            </div>' +
                                '            <div class="info">' +
                                '' + sapo + '' +
                                '            </div>' +
                                '        </div>' +
                                '    </div>'
                        }

                    });
                    var page = json.data.product.data.current_page + 1;
                    $('#load-data').append(html);
                    if(json.data.product.count > 0){
                        $('#btn-more').html('<button id="btn-more" data-page="'+page+'" @if(!empty(request()->child)) data-child="{{request()->child}}" @endif data-pid="{{request()->cate}}" @if(!empty(request()->sort_by)) data-sort="{{request()->sort_by}}" @endif @if(!empty(request()->filter_ids)) data-filter="{{request()->filter_ids}}" @endif class="more-prd">Xem thêm sản phẩm <i class="fa fa-angle-down"></i></button>');
                    }else{
                        $('#remove-row').remove();
                    }

                });

            });
        });
    </script>
@endpush