@extends('FrontEnd::layouts.home')
@section('title') {!! \Lib::siteTitle($site_title, $def['site_title'],false,true) !!} @stop
@section('g_meta')
    <meta itemprop="name" content="{{$data->title}}"/>
    <meta itemprop="description" content="{{ strip_tags($data->sapo) }}"/>
    <meta itemprop="image" content="{{\ImageURL::getImageUrl($data->image, \App\Models\Product::KEY, 'original')}}">
@endsection
@section('meta_basic')
    <meta name="title" content="{{ $data->title_seo}}"/>
    <meta name="description" content="{{ $data->description_seo}}"/>
    <meta name="keywords" content="{{ $data->keywords}}"/>
@stop
@section('facebook_meta')
    <meta property="og:locale" content="vi_VN"/>
    <meta property="og:title" content="{{$data->title_seo}}"/>
    <meta property="og:description" content="{{$data->description_seo}}"/>
    <meta property="og:url" content="{{url()->current()}}"/>
    <meta property="og:site_name" content="{{env('APP_NAME')}}"/>
    <meta property="og:image"
          content="{{ !empty($data->image_seo) ? $data->getImageSeoUrl('original') : $data->getImageUrl('original')}}"/>
    <meta property="og:image:width" content="800"/>
    <meta property="og:image:height" content="800"/>
