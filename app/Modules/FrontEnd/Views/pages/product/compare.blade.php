@extends('FrontEnd::layouts.home')
@section('title') {!! \Lib::siteTitle($site_title, $def['site_title'],false,true) !!} @stop

@section('content')
    <main>
        <div class="container page-compare-product">
            <div class="px-0 d-none d-md-block">
{{--                {!! \Lib::renderBreadcrumb(false, true,'FrontEnd::layouts.breadcrumb') !!}--}}
            </div>
            <div class="banner_compare_products mt-3">
                <div class="js-carousel" data-items="1" data-arrows="false" data-dots="true">
                    @foreach($slide as $slide_compare)
                        <a href="{{$slide_compare->link}}" class="banner_item">
                            <img data-src="{{$slide_compare->getImageUrl('original')}}" class="lazyload" alt="">
                        </a>
                    @endforeach

                </div>
            </div>
            <div class="main_compare_products d-none d-md-block">
                <div class="block-1">
                    <div class="main-title">So sánh sản phẩm</div>
                    <div style="position: relative; max-width: 330px; margin: 0 auto;">
                        <form action="">
                            <input type="text" id="serch_product_compare" class="search form-control" placeholder="Nhập tên sản phẩm cần so sánh">
                            <a><i class="fa fa-search"></i></a>

                        </form>
                        <div class="popsearch">
                            <div class="div-product-search " >
                            </div>
                        </div>
                    </div>

                </div>
                <div class="block-2">
                    <div class="col-8 offset-2 prds">
                        <div class="row">
                            <div class="product-item-compare col">
                                <div class="prd-title">
                                    <span>{{$pro_p->title}}</span>
                                </div>
                                <div class="wrap">
                                    <div class="wrap-img">
                                        <img data-src="{{\ImageURL::getImageUrl($pro_p->image, 'products', 'medium')}}" class="lazyload" alt="">
                                    </div>
                                    <div class="body">
                                        <div class="des">
                                            @foreach(explode('|', $pro_p->parameter) as $key => $parameter_product)
                                                @if($key < 5)
                                                    @php($str = substr( $parameter_product, 0, strpos( $parameter_product, ":" )))
                                                    <div class="prameter-filter">
                                                        <b>{{$str}}</b>{{str_replace($str,'',$parameter_product)}}
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <span class="price">
                                            @if($pro_p->out_of_stock == 0)
                                                <span class="new text-danger">
                                                    {{\Lib::priceFormatEdit($pro_p->price, '')['price']}} đ
                                                </span>
                                                @if($pro_p->priceStrike > 0)
                                                    <span class="old">
                                                        {{\Lib::priceFormatEdit($pro_p->priceStrike, '')['price']}} đ
                                                    </span>
                                                @endif
                                            @else
                                                <span class="new text-danger">
                                                    Liên hệ
                                                </span>
                                            @endif
                                        </span>
                                        <div class="stars">
                                            <span class="vote"><span class="star" data-vote="{{$pro_p->rate_avg}}"></span></span>
                                        </div>
                                        <a href="{{route('product.detail', ['alias' => $pro_p->alias])}}" class="more">
                                            Xem chi tiết
                                        </a>
                                    </div>
                                </div>

                            </div>
                            <div class="product-item-compare col">
                                <div class="prd-title">
                                    <span>{{$pro_c->title}}</span>
                                </div>
                                <div class="wrap">
                                    <div class="wrap-img">
                                        <img data-src="{{\ImageURL::getImageUrl($pro_c->image, 'products', 'medium')}}" class="lazyload" alt="">
                                    </div>
                                    <div class="body">
                                        <div class="des">
                                            @foreach(explode('|', $pro_c->parameter) as $key => $parameter_product)
                                                @if($key < 5)
                                                    @php($str = substr( $parameter_product, 0, strpos( $parameter_product, ":" )))
                                                    <div class="prameter-filter">
                                                        <b>{{$str}}</b>{{str_replace($str,'',$parameter_product)}}
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        <span class="price">
                                            @if($pro_c->out_of_stock == 0)
                                                <span class="new text-danger">
                                                    {{\Lib::priceFormatEdit($pro_c->price, '')['price']}} đ
                                                </span>
                                                @if($pro_c->priceStrike > 0)
                                                    <span class="old">
                                                        {{\Lib::priceFormatEdit($pro_c->priceStrike, '')['price']}} đ
                                                    </span>
                                                @endif
                                            @else
                                                <span class="new text-danger">
                                                    Liên hệ
                                                </span>
                                            @endif
                                        </span>
                                        <div class="stars">
                                            <span class="vote"><span class="star" data-vote="{{$pro_c->rate_avg}}"></span></span>
                                        </div>
                                        <a href="{{route('product.detail', ['alias' => $pro_c->alias])}}" class="more">
                                            Xem chi tiết
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                <div class="block-3">
                    <div class="block-3-child" style="padding: 0 15px;">
                        <div class="child-title">thông số cơ bản</div>
                        <table class="w-100">
                            @foreach($config_p as $item_p)
                                @foreach($item_p['props'] as $props_item_p)
                                    <tr style="border-bottom: 1px dashed #d4d4d4">
                                        <td class="text-dank font-weight-bold">{{$props_item_p['title']}}</td>
                                        <td>@if($props_item_p['value'] != ''){{$props_item_p['value']}}@else Trống @endif</td>
                                        @foreach($config_c as $item_c)
                                            @foreach($item_c['props'] as $props_item_c)
                                                @if($item_p['title'] == $item_c['title'] && $props_item_p['title'] == $props_item_c['title'])
                                                    <td>@if($props_item_c['value'] != ''){{$props_item_c['value']}}@else Trống @endif</td>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </tr>
                                @endforeach
                            @endforeach

                        </table>
                    </div>
                </div>


                <div class="block-4">
                    <div class="child-title">Thiết kế sản phẩm</div>
                    <div class="col-8 offset-2 demo">
                        <div class="row">
                            <div class="product-demo prd-1 col-6">
                                <div class="slider-demo">
                                    <div class="owl-carousel owl-theme big-img big demo-sync1">
                                        @foreach($pro_p->images as $key => $item_image_p )
                                            @if($key < 5)
                                                <div class="item">
                                                    <div class="wrap-img">
                                                        <img data-src="{{\ImageURL::getImageUrl($item_image_p->image, 'products', 'mediumx2')}}" class="lazyload" alt="">
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="owl-carousel owl-theme big-img-title small demo-sync2">
                                        @foreach($pro_p->images as $key => $item_image_p )
                                            @if($key < 5)
                                                <div class="item">
                                                    <div class="wrap-img">
                                                        <img data-src="{{\ImageURL::getImageUrl($item_image_p->image, 'products', 'tiny')}}" class="lazyload" alt="">
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach

                                    </div>
                                </div>

                                <div class="action">
                                    <a href="{{route('product.detail', ['alias' => $pro_p->alias])}}" class="buy1"><span>Mua ngay</span></a>
                                    <a href="{{route('installment.scenarios', ['alias' => str_slug($pro_p->title),'_token' => csrf_token(), 'index' => 1, 'id' => $pro_p->id, 'quan' => 1])}}" class="buy2"><span>trả góp</span></a>
                                </div>
                            </div>
                            <div class="product-demo prd-2 col-6">
                                <div class="slider-demo">
                                    <div class="owl-carousel owl-theme big-img big demo-sync1">
                                        @foreach($pro_c->images as $key => $item_image_c )
                                            @if($key < 5)
                                                <div class="item">
                                                    <div class="wrap-img">
                                                        <img data-src="{{\ImageURL::getImageUrl($item_image_c->image, 'products', 'mediumx2')}}" class="lazyload" alt="">
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                    <div class="owl-carousel owl-theme big-img-title small demo-sync2">
                                        @foreach($pro_c->images as $key => $item_image_c )
                                            @if($key < 5)
                                                <div class="item">
                                                    <div class="wrap-img">
                                                        <img data-src="{{\ImageURL::getImageUrl($item_image_c->image, 'products', 'tiny')}}" class="lazyload" alt="">
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                <div class="action">
                                    <a href="{{route('product.detail', ['alias' => $pro_c->alias])}}" class="buy1"><span>Mua ngay</span></a>
                                    <a href="{{route('installment.scenarios', ['alias' => str_slug($pro_c->title),'_token' => csrf_token(), 'index' => 1, 'id' => $pro_c->id, 'quan' => 1])}}" class="buy2"><span>trả góp</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main_compare_products_mobile d-md-none">
                <div class="main-title">So sánh <b>{{$pro_p->title}}</b> và <b>{{$pro_c->title}}</b></div>
                <div class="block-1 row">
                    <div class="item col">
                        <a href="" class="wrap-img">
                            <img data-src="{{\ImageURL::getImageUrl($pro_p->image, 'products', 'medium')}}" class="lazyload" alt="">
                        </a>
                        <div class="stars">
                            <span class="vote"><span class="star" data-vote="{{$pro_p->rate_avg}}"></span></span>
                        </div>
                        <a href="{{route('product.detail', ['alias' => $pro_p->alias])}}" class="name">{{$pro_p->title}}</a>
                        <span class="price text-danger">
                            @if($pro_p->out_of_stock == 0)
                                {{\Lib::priceFormatEdit($pro_p->price, '')['price']}} đ
                            @else
                                Liên hệ
                            @endif
                        </span>
                    </div>
                    <div class="item col">
                        <a href="" class="wrap-img">
                            <img data-src="{{\ImageURL::getImageUrl($pro_c->image, 'products', 'medium')}}" class="lazyload" alt="">
                        </a>
                        <div class="stars">
                            <span class="vote"><span class="star" data-vote="{{$pro_c->rate_avg}}"></span></span>
                        </div>
                        <a href="{{route('product.detail', ['alias' => $pro_c->alias])}}" class="name">{{$pro_c->title}}</a>
                        <span class="price text-danger">
                            @if($pro_c->out_of_stock == 0)
                                {{\Lib::priceFormatEdit($pro_c->price, '')['price']}} đ
                            @else
                                Liên hệ
                            @endif
                        </span>
                    </div>
                </div>
                @if(!empty($sale_parent) && !empty($sale_child))
                    <div class="title-block">so sánh khuyến mãi</div>
                    <div class="block-2 row">
                        <div class="sale col">
                            @if($pro_p->is_tragop > 0)<p>Trả góp 0%</p>@endif
                            @foreach($sale_parent as $item_sale_p)
                                @foreach($item_sale_p['props'] as $sale_p)
                                        <p>{{$sale_p['value']}}</p>
                                @endforeach
                            @endforeach

                        </div>
                        <div class="sale col">
                            @if($pro_c->is_tragop > 0)<p>Trả góp 0%</p>@endif
                            @foreach($sale_child as $item_sale_c)
                                @foreach($item_sale_c['props'] as $sale_c)
                                    <p>{{$sale_c['value']}}</p>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                @endif
                <div class="title-block">so sánh cấu hình cơ bản</div>
                <div class="block-3 row">
                    <div class="config col">
                        <div class="name">{{$pro_p->title}}</div>
                        @foreach($config_p as $item_config_p)
                            @foreach($item_config_p['props'] as $props_p)
                                <div class="box">
                                    <span>{{$props_p['title']}}</span>
                                    <span>{{$props_p['value']}}</span>
                                </div>
                            @endforeach
                        @endforeach

                        <div class="action">
                            <a href="{{route('product.detail', ['alias' => $pro_p->alias])}}" class="buy1"><span>Mua ngay</span></a>
                            <a href="{{route('installment.scenarios', ['alias' => str_slug($pro_p->title),'_token' =>csrf_token(), 'index' => 1, 'id' => $pro_p->id, 'quan' => 1])}}" class="buy2"><span>trả góp</span></a>
                        </div>
                    </div>
                    <div class="config col">
                        <div class="name">{{$pro_c->title}}</div>
                        @foreach($config_c as $item_config_c)
                            @foreach($item_config_c['props'] as $props_c)
                                <div class="box">
                                    <span>{{$props_c['title']}}</span>
                                    <span>{{$props_c['value']}}</span>
                                </div>
                            @endforeach
                        @endforeach

                        <div class="action">
                            <a href="{{route('product.detail', ['alias' => $pro_c->alias])}}" class="buy1"><span>Mua ngay</span></a>
                            <a href="{{route('installment.scenarios', ['alias' => str_slug($pro_c->title),'_token' =>csrf_token(), 'index' => 1, 'id' => $pro_c->id, 'quan' => 1])}}" class="buy2"><span>trả góp</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection
@push('js_bot_all')
    <script>
        $('#serch_product_compare').keyup(function(){
            var data = $(this).val();
            var dataParent = '{{@$pro_p->alias}}';
            if(data != ''){
                $('.popsearch').addClass('active');
                $('.div-product-search').show();
            }else{
                $('.popsearch').removeClass('active');
                $('.div-product-search').hide();
            }
            $('.div-product-search').html("");
            $.ajax({
                type: 'POST',
                url: ENV.BASE_URL+"ajax/searchProductCompare",
                data: {_token:ENV.token,AliasP:dataParent, data:data},
                dataType: 'json',
                async:true
            }).done(function(json) {

                $('.div-product-search').html(json.data);
            })
        })
    </script>
@endpush
