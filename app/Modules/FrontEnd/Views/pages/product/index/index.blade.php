
@extends('FrontEnd::layouts.home')
@section('title') {!! \Lib::siteTitle($site_title, $def['site_title'],false,true) !!} @stop
@section('meta_basic')
    <meta name="title" content="{{ !empty($metaSeo->title_seo) ? $metaSeo->title_seo : ''}}"/>
    <meta name="description" content="{{!empty($metaSeo->description) ? $metaSeo->description : ''}}"/>
    <meta name="keywords" content="{{ !empty($metaSeo->keywords) ? $metaSeo->keywords : ''}}"/>
@stop
@section('facebook_meta')
    <meta property="og:locale" content="vi_VN" />
    <meta property="og:title" content="{{!empty($metaSeo->title_seo) ? $metaSeo->title_seo : ''}}" />
    <meta property="og:description" content="{{!empty($metaSeo->description) ? $metaSeo->description : ''}}" />
    <meta property="og:url" content="{{url()->current()}}" />
    <meta property="og:site_name" content="{{env('APP_NAME')}}" />
    <meta property="og:image" content="{{ !empty($metaSeo->image) ? $metaSeo->getImageUrl('largex2') : ''}}" />
    <meta property="og:image:width" content="800" />
    <meta property="og:image:height" content="800" />