@stop
@section('content')
    <div class="container">
        {!! \Lib::renderBreadcrumb(false, true,'FrontEnd::layouts.breadcrumb') !!}
        {{--    detail sản phẩm--}}
        <div>
            <div class="product-wrapper" id="app_prd_detail">
                <section class="product-content">
                    <div class="product-content-heading">
                        <div class="product-name">
                            <span>{{$data->title}}</span>
                            <span id="product_name_sub">{{$data->title_sub}}</span>
                            <span class="small">(No.{{$data->id}})</span></div>
                        <div class="evaluate">
                            <p>@if(count($comment['comment']) > 0) {{count($comment['comment'])}} khách hàng đánh
                                giá @endif @if($comment['count'] > 0)| {{$comment['count']}} câu hỏi được trả
                                lời @endif</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-5">
                            <div class="customize-product-slider">
                                <div class="collection js-carousel" data-items="1" data-dots="true" data-arrows="false"
                                     data-autoplay="true" dat>
                                    <div class="product-slide">
                                        <div class="product-image">
                                            <a data-fancybox="img-slide" class="item"
                                               href="{{\ImageURL::getImageUrl($data->image, 'products', 'original')}}">
                                                <img data-src="{{\ImageURL::getImageUrl($data->image, 'products', 'mediumx2')}}"
                                                     class="lazyload" alt/>
                                            </a>
                                        </div>
                                    </div>
                                    @foreach($data->images as $key => $item)
                                        @if($key < 5)
                                            <div class="product-slide">
                                                <div class="product-image">
                                                    <a data-fancybox="img-slide" class="item"
                                                       href="{{\ImageURL::getImageUrl($item->image, 'products', 'original')}}">
                                                        <img data-src="{{\ImageURL::getImageUrl($item->image, 'products', 'largex2')}}"
                                                             class="lazyload" alt/>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                <div class="des text-center">
                                    <i class="fa fa-search" aria-hidden="true"></i> Click hoặc rê chuột vào ảnh để phóng
                                    to
                                </div>
                                <div class="product-thumbnail text-center">
                                    @if(!empty($data->lineBox))
                                        <div class="items">
                                            <div class="items-image">
                                                <a href="{{$data->getImageBox('original')}}" data-fancybox>
                                                    <img data-src="{{asset('html-viettech/images/openbox.png')}}"
                                                         class="lazyload"/>
                                                </a>
                                            </div>
                                            <div class="items-desc">Mở hộp</div>
                                        </div>
                                    @endif
                                    @if(!empty($data->link))
                                        <div class="items">
                                            <a data-fancybox href="{{$data->link}}">
                                                <div class="items-image item-youtube">
                                                    <img data-src="{{asset('html-viettech/images/icon-youtube.png')}}"
                                                         class="lazyload" alt/>
                                                </div>
                                            </a>
                                            <div class="items-desc">Video</div>
                                        </div>
                                    @endif
                                    @php($item_img =count($data->images) - 5)
                                    @if($item_img > 0)
                                        <div class="items">
                                            <div class="items-image">
                                                @foreach($data->images as $key => $img_item)
                                                    @if($key > 4)
                                                        @if($key == 5)
                                                            <a id="fancybox-thumbs" data-fancybox="images-preview"
                                                               href="{{\ImageURL::getImageUrl($img_item->image, 'products', 'original')}}">
                                                                <img data-src="{{asset('html-viettech/images/icon-show-images.png')}}"
                                                                     class="lazyload" alt/>
                                                            </a>
                                                        @else
                                                            <a data-fancybox="images-preview" rel="fbt4"
                                                               href="{{\ImageURL::getImageUrl($img_item->image, 'products', 'largex2')}}"
                                                               title="{{$data->title}}"></a>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            </div>

                                            <div class="items-desc">
                                                Xem thêm
                                                <br/>{{$item_img}} ảnh
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="promotion-image">
                                    @if(!empty($banner_detail))
                                        <img data-src="{{$banner_detail->getImageUrl('original')}}" class="lazyload"
                                             alt/>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-md-7 has-border">
                            <div class="product-infomation">
                                <div class="row">
                                    <div class="col-md-7" style="border-right: 1px solid #ececec;">
                                        <div class="specifications">
                                            <div class="price" >
                                                @if($data['out_of_stock'] == 0)
                                                    @if($data->detail_ap == 1)
                                                        @if(!empty($prd_prices['filter_cates']))
                                                            @if(!empty($prd_prices['filters']))
                                                                <span class="price-discount text-danger">@{{ formatPrice(prd_price) }} đ</span>
                                                            @else
                                                                <span class="price-discount text-danger">{{ \Lib::priceFormatEdit($data['price'])['price']}} đ </span>
                                                            @endif
                                                        @endif
                                                    @else
                                                        <span class="price-discount text-danger" id="price">{{ \Lib::priceFormatEdit($data['price'])['price']}} đ </span>
                                                    @endif
                                                @else
                                                    <span class="price-discount text-danger" id="out_of_stock">Liên hệ</span>
                                                @endif

                                                @if($data->is_tragop > 0)
                                                    <span class="installment">Trả góp 0%</span>
                                                @endif

                                                @if($data['out_of_stock'] == 0)
                                                    @if($data->detail_ap == 1)
                                                        @if(!empty($prd_prices['filter_cates']))
                                                            @if(!empty($prd_prices['filters']))
                                                                <span class="price-old" v-if="prd_price_strike > 0">@{{ formatPrice(prd_price_strike) }} đ</span>
                                                            @else
                                                                @if($data->priceStrike > 0)
                                                                    <span class="price-old">{{ \Lib::priceFormatEdit($data['priceStrike'])['price']}} đ </span>
                                                                @endif
                                                            @endif
                                                        @endif
                                                    @else
                                                        @if($data->priceStrike > 0)
                                                            <span class="price-old" id="priceStrike">{{ \Lib::priceFormatEdit($data['priceStrike'])['price']}} đ </span>
                                                        @endif
                                                    @endif
                                                @endif

                                                {{--                                        @if($data->priceStrike > 0)--}}
                                                {{--                                            <span class="price-old"><i>{{\Lib::priceFormat($data->priceStrike)}}</i></span>--}}
                                                {{--                                        @endif--}}
                                            </div>

                                            {{--                                <p class="special-text-price">“ Giá đặc biệt khi mua Online đến 31/08: 10.290.000đ ”</p>--}}
                                            {{--                                            @if(!empty($data->sale_detail))--}}
                                            {{--                                                @php($summary = explode("\n",$data->sale_detail))--}}

                                            {{--                                                <ul class="list-unstyled">--}}
                                            {{--                                                    @foreach($summary as $key => $item )--}}
                                            {{--                                                        @if(substr($item, 0, 1) == "#")--}}
                                            {{--                                                            <h4>{{str_replace("#","",$item)}}</h4>--}}
                                            {{--                                                        @else--}}
                                            {{--                                                        <li>--}}
                                            {{--                                                            {{$item}}--}}
                                            {{--                                                        </li>--}}
                                            {{--                                                        @endif--}}
                                            {{--                                                    @endforeach--}}
                                            {{--                                                </ul>--}}
                                            {{--                                            @endif--}}

                                            {{--for-AP--}}

    {{--1/6/2020--}}                        @if(!empty($prd_have_sale['properties']))
                                                @php($count_props = 0)
                                                @foreach(json_decode($prd_have_sale['properties'], true) as $item_pro)
                                                    @if(!empty($item_pro['props']))
                                                        <div class="desc-detail emtry-desc-detail" id="specifications_top">
                                                            @php($count_props += count($item_pro['props']))
                                                            @if($count_props < 6)
                                                                @foreach($item_pro['props'] as $item_props)
                                                                    <div class="w-100 item-desc">
                                                                        <b>{{ $item_props['title'] }} : </b>
                                                                        <span> {{ $item_props['value']   }}</span>
                                                                    </div>
                                                                @endforeach
                                                            @endif
                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endif
                                            @if($data->detail_ap == 1)
                                                <div v-for="(cate,index) in filter_cates">
                                                    <div class="desc-title" style="font-weight: bold">Chọn @{{ cate.title }}</div>
                                                    <div class="d-flex">
                                                        <div class="color" v-for="filter in filters">
                                                            <label v-if="filter.filter_cate_id == cate.id">
                                                                <input type="radio" @click="choose_this($event,filter)" v-bind:name="'property_' + cate.id"/>
                                                                <span class="filter-title mr-0" v-bind:for="'filter_' + filter.id" v-if="filter.title.indexOf('#') == -1">
                                                                    <img v-bind:src="'{{asset('upload/filters/original')}}/'+filter.image" style="height: 26px; width: 36px; object-fit: scale-down" />
                                                                </span>
                                                                <span class="checkmark" v-if="filter.title.indexOf('#') != -1" v-bind:style="{backgroundColor: filter.title}"></span>
                                                                <span class="fs-12 d-block text-center mt-2" v-if="filter.title.indexOf('#') == -1">@{{ filter.title }}</span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @elseif($data->detail_ap == 0)
                                                @if(!empty($data->product_relates))
                                                    <div class="main-load-more d-flex flex-wrap active">
                                                        <div class="configuration config-1 load col-6 p-1">
                                                            <a href="javascript:;" onclick="shop.getDataByID('{{$data->id}}')">
                                                                <div class="la-bel">
                                                                    <div class="ra-dio">
                                                                        <div class="checkmark ">
                                                                            {{$data->title_sub}} <br>
                                                                            <span class="text-danger font-weight-bold  w-100">
                                                                                    @if($data->out_of_stock == 0)
                                                                                    {{\Lib::priceFormatEdit($data->price, '')['price']}}đ
                                                                                @else
                                                                                    Liên hệ
                                                                                @endif
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </a>
                                                        </div>
                                                        @foreach($data->product_relates as $key => $item_relates)
                                                            @if(!empty($item_relates->product))
                                                                <div class="configuration config-1 load col-6 p-1">
                                                                    <a href="javascript:;" onclick="shop.getDataByID('{{$item_relates->product->id}}')">
                                                                        <div class="la-bel">
                                                                            <div class="ra-dio">
                                                                                <div class="checkmark" >
                                                                                    {{$item_relates->title}} <br>
                                                                                    <span class="text-danger font-weight-bold  w-100">
                                                                                        @if($item_relates->out_of_stock == 0)
                                                                                            {{\Lib::priceFormatEdit($item_relates->product->price, '')['price']}}đ
                                                                                        @else
                                                                                            Liên hệ
                                                                                        @endif
                                                                                    </span>
                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </a>
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                    <div class="view-more text-center parent-btn-load">
                                                        <button class="btn-load-more">Xem thêm</button>
                                                    </div>
                                                    {{--                                    <p class="special-promotion">Khuyến mại đặc biệt ( SL có hạn)</p>--}}
                                                @endif
                                            @endif

                                            <div class="box-khuyen-mai-detail">
                                                <h4 class="title">
                                                    <img src="{{asset('html-viettech/images/icon-khuyen-mai-detail.png')}}"
                                                         alt="">
                                                    QUÀ TẶNG/ KHUYẾN MẠI
                                                </h4>
                                                <div class="content-khuyen-mai">
                                                    @if(!empty($data->sale_detail))
                                                        @php($summary = explode("\n",$data->sale_detail))
                                                        <ul class="list-unstyled">
                                                            @foreach($summary as $key => $item )
                                                                @if(substr($item, 0, 1) == "#")
                                                                    <h4>{{str_replace("#","",$item)}}</h4>
                                                                @else
                                                                    <li>
                                                                        {{$item}}
                                                                    </li>
                                                                @endif
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </div>
                                            </div>
                                            @if($data['out_of_stock'] == 0)
                                                <div class="quantity">
                                                    <div>Số lượng :</div>
                                                    <div class="action">
                                                        <button class="icon " @click="down_quan($event)">-</button>
                                                        <input type="number" name="qty" value="0" min="0"
                                                               v-model="quantity" class="count"/>
                                                        <button class="icon" @click="up_quan($event)">+</button>
                                                    </div>
                                                </div>
                                                <div class="buy-now">
                                                    <button class="btn btn-block" type="btn"
                                                            @click="add_to_cart($event)">
                                                        <img data-src="{{asset('html-viettech/images/icon-shopping-cart.png')}}"
                                                             class="lazyload" alt/>Mua ngay
                                                    </button>
                                                    {{--                                            1/6/2020--}}

                                                    {{--                                            <div class="quantity">--}}
                                                    {{--                                                <div>Số lượng :</div>--}}
                                                    {{--                                                <div class="action">--}}
                                                    {{--                                                    <button class="icon " @click="down_quan($event)">-</button>--}}
                                                    {{--                                                    <input type="number" name="qty" value="0" min="0" v-model="quantity" class="count" />--}}
                                                    {{--                                                    <button class="icon" @click="up_quan($event)">+</button>--}}
                                                    {{--                                                </div>--}}

                                                    <div class="purchase-form mt-2">
                                                        <button @click="installment($event,0)">
                                                            Trả góp 0%
                                                            <span>Xét duyệt nhanh qua điện thoại</span>
                                                        </button>
                                                        <button @click="installment($event,1)">
                                                            TRả góp qua thẻ
                                                            <span>Visa, Master Card, JCB</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                            <p class="text-center" style="color: #666;">
                                                Gọi
                                                <strong style="color: #D0021B;">{{$def['hotline']}}</strong> để được tư
                                                vấn mua hàng ( Miễn phí)
                                            </p>
                                        </div>
                                    </div>
                                    {{--                        box--}}
                                    <div class="col-md-5">
                                        <div class="product-policy">
                                            @if(!empty($data->lineBox))
                                                <div class="product-policy-info">
                                                    <div class="title">Trong hộp có:</div>
                                                    <ul class="list-unstyled">
                                                        @foreach(explode('|', $data->lineBox) as $line_box)
                                                            <li>
                                                                <img data-src="{{asset('html-viettech/images/icon-sidebar-1.png')}}"
                                                                     class="lazyload" alt/>{{$line_box}}
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <div class="product-policy-info">
                                                @if(isset($def['commitment']) && !empty($def['commitment']))
                                                    <div class="title">LAPVIP cam kết :</div>
                                                    {!! $def['commitment'] !!}
                                                @endif
                                                {{--                                            <ul class="list-unstyled">--}}
                                                {{--                                                <li>--}}
                                                {{--                                                    {!! $def['commitment'] !!}--}}
                                                {{--                                                </li>--}}
                                                {{--                                            </ul>--}}
                                                {{--                                            <ul class="list-unstyled">--}}
                                                {{--                                                <li>--}}
                                                {{--                                                    <img src="{{asset('html-viettech/images/icon-sidebar-2.png')}}" alt /> Hàng chính hãng--}}
                                                {{--                                                </li>--}}
                                                {{--                                                <li>--}}
                                                {{--                                                    <img src="{{asset('html-viettech/images/icon-sidebar-3.png')}}" alt /> Bảo hành 24 tháng chĩnh hãng--}}
                                                {{--                                                </li>--}}
                                                {{--                                                <li>--}}
                                                {{--                                                    <img src="{{asset('html-viettech/images/icon-sidebar-4.png')}}" alt /> Giao hàng miễn phí toàn quốc trong 60 phút--}}
                                                {{--                                                </li>--}}
                                                {{--                                                <li>--}}
                                                {{--                                                    <img src="{{asset('html-viettech/images/icon-sidebar-5.png')}}" alt /> Bảo hành nhanh tại LAPVIP shop trên toàn quốc--}}
                                                {{--                                                </li>--}}
                                                {{--                                            </ul>--}}
                                                {{--                                        <div class="search-shop">--}}
                                                {{--                                            <a href>--}}
                                                {{--                                                <img src="{{asset('html-viettech/images/icon-map-maker.png')}}" alt />--}}
                                                {{--                                                Tìm shop có hàng gần nhất--}}
                                                {{--                                            </a>--}}
                                                {{--                                        </div>--}}
                                                {{--                                    <div class="product-old">--}}
                                                {{--                                        <a href>Asus VIVOBOOK X507 UA EJ234T/ Core i3-7020U cũ</a>--}}
                                                {{--                                        Giá từ :--}}
                                                {{--                                        <span class="price">7.942.000đ</span>--}}
                                                {{--                                    </div>--}}
                                            </div>
                                            @if(!empty($supports))
                                                <div class="sp-online">
                                                    <label for="">Hỗ trợ trực tuyến</label>
                                                    @foreach($supports as $item_sp)
                                                        <div class="d-flex mb-3">
                                                            <div class="avatar">
                                                                @if(!empty($item_sp->avatar_supports))
                                                                    <img src="{{$item_sp->getImageUrl('small')}}"
                                                                         alt="">
                                                                @else
                                                                    <img src="{{asset('html-viettech/images/user.png')}}"
                                                                         alt="">
                                                                @endif
                                                            </div>
                                                            <div class="info-supports">
                                                                <p>Tư vấn : {{$item_sp->name}}</p>
                                                                <p>Liên hệ: {{$item_sp->phone}}</p>
                                                                <p>{{$item_sp->email}}</p>
                                                                <span class="w-100 d-flex mt-1"><a
                                                                            style="background: #4267b2"
                                                                            href="{{$item_sp->facebook}}"
                                                                            target="_blank">Facebook</a> &nbsp;<a
                                                                            style="background: #1DA1F2"
                                                                            href="http://zalo.me/{{$item_sp->phone}}"
                                                                            target="_blank">Zalo me!</a></span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                    {{--                                                        <div class="block">--}}
                                                    {{--                                                            <a href="http://zalo.me/" target="_blank" class="ch">--}}
                                                    {{--                                                                <span><img data-src="{{asset('html-viettech/images/zalo_ic.jpg')}}" class="lazyload" alt=""> Chat</span>--}}
                                                    {{--                                                                <span></span>--}}
                                                    {{--                                                            </a>--}}
                                                    {{--                                                            <div class="phone">ĐT: <a href="tel:"></a></div>--}}
                                                    {{--                                                        </div>--}}
                                                </div>
                                                <div class="sp-online border-top-0">
                                                    <div class="d-flex mb-2 i-f">
                                                        <i class="fa fa-home fs-20 text-info" aria-hidden="true"></i>:&nbsp;{{@$def['address_wh']}}
                                                    </div>
                                                    <div class="d-flex mb-2 i-f">
                                                        <i class="fa fa-phone fs-20 text-info" aria-hidden="true"></i>:&nbsp;{{$def['tel']}}
                                                    </div>
                                                    <div class="d-flex mb-2 i-f">
                                                        <i class="fa fa-envelope-o fs-20 text-info"
                                                           aria-hidden="true"></i>:&nbsp;{{$def['email']}}
                                                    </div>
                                                    <div class="d-flex mb-2 i-f">
                                                        <i class="fa fa-fax fs-20 text-info" aria-hidden="true"></i>:&nbsp;{{$def['hotline']}}
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    {{--                        endbox--}}
                                </div>
                            </div>
                        </div>
                    </div>

                </section>
            </div>
            <div class="sidebar_mobile d-block d-md-none">
                <div class="sidebar-right specifications " id="info_Product_mobile">
                    <div class="title">Thông số kỹ thuật</div>
                    <div class="specifications-desc">
                        @if(!empty($prd_have_sale['properties']))
                            @php($count_props = 0)
                            @foreach(json_decode($prd_have_sale['properties'], true) as $item_pro)
                                @if(!empty($item_pro['props']))
                                    <div class="desc-detail" id="specifications_mobile">
                                        @php($count_props += count($item_pro['props']))
                                        @if($count_props < 6)
                                            @foreach($item_pro['props'] as $key_key => $item_props)
                                                <div class="w-100 ">
                                                    <b>{{ $item_props['title'] }} : </b>
                                                    <span> {{ $item_props['value']   }}</span>
                                                </div>
                                            @endforeach
                                        @endif
                                    </div>
                                @endif
                            @endforeach
                        @endif

                        {{--                        <div class="desc-detail" v-for="(item_info, index) in infoPro" v-if="index < 3">--}}
                        {{--                            <div v-for="item_info_value in item_info.props" class="w-100">--}}
                        {{--                                <b>@{{ item_info_value.title }} : </b> <span> @{{ item_info_value.value }}</span>--}}
                        {{--                            </div>--}}
                        {{--                        </div>--}}

                        <div class="view-detail">
                            <button type="button" class="btn btn-outline-primary" data-toggle="modal"
                                    data-target="#modalSpecificationsMobile">
                                Xem cấu hình chi tiết
                                <img data-src="{{asset('html-viettech/images/icon-arrow-blue.png')}}" class="lazyload"
                                     alt/>
                            </button>
                        </div>
                    </div>
                    <div class="modal fade" id="modalSpecificationsMobile">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <!-- Modal Header -->
                                <div class="modal-header">
                                    <h4 class="modal-title">Thông số kỹ thuật chi tiết</h4>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>
                                <!-- Modal body -->
                                <div class="modal-body">
                                    <ul class="fs-dttsktul list-unstyled" v-for="(item_info_detail, index) in infoPro"
                                        style="max-width : 100%;">
                                        <li class="modal-specifications-title">@{{ item_info_detail.title }}</li>
                                        <li v-for="item_info_value_detail in item_info_detail.props">
                                            <label data-id="49">@{{ item_info_value_detail.title }} :</label>
                                            <span><a href="javascript:;" title=" item_info_value_detail.value ">@{{ item_info_value_detail.value }}</a></span>
                                        </li>


                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{--    end detail--}}

            {{--    slide product--}}
            <div class="tab-product-slider">
                <ul class="tab-control">
                    @if(count($equivalent) > 0)
                        <li><a href="javascipt:;" class="active" data-tab="#tab-1">{{$data->category->title}} tương
                                tự</a></li>@endif
                    @if(count($pro_manu) > 0)
                        <li><a href="javascipt:;" data-tab="#tab-2">{{$data->category->title}} cùng hãng</a></li>@endif
                </ul>
                <div class="tab-product">
                    <div class="tab-item" id="tab-1">
                        <div class="js-carousel box-suggest mb-4" data-items="5" data-dots="false" data-loop="true">
                            @if(count($equivalent) > 0)
                                @foreach($equivalent as $item_equi)
                                    <div class="item">
                                        <div class="product-item-2">
                                            <a href="{{route('product.detail', ['alias' => $item_equi->alias])}}"
                                               class="wrap-img">
                                                <img class="owl-lazy"
                                                     data-src="{{\ImageURL::getImageUrl($item_equi->image, \App\Models\Product::KEY, 'mediumx2')}}"
                                                     alt="">
                                                {{--                                            <img src="{{asset('upload/products/original/'.$item_equi->image)}}" alt="">--}}
                                            </a>
                                            <div class="body">
                                                <a href="{{route('product.detail', ['alias' => $item_equi->alias])}}"
                                                   class="name"> {{$item_equi->title}} </a>
                                                @if($item_equi->out_of_stock == 0)
                                                    @if(!empty($item_equi->priceStrike != 0))
                                                        <span class="price">
                                                            <span class="new text-danger"> {{\Lib::priceFormatEdit($item_equi->price)['price']}} đ </span>
                                                            <span class="old"> {{\Lib::priceFormatEdit($item_equi->priceStrike)['price']}} đ </span>
                                                        </span>
                                                    @else
                                                        <span class="price">
                                                            <span class="new text-danger"> {{\Lib::priceFormatEdit($item_equi->price)['price']}} đ </span>
                                                        </span>
                                                    @endif
                                                @else
                                                    <span class="price">
                                                        <span class="new text-danger">Liên hệ</span>
                                                    </span>
                                                @endif
                                                <div class="stars">
                                                    <span class="vote"><span class="star"
                                                                             data-vote="{{$item_equi->rate_avg != 0 ? $item_equi->rate_avg : 0}}"></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                    <div class="tab-item" id="tab-2" style="display: none;">
                        <div class="js-carousel box-suggest mb-4" data-items="5" data-dots="false" data-loop="true">
                            @if(count($pro_manu) > 0)
                                @foreach($pro_manu as $item_manu)
                                    <div class="item">
                                        <div class="product-item-2">
                                            <a href="{{route('product.detail', ['alias' => $item_manu->alias])}}"
                                               class="wrap-img">
                                                <img class="owl-lazy"
                                                     data-src="{{\ImageURL::getImageUrl($item_manu->image, \App\Models\Product::KEY, 'mediumx2')}}"
                                                     alt="">
                                            </a>
                                            <div class="body">
                                                <a href="{{route('product.detail', ['alias' => $item_manu->alias])}}"
                                                   class="name"> {{$item_manu->title}} </a>
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
                                                        <span class="new text-danger"> Liên hệ </span>
                                                    </span>
                                                @endif
                                                <div class="stars">
                                                    <span class="vote"><span class="star"
                                                                             data-vote="{{$item_manu->rate_avg != 0 ? $item_manu->rate_avg : 0}}"></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            {{--    end product--}}

            <section class="row main-specifications-wrapper">
                <div class="col-md-9 has-bg">
                    {{--                start-layout-product-tab--}}
                    <div class="main-wrap product-tab" id="product-tabs">
                        <ul class="nav nav-tabs ffrr">
                            <li class="nav-item">
                                <a class="nav-link active" href="#salient-features">Đặc điểm nổi bật</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#comment">Đánh giá & nhận xét</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#faq">Hỏi & Đáp</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#image">Hình ảnh</a>
                            </li>
                            <li class="nav-item ml-auto">
                                <a href="tel:0987654321" class="btn-call">
                                    <i class="fa fa-phone" aria-hidden="true"></i>
                                    <span>Tư vấn miễn phí</span>
                                    <span class="number">{{$def['hotline']}}</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="javascript:;" class="btn-buy-fix" @click="add_to_cart($event)">Mua Ngay</a>
                            </li>
                        </ul>
                    </div>
                    {{--                end-layout-product-tab--}}

                    {{--                start-layout-product-evaluate--}}
                    <div id="salient-features" class="main-wrap characteristics">
                        {{--                        {!!$data->sort_body !!}--}}
                        <div class="content">
                            {!! $data->body !!}
                        </div>
                        <p class="show-more">
                            <button class="nav-toggle">
                                <span>Đọc thêm</span>
                                <i class="fa fa-angle-down" aria-hidden="true"></i>
                            </button>
                        </p>
                    </div>
                    {{--                end-layout-product-evaluate--}}

                    {{--                start-layout-product-comment--}}
                    <div id="comment" class="main-wrap comment">
                        <div class="title heading">Đánh giá & Nhận xét {{$data->title}}</div>
                        <div class="box-rating">
                            <div class="box-star text-center clearfix">
                                <div class="point text-center">{{!empty($comment['rating']['avg']) ? $comment['rating']['avg'] : 0}}
                                    /5
                                </div>
                                <div class="stars">
                                    <input disabled type="hidden" class="rating" data-filled="star star-filled"
                                           data-empty="star star-empty" data-fractions="2"
                                           value="{{!empty($comment['rating']['avg']) ? $comment['rating']['avg'] : 0}}"/>
                                </div>
                                <p>{{count($comment['comment'])}} đánh giá & nhận xét</p>
                            </div>
                            {{--                        rate line--}}
                            <div class="box-evaluation text-center">
                                @for ($i = 5; $i >= 1; $i--)
                                    <div class="box-evaluation-line">
                                        <div class="star">{{$i}} sao</div>
                                        <div class="progress-bar-rating">
                                            <span class="rate-count-show five-star"
                                                  style="width: @if (!empty($comment['rating']) && isset($comment['rating'])) {{$comment['rating']['rating_'.$i]}}@else 0 @endif%;"></span>
                                        </div>
                                        <div class="total-rate"></div>
                                    </div>
                                @endfor
                            </div>
                            {{--                        end rate line--}}
                            <div class="box-send-evaluation text-center">
                                <p>Bạn đã dùng sản phẩm này?</p>
                                <button class="btn">
                                    Gửi đánh giá của bạn
                                    <img data-src="{{asset('html-viettech/images/icon-arrow.png')}}" class="lazyload"
                                         alt/>
                                </button>
                            </div>
                        </div>
                        <div class="modal-box-comment">
                            <div class="modal-box-title">Gửi nhận xét của bạn</div>
                            <div class="modal-box-content">
                                <p>Bạn chấm sản phẩm này bao nhiêu sao?</p>
                                <div class="stars">
                                    <input type="hidden" name="type_id" id="type_id" value="{{$data->id}}">
                                    <input type="hidden" class="rating" id="rating" name="rating"
                                           data-filled="star star-filled" data-empty="star star-empty"
                                           data-fractions="2" value="5.0"/>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <input type="text" name="name" id="name" class="form-control"
                                           placeholder="Họ và Tên của bạn">
                                </div>
                                <div class="col-sm-12">
                                    <textarea id="txtNoteRating" name="txtNoteRating" rows="3" class
                                              placeholder="Bạn có khuyên người khác mua sản phẩm này không? Tại sao?"></textarea>
                                </div>

                                <div class="actions">
                                    <div class="action-left">Một đánh giá có ích thường dài từ 100 ký tự trở lên</div>
                                    <div class="action-right">
                                        <button type="button" class="btn-cancel" href title>Hủy</button>
                                        <button type="button" onclick="shop.commentproduct()" class="btn-send">Gửi
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="comment-wrap">
                            <div class="comment-wrap-top d-flex justify-content-between">
                                <div class="comment-wrap-top-left">
                                    Khách hàng nhận xét
                                    <span>({{count($comment['comment'])}})</span>
                                </div>
                                <div class="comment-wrap-top-tab">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-toggle="tab" href="#new">Mới nhất</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-toggle="tab" href="#useful">Hữu ích nhất</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div id="new" class="tab-pane active">
                                    @foreach($comment['comment'] as $key => $item_comment)
                                        @if($key < 1)
                                            <div class="comment-wrap-detail">
                                                <div class="customer-comment-detail">
                                                    <div class="stars">
                                                        <input disabled type="hidden" class="rating"
                                                               data-filled="star star-filled"
                                                               data-empty="star star-empty" data-fractions="2"
                                                               value="{{$item_comment->rating}}"/>
                                                    </div>
                                                    Bởi : {{$item_comment->name}}
                                                    <img data-src="{{asset('html-viettech/images/icon-tick-green.png')}}"
                                                         class="lazyload" alt/>
                                                    <span class="text">Đã mua tại LAPVIP Shop</span>
                                                    <br/>{{$item_comment->comment}}
                                                </div>
                                            </div>

                                        @endif
                                    @endforeach
                                    <div class="content" id="show-more-product" style="display:none">
                                        @foreach($comment['comment'] as $key => $item_comment)
                                            @if($key > 1)
                                                <div class="comment-wrap-detail">
                                                    <div class="customer-comment-detail">
                                                        <div class="stars">
                                                            <input disabled type="hidden" class="rating"
                                                                   data-filled="star star-filled"
                                                                   data-empty="star star-empty" data-fractions="2"
                                                                   value="{{$item_comment->rating}}"/>
                                                        </div>
                                                        Bởi : {{$item_comment->name}}
                                                        <img data-src="{{asset('html-viettech/images/icon-tick-green.png')}}"
                                                             class="lazyload" alt/>
                                                        <span class="text">Đã mua tại LAPVIP Shop</span>
                                                        <br/>{{$item_comment->comment}}
                                                    </div>
                                                </div>

                                            @endif
                                        @endforeach
                                    </div>
                                    @if( count($comment['comment']) - 1 > 0)
                                        <p class="show-more">
                                            <a href="#show-more-product" class="nav-toggle-comment">
                                                Xem thêm
                                            </a>
                                        </p>
                                    @endif
                                </div>

                                <div id="useful" class="tab-pane fade">
                                    <br/>
                                    @foreach($comment['comment'] as $key => $item_comment)
                                        @if($item_comment->is_useful > 0)
                                            <h3>{{$item_comment->name}}</h3>
                                            <p>{{$item_comment->comment}}</p>
                                        @endif
                                    @endforeach
                                </div>


                            </div>
                        </div>
                    </div>
                    {{--                end-layout-product-comment--}}

                    {{--                start-layout-product-faq--}}
                    <div id="faq" class="main-wrap faq">
                        <div class="title heading">Hỏi Đáp về {{$data->title}}</div>
                        <form action="{{route('product.question', ['product_id' => $data->id])}}" method="POST">
                            @csrf
                            <div class="form-group">
                                <div class="row mb-3">
                                    <div class="col-sm-6">
                                        <input type="text" class="form-control" name="name" placeholder="Họ và Tên"
                                               required>
                                    </div>
                                    <div class="col-sm-6">
                                        <input type="email" class="form-control" name="email" placeholder="Email"
                                               required>
                                    </div>
                                </div>
                                <textarea placeholder="Viết bình luận của bạn( Vui lòng gõ tiếng Việt có dấu) "
                                          class="form-control" rows="5" name="question" style="overflow:auto"
                                          required></textarea>
                            </div>
                            <div class="send text-right">
                                <button type="submit">Gửi câu hỏi</button>
                            </div>
                        </form>
                        @foreach ($comment['question'] as $key => $item)
                            {{--                        @php($count_item = count($comment['question']))--}}
                            <div class="media">
                                <img class="mr-3 lazyload" data-src="{{asset('html-viettech/images/icon-person.png')}}"
                                     alt="Generic placeholder image"/>
                                <div class="media-body">
                                    <h5 class="mt-0">
                                        {{$item['name']}}
                                    </h5>
                                    <span>{{$item['question']}}</span> <small
                                            class="text-secondary">{{\Lib::dateFormat($item->created, 'd/m/Y - H:i')}}</small>
                                    <div class="reply js-show-reply">
                                        <a href="javascript:void(0)">Trả lời</a>
                                    </div>
                                    @foreach($comment['answer_ques'] as $item_as)
                                        @if($item_as['qid'] != 0 && $item_as['qid'] == $item['id'])
                                            @if(!empty($item_as['answer']) && !empty($item_as['aid']))
                                                <div class="media mt-3">
                                                    <a class="mr-3 wrap-img" href="javascript:;">
                                                        <img data-src="{{!empty($item_as->user->image) ? $item_as->user->getImageAvatar('small') : asset('html-viettech/images/icon-person.png')}}"
                                                             class="lazyload" alt="{{$item_as['name']}}"/>
                                                    </a>
                                                    <div class="media-body">
                                                        <h5 class="mt-0">
                                                            {{$item_as['name']}}
                                                            <span class="badge bg-danger text-light">QTV</span>
                                                        </h5>
                                                        <span>{{$item_as['answer']}}</span> <small
                                                                class="text-secondary">{{\Lib::dateFormat($item_as->created, 'd/m/Y - H:i')}}</small>
                                                    </div>
                                                </div>
                                            @else
                                                <div class="media mt-3">
                                                    <a class="mr-3 wrap-img" href="javascript:;">
                                                        <img data-src="{{asset('html-viettech/images/icon-person.png')}}"
                                                             class="lazyload" alt="Generic placeholder image"/>
                                                    </a>
                                                    <div class="media-body">
                                                        <h5 class="mt-0">
                                                            {{$item_as['name']}}
                                                        </h5>
                                                        <span>{{$item_as['answer']}}</span> <small
                                                                class="text-secondary">{{\Lib::dateFormat($item_as->created, 'd/m/Y - H:i')}}</small>
                                                    </div>
                                                </div>
                                            @endif
                                        @endif
                                    @endforeach
                                    <div class="box-reply js-box-reply" style="display: none">
                                        <form action="{{route('product.question', ['product_id' => $data->id, 'qid' => $item->id])}}"
                                              method="POST">
                                            @csrf
                                            <div class="row mb-3">
                                                <div class="col-sm-6">
                                                    <input type="text" class="form-control" name="name"
                                                           placeholder="Họ và Tên" required>
                                                </div>
                                                <div class="col-sm-6">
                                                    <input type="email" class="form-control" name="email"
                                                           placeholder="Email" required>
                                                </div>
                                            </div>
                                            <textarea rows="3" class="box-ad-comment" name="question"
                                                      placeholder="Viết bình luận của bạn (Vui lòng gõ tiếng Việt có dấu)"
                                                      required></textarea>
                                            <div class="fs-cmbtn-send text-right">
                                                <button class="btn_comment_send_sub" type="submit">Gửi</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @if ($comment['paginate']->total() > 1)
                        <nav aria-label="Page navigation" class="main-wrap">
                            {{$comment['paginate']->render('FrontEnd::layouts.pagin')}}
                        </nav>
                    @endif
                    {{--                end-layout-product-faq--}}

                    {{--                <orther_gallery></orther_gallery>--}}
                    <div class="orther_gallery" id="image">
                        <h4>Hình ảnh thực tế ({{count($data->images)}} ảnh)</h4>
                        <div class="js-carousel" data-items="5" data-dots="false" data-margin="20">
                            @foreach($data->images as $key => $img_item_fo)
                                <div class="item">
                                    <a href="{{\ImageURL::getImageUrl($img_item_fo->image, 'products', 'original')}}"
                                       data-fancybox="images-preview">
                                        <img data-src="{{\ImageURL::getImageUrl($img_item_fo->image, 'products', 'mediumx2')}}"
                                             class="owl-lazy" alt="">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="sidebar">
                        {{--                    <sidebar-right-specifications></sidebar-right-specifications>--}}
                        <div class="sidebar-right specifications d-none d-md-block">
                            <div class="title">Thông số kỹ thuật</div>
                            <div class="specifications-desc" >
                                @if(!empty($prd_have_sale['properties']))
                                    @php($count_props = 0)
                                    @foreach(json_decode($prd_have_sale['properties'], true) as $item_pro)
                                        @if(!empty($item_pro['props']))
                                            <div class="desc-detail" id="specifications">
                                                @php($count_props += count($item_pro['props']))
                                                @if($count_props < 6)
                                                    @foreach($item_pro['props'] as $key_key => $item_props)
                                                        <div class="w-100 ">
                                                            <b>{{ $item_props['title'] }} : </b>
                                                            <span> {{ $item_props['value']   }}</span>
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        @endif
                                    @endforeach
                                @endif

                                {{--                                <div class="desc-detail" v-for="(item_info, index) in infoPro" v-if="index < 3">--}}
                                {{--                                    <div v-for="item_info_value in item_info.props" class="w-100">--}}
                                {{--                                        <b>@{{ item_info_value.title }} : </b> <span> @{{ item_info_value.value }}</span>--}}
                                {{--                                    </div>--}}
                                {{--                                </div>--}}

                                <div class="view-detail">
                                    <button type="button" class="btn btn-outline-primary" data-toggle="modal"
                                            data-target="#modalSpecifications">
                                        Xem cấu hình chi tiết
                                        <img data-src="{{asset('html-viettech/images/icon-arrow-blue.png')}}"
                                             class="lazyload" alt/>
                                    </button>
                                </div>
                            </div>
                            <div class="modal fade" id="modalSpecifications">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <!-- Modal Header -->
                                        <div class="modal-header">
                                            <h4 class="modal-title">Thông số kỹ thuật chi tiết</h4>
                                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                                        </div>
                                        <!-- Modal body -->
                                        <div class="modal-body" id="specifications_detail">
                                            @if(!empty($prd_have_sale['properties']))
                                                @foreach(json_decode($prd_have_sale['properties'], true) as $item_pro)
                                                    <ul class="fs-dttsktul list-unstyled" style="max-width : 100%;" >
                                                        <li class="modal-specifications-title">{{ $item_pro['title'] }}</li>
                                                        @if(!empty($item_pro['props']))
                                                            @foreach($item_pro['props'] as $key_key => $item_props)
                                                                <li>
                                                                    <label data-id="49">{{ $item_props['title'] }}:</label>
                                                                    <span><a href="javascript:;">{{ $item_props['value'] }}</a></span>
                                                                </li>
                                                            @endforeach
                                                        @endif
                                                    </ul>
                                                @endforeach
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        {{--                        start-sidebar-right-compare-product--}}
                        <div class="sidebar-right compare">
                            <div class="title heading">So sánh sản phẩm tương đương</div>
                            <div class="compare p-3">

                                @foreach($product_compare as $item_compare)
                                    <div class="items same-characteristics">
                                        <div class="image">
                                            <a href="{{route('product.detail', ['alias' => $item_compare->alias])}}"
                                               class="thumbnail">
                                                <img data-src="{{\ImageURL::getImageUrl($item_compare->image, 'products', 'small')}}"
                                                     class="lazyload" alt/>
                                            </a>
                                        </div>
                                        <div class="desc">
                                            <a class="link-title"
                                               href="{{route('product.detail', ['alias' => $item_compare->alias])}}">{{$item_compare->title}}</a>
                                            <div class="desc-price text-danger">@if($item_compare->out_of_stock == 0){{\Lib::priceFormatEdit($item_compare->price, '')['price']}}
                                                đ @else Liên hệ @endif</div>
                                            <div class="parameter">
                                                @foreach(explode('|', $item_compare->parameter) as $item_sapo)
                                                    <span>{{$item_sapo}}</span>
                                                @endforeach
                                            </div>
                                            <div class="link-view">
                                                <a href="{{route('com.product.compare', ['pro_parent' => str_slug($data->title), 'pro_child'=> str_slug($item_compare->title)])}}">Xem
                                                    so sánh chi tiết</a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                        </div>
                        {{--                        end-sidebar-right-compare-product--}}
                        {{--                    <sidebar-right-accessories></sidebar-right-accessories>--}}
                        {{--                    <sidebar-right-article></sidebar-right-article>--}}
                        {{--                    <sidebar-right-compare-product></sidebar-right-compare-product>--}}
                    </div>
                </div>
            </section>

            {{--    <home_title home_title="sản phẩm bạn vừa xem"></home_title>--}}
            {{--    <div class="js-carousel box-suggest mb-4" data-items="5" data-dots="false" data-loop="true">--}}
            {{--        <div class="item">--}}
            {{--            <product_item_2></product_item_2>--}}
            {{--        </div>--}}
            {{--        <div class="item">--}}
            {{--            <product_item_2></product_item_2>--}}
            {{--        </div>--}}
            {{--        <div class="item">--}}
            {{--            <product_item_2></product_item_2>--}}
            {{--        </div>--}}
            {{--        <div class="item">--}}
            {{--            <product_item_2></product_item_2>--}}
            {{--        </div>--}}
            {{--        <div class="item">--}}
            {{--            <product_item_2></product_item_2>--}}
            {{--        </div>--}}
            {{--        <div class="item">--}}
            {{--            <product_item_2></product_item_2>--}}
            {{--        </div>--}}
            {{--    </div>--}}
        </div>
    </div>

