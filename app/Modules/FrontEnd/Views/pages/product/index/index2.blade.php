
@extends('FrontEnd::layouts.home')
@section('title') {!! \Lib::siteTitle($site_title, $def['site_title'],false,true) !!} @stop

@section('content')
    <main id="app_prd_category">
        <div class="container" >
            <div class="px-0">
                {!! \Lib::renderBreadcrumb(false, true,'FrontEnd::layouts.breadcrumb') !!}
            </div>
        </div>
{{--        <div class="container banner_page_laptop">--}}
{{--            <div class="js-carousel" data-items="1" data-arrows="false">--}}
{{--                @foreach($slide as $i_slide)--}}
{{--                    <a href="{{$i_slide->link}}" class="banner_item">--}}
{{--                        <img src="{{$i_slide->getImageUrl('original')}}" alt="">--}}
{{--                    </a>--}}
{{--                @endforeach--}}
{{--            </div>--}}
{{--        </div>--}}


        <div class="container">
            @if(count($data) > 0)
                <div class="head-filter-page">
                    {{--<h2>phền mềm</h2>--}}
                    <div class="filter-main">
    {{--                    <span>Lọc</span>--}}
    {{--                    <ul class="list-cat">--}}
    {{--                        @foreach($category as $item_cat)--}}
    {{--                            @if(!empty($item_cat['sub']) && $item_cat['id'] == request()->parent_id)--}}
    {{--                                @foreach($item_cat['sub'] as $subcat)--}}
    {{--                                    <li >--}}
    {{--                                        <a @if(request()->child_id == $subcat['id']) class="active" @endif href="{{ route('product.list', ['alias'=> str_slug($subcat['title']), 'parent_id' => $item_cat['id'], 'id' => $subcat['id'] ]) }}" >{{$subcat['title']}}</a>--}}
    {{--                                    </li>--}}
    {{--                                @endforeach--}}
    {{--                            @endif--}}
    {{--                        @endforeach--}}
    {{--                    </ul>--}}
                        <div class="filter-sort">
                            <button type="button"> Sắp xếp theo <span class="sortText" v-for="(item, index) in filter.sort_by" v-if="index == {{!empty(request()->sort_by) ? request()->sort_by : 0}}">@{{ item }}</span></button>
                            <div class="has-dropdowm">
                                <ul class="list-unstyled" >
                                    <li v-for="(item, index) in filter.sort_by">
                                        <a class="has-icon sortdefault "  @click="pick_sort_by($event,index)">@{{ item }}</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row p-list m-0">
                    @foreach($data as $item_manu)
                        <div class="product-item-2 col-6 col-md-4 col-lg-3 d-none d-lg-block">
                            <a href="{{route('product.detail', ['alias' => $item_manu->alias])}}" class="wrap-img">
{{--                                <img data-src="{{asset('upload/products/original/'.$item_manu->image)}}" class="lazyload" alt="">--}}
                                <img data-src="{{\ImageURL::getImageUrl($item_manu->image, \App\Models\Product::KEY, 'mediumx2')}}" class="lazyload" alt="">
                            </a>
                            <div class="body">
                                <a href="{{route('product.detail', ['alias' => $item_manu->alias])}}" class="name"> {{$item_manu->title}} </a>

                                <span class="price">
                                    @if($item_manu->out_of_stock == 0)
                                        <span class="new text-danger"> {{\Lib::priceFormatEdit($item_manu->price)['price']}} đ </span>
                                        @if(!empty($item_manu->priceStrike != 0))
                                            <span class="old"> {{\Lib::priceFormatEdit($item_manu->priceStrike)['price']}} đ </span>
                                        @endif
                                    @else
                                        <span class="new text-danger">Liên hệ</span>
                                    @endif
                                </span>
{{--                                @if($item_manu->product_relates_count > 2)--}}
{{--                                    <div class="count-config">--}}
{{--                                        <span class="fs-12 text-white">Có <i>{{$item_manu->product_relates_count}}</i> {{!empty($item_manu->option) ? $item_manu->option : 'lựa chọn cấu hình'}}</span>--}}
{{--                                    </div>--}}
{{--                                @endif--}}
                                <div class="stars">
                                    <span class="vote"><span class="star" data-vote="{{$item_manu->rate_avg != 0 ? $item_manu->rate_avg : 0}}"></span></span>
                                </div>
                                <div class="des">
                                    @foreach(explode('|', $item_manu->parameter) as $key => $parameter_product)
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
                    @if ($data->total() > 16)
                        <div class="col-12 m-0 d-none d-lg-block">
                            <div class="bg-white row py-5 justify-content-center mt-5">
                                <nav aria-label="Page navigation" class="main-wrap">
                                    {{$data->links('FrontEnd::layouts.pagin')}}
                                </nav>
                            </div>
                        </div>
                    @endif

                    <div class="d-block d-lg-none" id="load-data">
                        @foreach($data as $item_manu_mobile)
                            <div class="product_item_laptop_mobile" >
                                @if($item_manu_mobile->is_tragop > 0)
                                    <span class="sale-mobile">
                                    Trả góp 0%
                                </span>
                                @endif
                                <div class="demo">
                                    <a href="{{route('product.detail', ['alias' => $item_manu_mobile->alias])}}" class="wrap-img">
                                        <img class="mobile-demo lazyload" data-src="{{\ImageURL::getImageUrl($item_manu_mobile->image, \App\Models\Product::KEY, 'medium')}}" alt="">
                                    </a>
                                </div>
                                <div class="wrap-right">
                                    <a href="{{route('product.detail', ['alias' => $item_manu_mobile->alias])}}" class="name">{{$item_manu_mobile->title}}</a>
                                    <span class="price text-danger"> @if($item_manu_mobile->out_of_stock == 0){{\Lib::priceFormatEdit($item_manu_mobile->price, '')['price']}} đ @else Liên hệ @endif</span>
                                    <div class="stars">
                                        <span class="vote"><span class="star" data-vote="{{$item_manu_mobile->rate_avg}}"></span></span>
                                    </div>
                                    <div class="info">
                                        @foreach(explode('|', $item_manu_mobile->parameter) as $item_info)
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
                        <div id="remove-row" class="d-block d-lg-none w-100">
                            <button id="btn-more" data-page="{{$data->currentPage() + 1}}" data-key="{{request()->key}}" @if(!empty(request()->sort_by)) data-sort="{{request()->sort_by}}" @endif class="more-prd-search">Xem thêm sản phẩm <i class="fa fa-angle-down"></i></button>
                        </div>
                    @endif

                </div>
            @else
                <div class="w-100" >
                    <div class="name text-center w-100">
                        <img class="rounded mx-auto d-block mt-5 lazyload" data-src="{{asset('images/noti-search.png')}}" alt="">
                        <h5 class="name text-center w-100"><span class="font-weight-normal">Rất tiếc chúng tôi không tìm thấy kết quả của</span> "{{request()->key}}"</h5>
                        <div class="fs-senullbob">
                            <h4>Để tìm được kết quả chính xác hơn, xin vui lòng:</h4>
                            <ul>
                                <li>Kiểm tra lỗi chính tả của từ khóa đã nhập</li>
                                <li>Thử lại bằng từ khóa khác</li>
                                <li>Thử lại bằng những từ khóa tổng quát hơn</li>
                                <li>Thử lại bằng những từ khóa ngắn gọn hơn</li>
                            </ul>
                        </div>
                    </div>
                </div>
            @endif
{{--            <home_title home_title="sản phẩm bạn vừa xem" class="mt-5"></home_title>--}}
{{--            <div class="js-carousel box-suggest mb-4" data-items="5" data-dots="false" data-loop="true">--}}
{{--                <div class="item">--}}
{{--                    <product_item_2></product_item_2>--}}
{{--                </div>--}}
{{--                <div class="item">--}}
{{--                    <product_item_2></product_item_2>--}}
{{--                </div>--}}
{{--                <div class="item">--}}
{{--                    <product_item_2></product_item_2>--}}
{{--                </div>--}}
{{--                <div class="item">--}}
{{--                    <product_item_2></product_item_2>--}}
{{--                </div>--}}
{{--                <div class="item">--}}
{{--                    <product_item_2></product_item_2>--}}
{{--                </div>--}}
{{--                <div class="item">--}}
{{--                    <product_item_2></product_item_2>--}}
{{--                </div>--}}
{{--            </div>--}}
        </div>
    </main>
