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
                    @include('FrontEnd::pages.product.components.filterlist')
                    @include('FrontEnd::pages.product.components.product_list')
                </div>
            </div>
        </div>
    </main>
@endsection
@push('js_bot_all')
    <script>
        var sort_by = '{!! json_encode($orderClauseText) !!}';
        var filter_cate = '{!! json_encode($filter_cate) !!}';
        var choosed_filters = '{!! json_encode($choosed_filters) !!}';
    </script>
    {!! \Lib::addMedia('js/features/product/category.js') !!}
@endpush