@endsection
@push('js_bot_all')
    <script src="https://unpkg.com/http-vue-loader"></script>
    <script>


        $(document).ready(function () {
            let i = 0;
            $(".show-more .nav-toggle").click(function () {
                $(".content").toggleClass('show-up-content');
                $(this).parent().toggleClass('rv_ps');
                i++;
                if (i % 2 == 1) {
                    $(this).find('span').text("Rút gọn");
                } else {
                    $(this).find('span').text("Xem thêm");
                }
                $(this).find('i').toggleClass('up_down');
            });
            $('.main-load-more').each(function () {
                $(this).find('.load').slice(0, 4).show();
                if ($(this).find('.load').length <= 4) {
                    $(this).siblings('.parent-btn-load').children('.btn-load-more').css('display', 'none')
                }
            })
            $('.btn-load-more').click(function () {
                $(this).parent('.parent-btn-load').siblings('.main-load-more').find('.load:hidden').slice(0, 2).slideDown();
                if ($(this).parent('.parent-btn-load').siblings('.main-load-more').find('.load:hidden').length == 0) {
                    $(this).css('display', 'none')
                }
            })
        });

        $('.js-show-reply a').click(function () {
            $(this).parent().siblings('.js-box-reply').slideToggle();
            $([document.documentElement, document.body]).animate({
                scrollTop: $(this).parent().siblings('.js-box-reply').offset().top
            }, 300);
        });

        var prd_id = {{$data->id}};
        var percent = '{!! json_encode($percent) !!}';
        var customer_gp = '{!! json_encode($customer_gp) !!}';
                {{--var warehouse_ = '{!! json_encode($warehoue) !!}';--}}
                {{--var store = '{!! json_encode($storage) !!}';--}}
                {{--var sale = '{!! $prd_have_sale['promote_props'] !!}';--}}
        var infoPro = '{!! $prd_have_sale['properties'] !!}';
        var prices = '{!! json_encode($prd_prices['prices']) !!}';
        var filters = '{!! json_encode($prd_prices['filters']) !!}';
        var filter_prices = '{!! isset($product_prices['data']) ? json_encode($product_prices['data']) : '' !!}';
        var filter_cates = '{!! isset($prd_prices['filter_cates']) ? json_encode($prd_prices['filter_cates']) : '' !!}';


    </script>
    {!! \Lib::addMedia('js/features/product/detail.js') !!}

@endpush