@endsection
@push('js_bot_all')
    <script>
        var sort_by = '{!! json_encode($orderClauseText) !!}';
        var filter_cate = '{!! @json_encode($filter_cate_child) !!}';
        var choosed_filters = '{!! @json_encode($choosed_filters) !!}';
    </script>
    {!! \Lib::addMedia('js/features/product/category.js') !!}

    <script>
        $(document).ready(function(){
            $(document).on('click','#btn-more',function(){
                var page = $(this).data('page');
                var key = $(this).data('key');
                var sort = $(this).data('sort');
                $("#btn-more").html('<img data-src={{asset('html-viettech/images/loading.gif')}} class="lazyload">');
                shop.ajax_popup('loadMoreSearchAjax', 'POST', {page: page, key:key, sort_by:sort,  _token:"{{csrf_token()}}"}, function(json) {
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
                        if (value.is_tragop > 0) {
                            if (value.out_of_stock == 0){
                                html += '<div class="product_item_laptop_mobile">' +
                                    '       <span class="sale-mobile">' +
                                    '            Trả góp 0%' +
                                    '        </span>' +
                                    '        <div class="demo">' +
                                    '            <a href="" class="wrap-img">' +
                                    '                <img class="mobile-demo" src="' + img + '" alt="">' +
                                    '            </a>' +
                                    '        </div>' +
                                    '        <div class="wrap-right">' +
                                    '             <a href="" class="name">' + value.title + '</a>' +
                                    '            <span class="price text-danger">' + price + ' đ</span>' +
                                    '            <div class="stars">' +
                                    '                <span class="vote"><span class="star" data-vote="' + value.rate_avg + '"></span></span>' +
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
                                    '                <img class="mobile-demo" src="'+img+'" alt="">' +
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
                                    '                <img class="mobile-demo" src="' + img + '" alt="">' +
                                    '            </a>' +
                                    '        </div>' +
                                    '        <div class="wrap-right">' +
                                    '             <a href="" class="name">' + value.title + '</a>' +
                                    '            <span class="price text-danger">' + price + ' đ</span>' +
                                    '            <div class="stars">' +
                                    '                <span class="vote"><span class="star" data-vote="' + value.rate_avg + '"></span></span>' +
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
                                    '                <img class="mobile-demo " src="' + img + '" alt="">' +
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
                        $('#btn-more').html('<button id="btn-more" data-page="'+page+'" data-key="{{request()->key}}" @if(!empty(request()->sort_by)) data-sort="{{request()->sort_by}}" @endif class="more-prd-search">Xem thêm sản phẩm <i class="fa fa-angle-down"></i></button>');
                    }else{
                        $('#remove-row').remove();
                    }

                });

            });
        });
    </script>
@endpush