@stop
@section('content')
    <main id="app_prd_category" class="page_laptop">
        <div class="container">
            {!! \Lib::renderBreadcrumb(false, true,'FrontEnd::layouts.breadcrumb') !!}
            <div class="filter-cate-mo d-none" v-for="(fill, index) in filter.filter_cate">
                <div class="head-price" v-if="fill.sort == 1">
                    <h5>@{{ fill.title }}</h5>
                    <ul class="d-lg-flex d-none pl-0 flex-wrap justify-content-start justify-content-lg-around filter-price" >
                        <div v-for="cate in fill.filters">
                            <li>
                                <a v-bind:data-link="'{{route('product.filter',['alias_filter' => ''])}}/'+cate.safe_title+'?cate={{request()->parent_id}}&filter_ids='+cate.id" class="img" @click="choose_fil($event)">
                                    <img class="lazyload" :data-src="'{{asset('upload/original/')}}/' + cate.image" alt="">
                                </a>
                                <a v-bind:data-link="'{{route('product.filter',['alias_filter' => ''])}}/'+cate.safe_title+'?cate={{request()->parent_id}}&filter_ids='+cate.id" class="title" @click="choose_fil($event)"><span>@{{ cate.title }}</span></a>
                            </li>
                        </div>
                        <li>
                            <a v-bind:data-link="'{{route('product.filter',['alias_filter' => 'xem-tat-ca'])}}/?cate={{request()->parent_id}}&sort_by=2'" class="img" @click="choose_fil($event)">
                                <img src="{{asset('html-viettech/images/price_dot.png')}}" alt="">
                            </a>
                            <a v-bind:data-link="'{{route('product.filter',['alias_filter' => 'xem-tat-ca'])}}/?cate={{request()->parent_id}}&sort_by=2'" class="title" @click="choose_fil($event)"><span>Xem tất cả &rarr;</span></a>
                        </li>
                    </ul>
                    <ul class="d-flex d-lg-none pl-0 flex-wrap justify-content-start justify-content-lg-around filter-price">
                        <div v-for="cate in fill.filters">
                            <li>
                                <a class="title" @click="choose_filters($event,cate,fill)"><span>@{{ cate.title }}</span></a>
                            </li>
                        </div>
                    </ul>
                </div>
                <div class="head-price" v-if="fill.sort == 2">
                    <ul class="d-flex d-lg-none pl-0 flex-wrap justify-content-start justify-content-lg-around filter-cate" >
                        <div v-for="cate in fill.filters">
                            <li>
                                <a class="title" @click="choose_filters($event,cate,fill)"><span>@{{ cate.title }}</span></a>
                            </li>
                        </div>

                    </ul>
                </div>
            </div>
        </div>

        <div class="container banner_page_laptop" v-if="Object.keys(filter.filter_cate).length > 1">
            <div class="js-carousel" data-items="1" data-arrows="false" data-autoplay="true">
                @foreach($slide as $i_slide)
                    <a href="{{$i_slide->link}}" class="banner_item">
                        <img class="owl-lazy" data-src="{{$i_slide->getImageUrl('large')}}"  alt="">
                    </a>
                @endforeach
            </div>
        </div>
        <div class="container filter-cate-mo d-none">
            <div class="section_brannd" v-for="(fill, index) in filter.filter_cate" v-if="fill.sort == 2">
                <h5>@{{ fill.title }}</h5>
                <div class="brand_laptop js-carousel" data-items="7" data-arrows="false" data-dots="false"  >
                    <div v-for="cate in fill.filters">
                        <div class="brand_laptop-item">
                            <a v-bind:data-link="'{{route('product.filter',['alias_filter' => ''])}}/'+cate.safe_title+'?cate={{request()->parent_id}}&filter_ids='+cate.id" class="image" @click="choose_fil($event)" >
                                <img :data-src="'{{asset('upload/original/')}}/' + cate.image" class="lazyload" alt="">
                            </a>
                            <a v-bind:data-link="'{{route('product.filter',['alias_filter' => ''])}}/'+cate.safe_title+'?cate={{request()->parent_id}}&filter_ids='+cate.id" class="name" @click="choose_fil($event)">
                                @{{ cate.title }}
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="page_laptop_desktop d-none d-lg-block">
            @foreach($collec as $item_col)
                @if(count($item_col['products']) > 0)
                    <div class="container mt-5">
                        <div class="home-title-cate">
                            <h3 ><a href="{{route('product.filter',['alias_filter' => str_slug($item_col['title']), 'cate' => \request()->parent_id, 'filter_ids' => $item_col['filter_id']])}}"  class="name">
                                    {{$item_col['title']}}
                                </a> </h3>
                        </div>
                        <div class="js-carousel mb-4" data-items="4" data-dots="false" data-loop="true">
                            @foreach($item_col['products'] as $item_pro)
                                <div class="item" style="margin: 0 1px">
                                    <div class="product-item-2">
                                        <a href="{{route('product.detail', ['alias' => $item_pro['alias']])}}" class="wrap-img">
                                            <img class="owl-lazy" data-src="{{\ImageURL::getImageUrl($item_pro['image'], \App\Models\Product::KEY, 'mediumx2')}}" alt="">
                                        </a>
                                        <div class="body">
                                            <a href="{{route('product.detail', ['alias' => $item_pro['alias']])}}" class="name"> {{$item_pro['title']}}</a>
{{--                                            @if($item_pro['priceStrike'] > 0)--}}
{{--                                                <span class="price">--}}
{{--                                                    <span class="new">{{\Lib::priceFormatEdit($item_pro['priceStrike'], '')['price']}}<sup>đ</sup></span>--}}
{{--                                                </span>--}}
{{--                                            @else--}}
                                                <span class="price">
                                                    @if($item_pro['out_of_stock'] == 0)
                                                        <span class="new text-danger">{{\Lib::priceFormatEdit($item_pro['price'], '')['price']}} đ </span>
                                                        @if($item_pro['priceStrike'] > 0)
                                                            <span class="old">{{\Lib::priceFormatEdit($item_pro['priceStrike'], '')['price']}} đ </span>
                                                        @endif
                                                    @else
                                                        <span class="new text-danger">Liên hệ</span>
                                                    @endif
                                                </span>
{{--                                            @endif--}}
                                            <div class="stars">
                                                <span class="vote"><span class="star" data-vote="{{$item_pro['rate_avg'] != 0 ? $item_pro['rate_avg'] : 0}}"></span></span>
                                            </div>
{{--                                            @if($item_pro['count'] > 2)--}}
{{--                                                <div class="count-config">--}}
{{--                                                    <span class="fs-12 text-white">Có <i>{{$item_pro['count']}}</i> {{!empty($item_pro['option']) ? $item_pro['option'] : 'lựa chọn cấu hình'}}</span>--}}
{{--                                                </div>--}}
{{--                                            @endif--}}
                                            <div class="des">
                                                @foreach(explode('|', $item_pro['parameter']) as $key => $parameter_product)
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
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
        <div class="page_laptop_mobile d-block d-lg-none">
            <div class="box-head" >
                <span class="title">{{$cate_title}}</span>
                <button class="show_sort">Sắp xếp theo <i class="fa fa-angle-down"></i></button>
                <div class="modal-sort d-none">
                    <div class="head">
                        <span>Sắp xếp theo:</span>
                        <div class="close-modal-sort"><i class="fa fa-times"></i></div>
                    </div>
                    <ul class="cont d-none">
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
            <div class="box-filter d-none" >
                <div v-for="(fill, index) in filter.filter_cate">
                    <div class="child" v-if="index < 2">
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
            <div class="box-filter-value d-lg-none d-none" v-if="filter.choosed_filters.length > 0">
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
            @if($pro_mobile->total() > 0)
                <div class="list-products" id="load-data">
                    @foreach($pro_mobile as $item_mobile)
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
                                <span class="price text-danger">@if($item_mobile->out_of_stock == 0) {{\Lib::priceFormatEdit($item_mobile->price, '')['price']}} đ @else Liên hệ @endif</span>
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
                @if($pro_mobile->lastPage() - $pro_mobile->currentPage() > 0)
                    <div id="remove-row">
                        <button id="btn-more" data-page="{{$pro_mobile->currentPage() + 1}}" data-pid="{{request()->parent_id}}" @if(!empty(request()->sort_by)) data-sort="{{request()->sort_by}}" @endif @if(!empty(request()->filter_ids)) data-filter="{{request()->filter_ids}}" @endif class="more-prd">Xem thêm sản phẩm <i class="fa fa-angle-down"></i></button>
                    </div>
                @endif
            @else
                <div class="list-products">
                    <div class="w-100 bg-light">
                        <img class="rounded mx-auto d-block mt-5 lazyload" data-src="{{asset('images/noti-search.png')}}" alt="">
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
    </main>
