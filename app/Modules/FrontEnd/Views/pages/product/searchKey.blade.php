@extends('FrontEnd::layouts.home')
@section('title') {!! \Lib::siteTitle($site_title, $def['site_title'],false,true) !!} @stop
@section('content')
    <main>
        <div class="breadcrumb-block">
            {!! \Lib::renderBreadcrumb(false, true,'FrontEnd::layouts.breadcrumb') !!}
        </div>

        <div class="container">
            <div class="product-list-title d-flex justify-content-between d-lg-block">
                <h2 class="cat-title d-inline-block d-lg-block">{{@$site_title}}</h2>
                <a href="javascript:;" class="mobile-show-cat d-inline-block d-lg-none"><i class="fa fa-caret-down" aria-hidden="true"></i></a>
            </div>
            <div class="row" id="app_prd_category">
                @include('FrontEnd::pages.product.components.sidebar')
                <div class="col-12 col-lg-10">
                    <div class="product-filter">
                        <div class="box-filter d-none d-lg-flex flex-wrap">
                            <div class="dropdown box-filter-item ml-auto">
                                <button class="btn dropdown-toggle" type="button" id="dropdownColor" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    Lọc sản phẩm
                                </button>
                                <div class="dropdown-menu" aria-labelledby="dropdownColor">
                                    <a class="dropdown-item" href="#" v-for="(item, index) in filter.sort_by" @click="pick_sort_by($event,index)">@{{ item }}</a>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        @if($list_products->total() > 0)
                            @foreach($list_products as $item)
                                <div class="col-12 col-sm-6 col-lg-4 mb-5">
                                    <div class="p-item item-2">
                                        <a href="{{route('product.detail',['safe_title' => \Illuminate\Support\Str::slug($item->title_short? $item->title_short : $item->title), 'id' => $item->id])}}" class="figure">
                                            <img data-src="{{\ImageURL::getImageUrl($item->image, \App\Models\Product::KEY, 'mediumx2')}}" class="lazyload" alt="">
                                        </a>
                                        <div class="info">
                                            <a href="{{route('product.detail',['safe_title' => \Illuminate\Support\Str::slug($item->title_short? $item->title_short : $item->title), 'id' => $item->id])}}" class="title">{{$item->title}}</a>
                                            <span class="price d-block mt-2">
                                                @if($item->out_of_stock == 0)
                                                    {{\Lib::priceFormatEdit($item->priceStrike)['price']}} đ
                                                    <span class="standard text-danger">
                                                        {{\Lib::priceFormatEdit($item->price)['price']}} đ
                                                    </span>
                                                @else
                                                    <span class="standard text-danger">
                                                        Liên hệ
                                                    </span>
                                                @endif
                                            </span>
                                        </div>
                                        @if($item->priceStrike)
                                            <div class="discount"><span>-{{100- round($item->price/$item->priceStrike*100)}}%</span></div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="col-12">
                                <p class="fs-18">Xin lỗi, từ khóa của bạn chưa rõ, xin hãy dùng từ khóa khác!</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@push('js_bot_all')
    <script>
        var sort_by = '{!! json_encode($orderClauseText) !!}';
    </script>
    {!! \Lib::addMedia('js/features/product/search.js') !!}
@endpush