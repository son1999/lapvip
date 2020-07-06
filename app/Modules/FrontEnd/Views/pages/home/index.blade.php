@extends('FrontEnd::layouts.home')

@section('title') {!! \Lib::siteTitle($site_title, $def['site_title']) !!} @stop

@section('content')
    <main>
        <div class="container home-page-content">
            {{--            banner--}}
            @include('FrontEnd::layouts.banner')
            {{--            endbanner--}}

            @if(!empty($product_tags))
                @foreach($product_tags as $item_tags)
                    @if(count($item_tags['product']) > 0)
                        <div class="home-title-cate">
                            <h3><a class="name"
                                   href="{{ route('product.list', ['alias'=> str_slug($item_tags['title']), 'parent_id' => $item_tags['pid'], 'child_id' => 0])}}">{{$item_tags['title']}}</a>
                            </h3>
                        </div>
                        @if(!empty($item_tags['id_sell']))
                            <div class="home_cate">
                                <div class="list-cates">
                                    @foreach($item_tags['proSell'] as $item_sell)
                                        <div class="cate"><a
                                                    href="{{route('product.filter',['alias_filter' => \Illuminate\Support\Str::slug($item_sell['title'])])}}?cate={{$item_sell['pid']}}&child={{$item_sell['id']}}&filter_child={{ !empty($item_sell['slug']) ? $item_sell['slug'] : '' }}">{{$item_sell['title']}}</a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                        <div class="row show-product-desktop mb-4 @if($item_tags['layouts_mobile'] == 2) mt-3 @elseif($item_tags['layouts_mobile'] == 1) row-1-item @endif">
                            @foreach($item_tags['product'] as $item_pros)
                                {{--                                @foreach($item_p as $item_pros)--}}
                                {{--                                    @php(dd($item_pros))--}}
                                {{--                                    @if($item_pros['special_box_home'] == $item_tags['id'] )--}}
                                <div class="col-md-4 @if($item_tags['layouts_mobile'] == 2) item-mobile-2 @endif">
                                    <div class="product-item-type-1 @if($item_tags['layouts_mobile'] == 1) product_item_1_2 @endif">
                                        <a href="{{route('product.detail', ['alias' => $item_pros['alias']])}}"
                                           class="name">{{$item_pros['title']}}</a>
                                        <span class="price text-danger"><b> @if($item_pros['out_of_stock'] == 0){{\Lib::priceFormatEdit( $item_pros['price'], '')['price']}}
                                                đ @else Liên hệ @endif</b></span>
                                        @if($item_pros['is_tragop'] > 0)
                                            <span class="sale-mobile">
                                                        Trả góp 0%
                                                    </span>
                                        @endif
                                        <div class="info">
                                            <div class="cont d-block d-md-none">
                                                <div class="parameter">
                                                    @foreach(explode('|', $item_pros['parameter']) as $key => $item_param)
                                                        @if(count(explode('|', $item_pros['parameter'])) > 1 )
                                                            @if($key < 6)
                                                                <div><span>{{$item_param}}</span></div>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </div>
                                                @if($item_pros['is_sale'] > 0)
                                                    <span class="saling">khuyến mại</span>
                                                @endif
                                            </div>
                                            <div class="demo d-block d-md-none">
                                                <a href="{{route('product.detail', ['alias' => $item_pros['alias']])}}"
                                                   class="wrap-img">
                                                    <img class="mobile-demo"
                                                         src="{{\ImageURL::getImageUrl($item_pros['image'], \App\Models\Product::KEY, 'medium')}}"
                                                         alt="">
                                                </a>
                                            </div>
                                            <div>
                                                <a href="{{route('product.detail', ['alias' => $item_pros['alias']])}}">
                                                    <img class="desktop-demo d-none d-md-block lazyload"
                                                         data-src="{{\ImageURL::getImageUrl($item_pros['image_home'], \App\Models\Product::KEY, 'mediumx2')}}"
                                                         alt="">
                                                </a>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                {{--                                    @endif--}}
                                {{--                                @endforeach--}}
                            @endforeach
                        </div>
                        @if($item_tags['is_show_slide_home'] > 0)
                            <div class="home-title-cate">
                                <h3 class="name">Có thể bạn sẽ thích</h3>
                                <a href="{{ route('product.list', ['alias'=> str_slug($item_tags['title']), 'parent_id' => $item_tags['cid'] , 'child_id' =>  0])}}"
                                   class="more"> Xem tất cả </a>
                            </div>
                            <div class="js-carousel box-suggest mb-4" data-items-xxs="2" data-items-sm="5"
                                 data-dots="false" data-loop="true">
                                @foreach($item_tags['slide'] as  $pro_slide)
                                    {{--                                    @foreach($pro_slide as $p_s)--}}
                                    {{--                                        @if(isset($p_s['cat_id']) && $p_s['cat_id'] == $item_tags['pid'])--}}
                                    <div class="item">
                                        <div class="product-item-2 product_item_2_3">
                                            <a href="{{route('product.detail', ['alias' => $pro_slide['alias']])}}"
                                               class="wrap-img">
                                                <img src="{{\ImageURL::getImageUrl($pro_slide['image'], \App\Models\Product::KEY, 'mediumx2')}}"
                                                     alt="">
                                            </a>
                                            <div class="body">
                                                <a href="{{route('product.detail', ['alias' => $pro_slide['alias']])}}"
                                                   class="name">{{$pro_slide['title']}}</a>
                                                @if($pro_slide['out_of_stock'] == 0)
                                                    @if($pro_slide['priceStrike'] > 0)
                                                        <span class="price">
                                                            <span class="new text-danger">{{\Lib::priceFormatEdit($pro_slide['price'])['price']}} đ </span>
                                                            <span class="old">{{\Lib::priceFormatEdit($pro_slide['priceStrike'], '')['price']}} đ</span>
                                                        </span>
                                                    @else
                                                        <span class="price">
                                                            <span class="new text-danger">{{\Lib::priceFormatEdit($pro_slide['price'], '')['price']}} đ </span>
                                                            <span class="old"></span>
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="price">
                                                        <span class="new text-danger">Liên hệ</span>
                                                        <span class="old"></span>
                                                    </span>
                                                @endif
                                                <div class="stars">
                                                    <span class="vote"><span class="star"
                                                                             data-vote="{{$pro_slide['rate_avg'] != 0 ? $pro_slide['rate_avg'] : 0}}"></span></span>
                                                </div>
{{--                                                @if($pro_slide['count'] > 2)--}}
{{--                                                    <div class="count-config">--}}
{{--                                                        <span class="fs-12 text-white">Có <i>{{$pro_slide['count']}}</i> {{!empty($pro_slide['option']) ? $pro_slide['option'] : 'lựa chọn cấu hình'}}</span>--}}
{{--                                                    </div>--}}
{{--                                                @endif--}}
                                            </div>
                                        </div>
                                    </div>
                                    {{--                                        @endif--}}
                                    {{--                                    @endforeach--}}
                                @endforeach
                            </div>
                        @endif
                    @endif
                @endforeach
            @else
                <div class="w-100">
                    <h5 class="name text-center w-100">Updating.....!!!</h5>
                </div>
            @endif
            {{--phụ kiện--}}
            <div class="row mt-5">
                <div class="col-12">
                    <div class="home-title-cate" home_title="PHỤ KIÊN HOT - GIÁ TỐT">
                        <h3 class="name">PHỤ KIÊN HOT - GIÁ TỐT</h3>
                        <a href="" class="more"> Xem tất cả </a>
                    </div>
                </div>
                <div class="col-12">
                    @foreach($category as $cate)
                        @if($cate['title'] == 'Phụ kiện')
                            <div class="home_cate_option">
                                <div class="list-cates">
                                    @php($key_data = 1)
                                    @foreach($cate['sub'] as $key => $cate_sub)
                                        @if($key_data < 6)
                                            <div class="cate @if($key === array_key_first($cate['sub'])) active @endif">
                                                <a @if($key === array_key_first($cate['sub']))@else href="{{ route('product.list', ['alias'=> str_slug($cate['title']), 'parent_id' => $cate['id'], 'child_id' =>  $cate_sub['id']])}}" @endif>{{$cate_sub['title']}}</a>
                                            </div>
                                            @php($key_data ++)
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="row m-0 box-suggest-2">
                                @if(!empty($product_by_cate))
                                    @foreach($product_by_cate as $i_pro_cate)
                                        <div class="col p-0">
                                            <div class="product-item-2 product_item_2_2">
                                                <a href="{{route('product.detail.accessory', ['alias' => $i_pro_cate->alias])}}"
                                                   class="wrap-img">
                                                    <img data-src="{{asset('upload/products/thumb_250x0/'.$i_pro_cate->image)}}"
                                                         class="lazyload" alt="">
                                                </a>
                                                <div class="body">
                                                    <a href="{{route('product.detail.accessory', ['alias' => $i_pro_cate->alias])}}"
                                                       class="name"> {{$i_pro_cate->title}} </a>
                                                    @if($i_pro_cate->out_of_stock == 0)
                                                        @if(!empty($i_pro_cate->priceStrike != 0))
                                                            <span class="price">
                                                                    <span class="new text-danger"> {{\Lib::priceFormatEdit($i_pro_cate->price)['price']}} đ</span>
                                                                    <span class="old"> {{\Lib::priceFormatEdit($i_pro_cate->priceStrike)['price']}} đ </span>
                                                                </span>
                                                        @else
                                                            <span class="price">
                                                                    <span class="new text-danger"> {{\Lib::priceFormatEdit($i_pro_cate->price)['price']}} đ</span>
                                                                </span>
                                                        @endif
                                                    @else
                                                        <span class="price">
                                                                <span class="new text-danger"> Liên hệ</span>
                                                            </span>
                                                    @endif
                                                    <div class="stars">
                                                        <span class="vote"><span class="star"
                                                                                 data-vote="2.5"></span></span>
                                                    </div>
                                                    <div class="des">
                                                        {!! \StringLib::plainText(mb_substr($i_pro_cate->parameter, 0, 50)) !!}
                                                        &nbsp;...
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <div class="w-100">
                                        <h5 class="name text-center w-100">Updating.....!!!</h5>
                                    </div>
                                @endif
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            {{--end phụ kiện--}}
            <div class="bhx-main container">

            </div>
        </div>
    </main>
@endsection