@endsection
@push('js_bot_all')
    <script>
        var sort_by = '{!! json_encode($orderClauseText) !!}';
        var sort_by = '{!! json_encode($orderClauseText) !!}';
        var filter_cate = '{!! json_encode($filter_cates) !!}';
        var choosed_filters = '{!! json_encode($choosed_filters) !!}';
    </script>
        {!! \Lib::addMedia('js/features/product/category.js') !!}
    <script>
        $(document).ready(function(){
            $(document).on('click','#btn-more',function(){
                var page = $(this).data('page');
                var pid = $(this).data('pid');
                var sort = $(this).data('sort');
                var filter = $(this).data('filter');
                $("#btn-more").html('<img src={{asset('html-viettech/images/loading.gif')}}>');
                shop.ajax_popup('loadMoreAjax', 'POST', {page:page, pid:pid, sort_by:sort, filter_ids:filter, _token:"{{csrf_token()}}"}, function(json) {
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
                            if (value.out_of_stock == 0){
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
                                    '            <span class="price text-danger">'+price+' đ</span>' +
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
                                    '       <span class="sale-mobile">' +
                                    '            Trả góp 0%' +
                                    '        </span>'+
                                    '        <div class="demo">' +
                                    '            <a href="" class="wrap-img">' +
                                    '                <img class="mobile-demo lazyload" data-src="'+img+'" alt="">' +
                                    '            </a>' +
                                    '        </div>' +
                                    '        <div class="wrap-right">' +
                                    '             <a href="" class="name">'+value.title+'</a>' +
                                    '            <span class="price text-danger">Liên hệ</span>' +
                                    '            <div class="stars">' +
                                    '                <span class="vote"><span class="star" data-vote="'+value.rate_avg+'"></span></span>' +
                                    '            </div>' +
                                    '            <div class="info">' +
                                    '' + sapo + '' +
                                    '            </div>' +
                                    '        </div>' +
                                    '    </div>'
                            }
                        }else {
                            if (value.out_of_stock == 0) {
                                html += '<div class="product_item_laptop_mobile">' +
                                    '        <div class="demo">' +
                                    '            <a href="" class="wrap-img">' +
                                    '                <img class="mobile-demo" src="'+img+'" alt="">' +
                                    '            </a>' +
                                    '        </div>' +
                                    '        <div class="wrap-right">' +
                                    '             <a href="" class="name">'+value.title+'</a>' +
                                    '            <span class="price text-danger">'+price+' đ</span>' +
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
                                    '                <img class="mobile-demo lazyload" data-src="' + img + '" alt="">' +
                                    '            </a>' +
                                    '        </div>' +
                                    '        <div class="wrap-right">' +
                                    '             <a href="" class="name">' + value.title + '</a>' +
                                    '            <span class="price text-danger">Liên hệ</span>' +
                                    '            <div class="stars">' +
                                    '                <span class="vote"><span class="star" data-vote="' + value.rate_avg + '"></span></span>' +
                                    '            </div>' +
                                    '            <div class="info">' +
                                    '' + sapo + '' +
                                    '            </div>' +
                                    '        </div>' +
                                    '    </div>'
                            }
                        }

                    });
                    var page = json.data.product.data.current_page + 1;
                    $('#load-data').append(html);
                    if(json.data.product.count > 0){
                        $('#btn-more').html('<button id="btn-more" data-page="'+page+'" data-pid="{{request()->parent_id}}" @if(!empty(request()->sort_by)) data-sort="{{request()->sort_by}}" @endif @if(!empty(request()->filter_ids)) data-filter="{{request()->filter_ids}}" @endif class="more-prd">Xem thêm sản phẩm <i class="fa fa-angle-down"></i></button>');
                    }else{
                        $('#remove-row').remove();
                    }

                });

            });
        });
    </script>

@endpush

