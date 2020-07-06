@extends('FrontEnd::layouts.default')

@section('title') {!! \Lib::siteTitle($site_title, $def['site_title']) !!} @stop

@section('content')
<main>
    <!-- begin breadcrumb -->
    <div class="breadcrumb-block">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#">Trang chủ</a></li>
                    <li class="breadcrumb-item"><a href="{{route('profile')}}">Thông tin tài khoản</a></li>
                </ul>
            </nav>
        </div>
    </div>
    <!-- end breadcrumb -->
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-3 user-tab-left d-none d-md-block" style="margin-top: 35px">
                @include('FrontEnd::pages.profile.tab-left')
            </div>
            <div class="col-12 col-lg-9">
            @if (count($prd_history) > 0)
            <section class="product-related">
                <div class="container">
                    <div class="title-features">
                        <h3 class="fs-16">Sản phẩm đã xem</h3>
                    </div>
                    <div class="row">
                        @foreach ($prd_history as $phis)
                        @foreach ($phis->viewed as $viewed)
                        <div class="col-12 col-sm-6 col-lg-3 mb-4 paga">
                            <div class="p-item item-1">
                                <a href="{{route('product.detail',['safe_title' => \Illuminate\Support\Str::slug($viewed->title), 'id' => $viewed->id])}}" class="figure">
                                    <img src="{{$viewed->getImageUrl('medium')}}" alt="">
                                </a>
                                <div class="info">
                                    <a href="{{route('product.detail',['safe_title' => \Illuminate\Support\Str::slug($viewed->title), 'id' => $viewed->id])}}" class="title">{{$viewed->title}}</a>
                                    <span class="price d-block mt-2"> <span class="standard">300000đ</span> </span>
                                </div>
                                @if($viewed->priceStrike)
                                    <div class="discount"><span>-{{100- round($viewed->price/$viewed->priceStrike*100)}}%</span></div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                        
                        @endforeach
                    </div>
                    @if ($prd_history->total() > 12)
                    <div class="row justify-content-center mt-3">
                        <div class="col-6">
                            {{$prd_history->render('FrontEnd::layouts.pagin')}}
                        </div>
                    </div>
                    @endif
                </div>
            </section>
            @else
            <div class="title-features">
                <h3 class="fs-16">Bạn chưa xem sản phẩm nào</h3>
            </div>
            @endif
            </div>
        </div>
    </div>
</main>
@stop

@section('js_bot')
<script src="https://cdnjs.cloudflare.com/ajax/libs/simplePagination.js/1.6/jquery.simplePagination.js"></script>
<script>
    var items = $(".product-related .paga");
        var numItems = items.length;
        var perPage = 12;
    
        items.slice(perPage).hide().addClass('d-none');
    
        $('#pagination-container').pagination({
            items: numItems,
            itemsOnPage: perPage,
            prevText: "&laquo;",
            nextText: "&raquo;",
            onPageClick: function (pageNumber) {
                var showFrom = perPage * (pageNumber - 1);
                var showTo = showFrom + perPage;
                items.hide().addClass('d-none').slice(showFrom, showTo).show().removeClass('d-none');
            }
        });
</script>
@endsection