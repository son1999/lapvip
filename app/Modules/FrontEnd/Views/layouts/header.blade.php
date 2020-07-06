<header>
    <div class="header-top">
        <div class="container header-top-main">
            <a href="javascript:;" class="nav-mobile d-inline-block d-lg-none js-show-menu-mobile">
                <i class="fa fa-bars" aria-hidden="true"></i>
            </a>
            <a href="/" class="logo">
                <img src="{{asset('html-viettech/images/logo.png')}}" alt />
            </a>
            <div class="icon_mobile">
                <a class="icon_mobile_tel d-lg-none" href="tel:{{$def['hotline']}}" title="">
                    <span>Gọi miễn phí</span>
                    <strong>{{$def['hotline']}}</strong>
                </a>
                <a href="{{route('cart.checkout.cart')}}" class="icon_mobile-item">
                    <i class="fa fa-shopping-cart" aria-hidden="true"></i>
                    <span class="counter-cart"></span>
                </a>
            </div>
            <div class="header-search">
                <div  class="form-search typeahead " role="search">
                    <input type="search" id="search" class="s_input w-100" name="q" placeholder="Bạn muốn mua gì ?" autocomplete="off" />
                    <a href="javascript:;" class="search-btn" onclick="shop.search()">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </a>
                </div>
            </div>
            <div class="header-top-link">
                @foreach($menu as $item)
                    @if($item['title'] == 'HEAD')
                        @foreach($item['sub'] as $item_sub)
                            <a href="{{route('news.list', ['slug_title' =>str_slug($item_sub['title']), 'cat' => 0])}}" class="top-link-item @if($item_sub['title'] == 'TIN TỨC') tin-tuc @endif">{{$item_sub['title']}}</a>
                        @endforeach
                    @endif
                @endforeach
                <a href="{{route('cart.checkout.cart')}}" class="top-link-item gio-hang">
                    Giỏ hàng
                    <span class="counter-cart"></span>
                </a>
            </div>
        </div>
    </div>
{{--    <div class="menu" id="app_prd_category_menu">--}}
    <div class="menu">
        <nav class="container">
            <ul class="main-menu">
                @foreach($menu as $item)
                    @if($item['title'] == 'HEAD')
                        <li class="menu-item d-none">
                            @foreach($item['sub'] as $item_sub)
                                <a href="{{route('news.list', ['slug_title' =>str_slug($item_sub['title']), 'cat' => 0])}}" class="top-link-item @if($item_sub['title'] == 'TIN TỨC') tin-tuc @endif">{{$item_sub['title']}}</a>
                            @endforeach
                        </li>
                    @elseif($item['title'] != 'HEAD')
                        <li class="menu-item @if(!empty($item['sub'])) item-has-child @endif">
                            @if($item['title'] == 'KHUYỄN MÃI')
                                <a href="{{route('news.list', ['slug_title' => str_slug($item['title']), 'cat' => $item['cat_id']])}}">
                                    <img class="lazyload" data-src="{{asset('upload/original/'.$item['img_icon'])}}" alt />
                                    <span>{{$item['title']}}</span>
                                </a>
                            @else
                                <a href="@if(!empty($item['cat_id'])) {{ route('product.list', ['alias'=> str_slug($item['title']), 'parent_id' => $item['cat_id']])}} @else {{$item['link']}} @endif " {!! $item  ['newtab'] == 1 ? ' target="_blank"' : '' !!}}>
                                    <img class="lazyload" data-src="{{asset('upload/original/'.$item['img_icon'])}}" />
                                    <span>{{$item['title']}}</span>
                                </a>
                            @endif
                            @if(!empty($item['sub']))
                                <div class="mega-menu-drop apple-drop">
                                    <div class="mega-menu-main">
                                        <div class="mega-col mega-col-1">
                                            @foreach($item['sub'] as $item_sub1)
                                                <h6 class="title">{{$item_sub1['title']}}</h6>
                                                @if(!empty($item_sub1['sub']))
                                                    <ul>
                                                        @foreach($item_sub1['sub'] as $item_sub2)
                                                            <li> <a href="{{route('product.filter',['alias_filter' => \Illuminate\Support\Str::slug($item['title'])])}}?cate={{$item['cat_id']}}&child={{$item_sub2['cat_id']}}&filter_child={{ \Illuminate\Support\Str::slug($item_sub2['title'].' '.$item_sub2['cat_id'])}}" {!! $item_sub2['newtab'] == 1 ? ' target="_blank"' : '' !!}>{{$item_sub2['title']}}</a> </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            @endforeach
                                        </div>
                                        @foreach($fill_cate_menu as $key => $item_fill)
                                            @if($item_fill->cate_id == $item['cat_id'])
                                                <div class="mega-col mega-col-2">
                                                    <h6 class="title">{{$item_fill->title}}</h6>
                                                    <ul>
                                                        @foreach($item_fill->filters as $key => $fil)
                                                            <li>
                                                                <a href="{{route('product.filter',['alias_filter' => str_slug($fil->title)])}}?cate={{$item_fill->cate_id}}&filter_ids={{$fil->id}}">{{$fil->title}}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                        @endforeach
                                        @if(!empty($item['is_selling']))
                                            <div class="mega-col mega-col-3">
                                                <h6 class="title">bán chạy nhất</h6>
                                                <ul>
                                                    @foreach($item['is_selling'] as $key => $item_pro_sel)
                                                        @if($key < 3)
                                                            <li class="menu-product-item">
                                                                <a href="{{route('product.detail', ['alias' => $item_pro_sel['alias']])}}" class="p-img">
                                                                    <img class="lazyload" data-src="{{\ImageURL::getImageUrl($item_pro_sel['image'], \App\Models\Product::KEY, 'small')}}" alt />
                                                                </a>
                                                                <div class="p-info">
                                                                    <a href="{{route('product.detail', ['alias' => $item_pro_sel['alias']])}}" class="p-name">{{$item_pro_sel['title']}}</a>
                                                                    <span class="p-price text-danger">@if($item_pro_sel->out_of_stock == 0){{\Lib::priceFormatEdit($item_pro_sel['price'])['price']}} đ @else Liên hệ @endif</span>
                                                                </div>
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                        @if(!empty($item['banner_link']))
                                            <div class="mega-col col-image">
                                                <a href="{{$item['banner_link']}}" class="banner_mega-menu">
                                                    <img class="lazyload" data-src="{{\ImageURL::getImageUrl($item['image'], \App\Models\Menu::KEY, 'small')}}" alt />
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </li>
                    @endif
                @endforeach
            </ul>
        </nav>
    </div>


    <div class="mobile-menu d-block d-lg-none">
        <div class="head">
            <a href="/" class="logo">
                <img src="{{asset('html-viettech/images/logo.png')}}" alt />
            </a>
            <div class="close-menu"><i class="fa fa-times"></i></div>
        </div>
        <div class="main-menu">
            @foreach($menu as $item)
                @if($item['title'] != 'HEAD')
                    <div class="menu-item @if(!empty($item['sub'])) item-has-child @endif">
                        @if(!empty($item['sub'])) <div class="wrap-cate-has-child"> @endif
                            @if($item['title'] == 'KHUYỄN MÃI')
                                <a  href="{{route('news.list', ['slug_title' => str_slug($item['title']), 'cat' => $item['cat_id']])}}" class="cate-1">
                                    <img src="{{asset('upload/original/'.$item['img_icon'])}}" alt />
                                    <div class="wrap">
                                        <span>{{$item['title']}}</span>
                                    </div>
                                </a>
                            @else
                                <a href="@if(!empty($item['cat_id'])) {{ route('product.list', ['alias'=> str_slug($item['title']), 'parent_id' => $item['cat_id']])}} @else {{$item['link']}} @endif " {!! $item  ['newtab'] == 1 ? ' target="_blank"' : '' !!}} class="cate-1">
                                    <img src="{{asset('upload/original/'.$item['img_icon'])}}" />
                                    <div class="wrap">
                                        <span>{{$item['title']}}</span>
                                    </div>
                                </a>
                            @endif
                        @if(!empty($item['sub']))
                            <i class="fa fa-angle-down"></i>
                        </div>
                        <div class="mobile-menu-drop" style="display: none">
                            @foreach($item['sub'] as $item_sub1)
                                @if(!empty($item_sub1['sub']))
                                    @foreach($item_sub1['sub'] as $item_sub2)
                                        <div class="cate-2"><span><a href="{{route('product.filter',['alias_filter' => str_slug($item['title'])])}}?cate={{$item['cat_id']}}&child={{$item_sub2['cat_id']}}&filter_child={{ \Illuminate\Support\Str::slug($item_sub2['title'].' '.$item_sub2['cat_id'])}}" {!! $item_sub2['newtab'] == 1 ? ' target="_blank"' : '' !!}>{{$item_sub2['title']}}</a></span></div>
                                    @endforeach
                                @endif
                            @endforeach
                        </div>
                        @endif
                    </div>
                @elseif($item['title'] == 'HEAD')
                    @foreach($item['sub'] as $item_sub)
                        <div class="menu-item">
                            <a href="{{route('news.list', ['slug_title' =>str_slug($item_sub['title']), 'cat' => 0])}}" class="cate-1">
                                <img src="{{asset('html-viettech/images/ic_blog.png')}}" alt />
                                <div class="wrap">
                                    <span>{{$item_sub['title']}}</span>
                                </div>
                            </a>
                        </div>
                    @endforeach
                @endif
            @endforeach

        </div>
    </div>
</header>