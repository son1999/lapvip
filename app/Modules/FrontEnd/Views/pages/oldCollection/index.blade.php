@extends('FrontEnd::layouts.home', ['bodyClass' => 'has-cover'])

@section('title') {!! \Lib::siteTitle($site_title, $def['site_title']) !!} @stop

@section('content')
    <main>
        <div class="container">
            <div class="px-0">
                {!! \Lib::renderBreadcrumb(false, true,'FrontEnd::layouts.breadcrumb') !!}
            </div>
        </div>
        <div class="container banner_page_laptop mt-3">
            <div class="js-carousel" data-items="1" data-arrows="false">
                <a href="#" class="banner_item">
                    <img src="{{asset('html-viettech/images/banner_page_laptop.jpg')}}" alt="">
                </a>
            </div>
        </div>

        <div class="container mt-4 page-content-thu-cu">
            <div class="row m-0 bg-white py-4 px-2">
                <h3 class="text-center col-12 fs-24">{{$data['title']}}</h3>
                <div class="col-12 col-lg-6">
                    {!! $data['body'] !!}
                </div>
            </div>
        </div>

{{--        <div class="container mt-5 product-list-thu-cu">--}}
{{--            <div class="bg-white pb-5">--}}
{{--                @foreach($product as $key => $item_p)--}}
{{--                    <div class="home-title-cate">--}}
{{--                        <h3 class="name">{{$key}}</h3>--}}
{{--                        <a href="" class="more">--}}
{{--                            Xem tất cả--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                    <div class="row mb-5">--}}
{{--                        @foreach($item_p as $item_pro)--}}
{{--                            <div class="col-6 col-lg-3">--}}
{{--                                <div class="product-item-2">--}}
{{--                                    <a href="{{route('product.detail', ['alias' => $item_pro->alias])}}" class="wrap-img">--}}
{{--                                        <img src="{{\ImageURL::getImageUrl($item_pro->image, \App\Models\Product::KEY, 'original')}}" alt="">--}}
{{--                                    </a>--}}
{{--                                    <div class="body">--}}
{{--                                        <a href="{{route('product.detail', ['alias' => $item_pro->alias])}}" class="name"> {{$item_pro->title}}</a>--}}
{{--                                        @if($item_pro->priceStrike > 0)--}}
{{--                                            <span class="price">--}}
{{--                                                        <span class="new">{{\Lib::priceFormat($item_pro->priceStrike, '')}} </span>--}}
{{--                                                    </span>--}}
{{--                                        @else--}}
{{--                                            <span class="price">--}}
{{--                                                        <span class="new">{{\Lib::priceFormat($item_pro->price, '')}}</span>--}}
{{--                                                    </span>--}}
{{--                                        @endif--}}
{{--                                        <div class="stars">--}}
{{--                                            <span class="vote"><span class="star" data-vote="{{$item_pro->rate_avg != 0 ? $item_pro->rate_avg : 0}}"></span></span>--}}
{{--                                        </div>--}}
{{--                                        <div class="des">--}}
{{--                                            {!! \StringLib::plainText(mb_substr($item_pro->sapo, 0, 50)) !!}&nbsp;...--}}
{{--                                        </div>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @endforeach--}}
{{--                    </div>--}}
{{--                @endforeach--}}

{{--            </div>--}}
{{--        </div>--}}
    </main>
@endsection