
@extends('FrontEnd::layouts.home')
@section('title') {!! \Lib::siteTitle($site_title, $def['site_title'],false,true) !!} @stop

@section('content')
    <main id="app_prd_category">
        <div class="container">
            {!! \Lib::renderBreadcrumb(false, true,'FrontEnd::layouts.breadcrumb') !!}
            <div class="banner-accsessories">
                @if(!empty($slide))
                    @foreach($slide as $key => $i_slide)
                        @if($key < 2)
                            <a href="{{$i_slide->link}}">
                                <img data-src="{{$i_slide->getImageUrl('original')}}" class="lazyload" alt="">
                            </a>
                        @endif
                    @endforeach
                @endif
            </div>

            <div class="list-cat-accessories">
                <ul class="p-0 d-flex justify-content-between">
                    @foreach($category as $item_cat)
                        @if(!empty($item_cat['sub'] && $item_cat['id'] == request()->parent_id) || !empty($item_cat['sub'] && $item_cat['id'] == request()->cate))
                            @foreach($item_cat['sub'] as $subcat)
                                <li @if(request()->child == $subcat['id']) class="active" @endif >
{{--                                    <a href="{{ route('product.list', ['alias'=> str_slug($subcat['title']), 'parent_id' => $item_cat['id'], 'id' => $subcat['id'] ]) }}" class="cat_icon_pk" style="background-image: url('{{asset('upload/category/original/'.$subcat['icon'])}}');">--}}
                                    <a href="{{ route('product.list', ['alias'=> str_slug($subcat['title']), 'parent_id' => $item_cat['id'], 'child' => $subcat['id'] ]) }}" class="cat_icon_pk" style="background-image: url('{{asset('upload/category/original/'.$subcat['icon'])}}');">
                                        <span style="background-image: url('{{asset('upload/category/original/'.$subcat['icon_hover'])}}');"></span>
                                    </a>
                                    <a href="{{ route('product.list', ['alias'=> str_slug($subcat['title']), 'parent_id' => $item_cat['id'], 'child' => $subcat['id'] ]) }}" class="cat_name_pk">{{$subcat['title']}}</a>
                                </li>
                            @endforeach
                        @endif
                    @endforeach
                </ul>
            </div>

            <div class="container mt-3 mb-5">
                @foreach($category as $item_cat)
                    @if(request()->parent_id == $item_cat['id'] || request()->cate == $item_cat['id'])
                        @if(!empty($item_cat['sub']))
                            @foreach($item_cat['sub'] as $subcat)
                                @if(request()->child == $subcat['id'] && request()->child != 0)
                                    <div class="home-title-cate">
                                        <h3 class="name">{{$subcat['title']}}</h3>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="home-title-cate">
                                <h3 class="name">{{$item_cat['title']}}</h3>
                            </div>
                        @endif
                    @endif
                @endforeach


                <div class="row">
                    <div class="head-filter-page w-100">
                        <div class="filter-main">
                            <span>Lọc</span>
                            <ul class="list-cat">
                                <div class="dropdown box-filter-item" v-for="cate in filter.filter_cate">
                                    <button class="btn chose pl-0 dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        @{{ cate.title }}
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownSize">
                                        <a class="dropdown-item fs-12" href="#" v-for="filter in cate.filters" @click="pick_filters($event,filter.id)">@{{ filter.title }}</a>
                                    </div>
                                </div>
                            </ul>
                            <div class="filter-sort">
                                <button type="button"> Sắp xếp theo
                                    <span class="sortText" v-for="(item, index) in filter.sort_by" v-if="index == {{!empty(request()->sort_by) ? request()->sort_by : 0}}">@{{ item }}</span>
                                </button>
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
                </div>
                <div class="row">
                    @if(!empty($pro_mobile))
                    @foreach($pro_mobile as $item_manu)
                        <div class="product-item-2-seen col-6 col-md-3 col-lg-5-2 p-0">
                            <a href="{{route('product.detail.accessory', ['alias' => $item_manu->alias])}}" class="wrap-img">
                                <img data-src="{{asset('upload/products/original/'.$item_manu->image)}}" class="lazyload" alt="">
                            </a>
                            <div class="body">
                                <a href="{{route('product.detail.accessory', ['alias' => $item_manu->alias])}}" class="name">
                                    <span>
                                        {{$item_manu->title}}
                                    </span>
                                </a>
                                @if($item_manu->out_of_stock == 0)
                                    @if(!empty($item_manu->priceStrike != 0))
                                        <span class="price">
                                            <span class="new text-danger"> {{\Lib::priceFormatEdit($item_manu->price)['price']}} đ </span>
                                            <span class="old"> {{\Lib::priceFormatEdit($item_manu->priceStrike)['price']}} đ </span>
                                        </span>
                                    @else
                                        <span class="price">
                                            <span class="new text-danger"> {{\Lib::priceFormatEdit($item_manu->price)['price']}} đ </span>
                                        </span>
                                    @endif
                                @else
                                    <span class="price">
                                        <span class="new text-danger"> Liên hệ</span>
                                    </span>
                                @endif
                            </div>
                        </div>
{{--                        @if($item_manu->count > 2)--}}
{{--                            <div class="count-config">--}}
{{--                                <span class="fs-12 text-white">Có <i>{{$item_manu->count}}</i> {{!empty($item_manu->option) ? $item_manu->option : 'lựa chọn cấu hình'}}</span>--}}
{{--                            </div>--}}
{{--                        @endif--}}
{{--                        <div class="product-item-2 col-6 col-md-3 col-lg-5-2 p-0">--}}
{{--                            <a href="{{route('product.detail.accessory', ['alias' => $item_manu->alias])}}" class="wrap-img">--}}
{{--                                <img src="{{asset('upload/products/original/'.$item_manu->image)}}" alt="">--}}
{{--                            </a>--}}
{{--                            <div class="body">--}}
{{--                                <a href="{{route('product.detail.accessory', ['alias' => $item_manu->alias])}}" class="name"> {{$item_manu->title}} </a>--}}
{{--                                @if(!empty($item_manu->priceStrike != 0))--}}
{{--                                    <span class="price">--}}
{{--                                                <span class="new"> {{\Lib::priceFormat($item_manu->price)}} </span>--}}
{{--                                                <span class="old"> {{\Lib::priceFormat($item_manu->priceStrike)}} </span>--}}
{{--                                            </span>--}}
{{--                                @else--}}
{{--                                    <span class="price">--}}
{{--                                                <span class="new"> {{\Lib::priceFormat($item_manu->price)}} </span>--}}
{{--                                            </span>--}}
{{--                                @endif--}}
{{--                                <div class="stars">--}}
{{--                                    <span class="vote"><span class="star" data-vote="{{$item_manu->rate_avg != 0 ? $item_manu->rate_avg : 0}}"></span></span>--}}
{{--                                </div>--}}
{{--                                <div class="des">--}}
{{--                                    {!! \StringLib::plainText(mb_substr($item_manu->sapo, 0, 50)) !!}&nbsp;...--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                    @endforeach
                </div>
                    @if ($pro_mobile->total() > 15)
                        <div class="bg-white row py-5 justify-content-center mt-5">
                            <nav aria-label="Page navigation" class="main-wrap">
                                {{$pro_mobile->render('FrontEnd::layouts.pagin')}}
                            </nav>
                        </div>
                    @endif
                @endif
            </div>
            <div class="list-cat-accessories mb-5">
                <ul class="p-0 d-flex justify-content-between">
                    @foreach($category as $item_cat)
                        @if(!empty($item_cat['sub'] && $item_cat['id'] == request()->parent_id) || !empty($item_cat['sub'] && $item_cat['id'] == request()->cate))
                            @foreach($item_cat['sub'] as $subcat)
                                <li @if(request()->child == $subcat['id']) class="active" @endif >
                                    <a href="{{ route('product.list', ['alias'=> str_slug($subcat['title']), 'parent_id' => $item_cat['id'], 'child' => $subcat['id'] ]) }}" class="cat_icon_pk" style="background-image: url('{{asset('upload/category/original/'.$subcat['icon'])}}');">
                                        <span style="background-image: url('{{asset('upload/category/original/'.$subcat['icon_hover'])}}');"></span>
                                    </a>
                                    <a href="{{ route('product.list', ['alias'=> str_slug($subcat['title']), 'parent_id' => $item_cat['id'], 'child' => $subcat['id']  ]) }}" class="cat_name_pk">{{$subcat['title']}}</a>
                                </li>
                            @endforeach
                        @endif
                    @endforeach
                </ul>
            </div>
        </div>
    </main>
@endsection
@push('js_bot_all')
    <script>
        var sort_by = '{!! json_encode($orderClauseText) !!}';
        var filter_cate = '{!! json_encode(@$filter_cates) !!}';
        var choosed_filters = '{!! json_encode($choosed_filters) !!}';
    </script>
    {!! \Lib::addMedia('js/features/product/category.js') !!}
@endpush