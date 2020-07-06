@extends('FrontEnd::layouts.home', ['bodyClass' => 'has-cover'])

@section('title') {!! \Lib::siteTitle($site_title, $def['site_title']) !!} @stop

@section('content')
    <main>
        <div class="container">
            <div  class="blog-list-cat">
                <ul>
                    @foreach($news as $item_news)
                        <li @if($item_news['id'] == request()->cat) class="active" @endif>
                            <a href="{{route('news.list', ['slug_title' => str_slug($item_news['title']), 'cat'=>$item_news['id']])}}">{{$item_news['title']}}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
            <div class="page-video-hot">
                <div class="row">
                        <div class="col-12 col-lg-9">
                            <div class="video-hot-content-left">
                                @if(!empty($v_hot))
                                    <div class="video-advertise has-padding">
                                        <div class="fs-vdsboxs">
                                            <div class="fs-newsrow clearfix">
                                                <div id="lastVideoShow">
                                                    <div class="title-video-top">
                                                        <h2 class="fs-vds--tit"><img data-src="{{asset('html-viettech/images/youtube2.png')}}" class="lazyload" alt="VIDEO HOT"> <span>VIDEO</span></h2>
                                                    </div>
                                                    <div class="fs-iframes">
                                                        <iframe width="560" height="315" src="https://www.youtube.com/embed/{{$v_hot['video_id']}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                                    </div>
                                                    <p class="fs-vds-bots"><span class="fs-vds-view">{{\Lib::numberFormat($v_hot['view_count'])}} luợt xem</span> <span class="fs-vds-time">{{\Lib::time_stamp($v_hot['published_at'])}} trước</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(!empty($group))
                                    @foreach($group as $key => $item_gr)
                                        <div class="video-news-content-wrap has-padding">
                                            <div class="title-infomation">
                                                <h6>{{$item_gr->title_groups}}</h6>
                                                <p>{!! $item_gr['description'] !!}</p>
                                            </div>
                                            <div class="products">
                                                @if(!empty($item_gr->videos))
                                                    <div class="js-carousel"  data-items="3" data-dots="true" data-arrows="false" data-margin="25">
                                                        @foreach($item_gr->videos as $item_vi)
                                                            <div class="item-product">
                                                                <div data-video-id="{{$item_vi['video_id']}}" data-video-title="{{$item_vi['title']}}" data-video-view="{{\Lib::numberFormat($item_vi['view_count'])}}" data-video-date="{{\Lib::dateFormat($item_vi['published_at'], 'd-m-Y')}}">
                                                                    <a class="clearfix video-btn" href="javascript:;"    data-toggle="modal" data-id-video="{{$item_vi['video_id']}}" data-target="#myModal">
                                                                        <span class="fs-vds-img"><img data-src="{{$item_vi['image_thumbnail']}}" class="lazyload" alt="{{$item_vi['title']}}"></span>
                                                                        <span class="title">{{$item_vi['title']}}</span>
                                                                        <span>{{\Lib::numberFormat($item_vi['view_count'])}} lượt xem - {{\Lib::time_stamp($item_vi['published_at'])}} trước</span>
                                                                    </a>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                    <div class="modal-box mt-5">
                                                        <!-- Modal -->
                                                        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                            <div class="modal-dialog" role="document">
                                                                <div class="modal-content">
                                                                    <div class="modal-body">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span
                                                                                    aria-hidden="true">&times;</span></button>
                                                                        <!-- 16:9 aspect ratio -->
                                                                        <div class="embed-responsive embed-responsive-16by9">
                                                                            <iframe class="embed-responsive-item" src="" id="video" allowscriptaccess="always"
                                                                                    allow="autoplay"></iframe>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    @endforeach
                                    @if ($group->total() > 1)
                                        <div class="col-12 m-0">
                                            <div class="bg-white row justify-content-center">
                                                <nav aria-label="Page navigation" class="main-wrap">
                                                    {{$group->links('FrontEnd::layouts.pagin')}}
                                                </nav>
                                            </div>
                                        </div>
                                    @endif
                                @endif
                            </div>
                        </div>
                        <div class="col-12 col-lg-3">
                            @if(count($watch_a_lot) > 0)
                                <div class="block-sidebar">
                                    <h3 class="heading-sidebar">Xem nhiều</h3>
                                    @foreach($watch_a_lot as $item_watch)
                                        <div class="slider-blog type-1">
                                            <a href="{{route('news.detail', ['cate_title' => isset($item_watch->cates) && !empty($item_watch->cates) ? str_slug($item_watch->cates->title) : 'no-cat' ,'alias' => str_slug($item_watch->title)])}}" class="b-img">
                                                <img src="{{\ImageURL::getImageUrl($item_watch->image, 'news', 'small')}}" alt="">
                                            </a>
                                            <div class="b-info">
                                                <a href="{{route('news.detail', ['cate_title' => isset($item_watch->cates) && !empty($item_watch->cates) ? str_slug($item_watch->cates->title) : 'no-cat' ,'alias' => str_slug($item_watch->title)])}}" class="b-name">{{$item_watch->title}}</a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                            <div class="block-sidebar">
                                <h3 class="heading-sidebar">Sản phẩm mới</h3>
                                @foreach($new_product as $item_product)
                                    <div class="slider-blog type-2">
                                        <a href="{{route('product.detail', ['alias' => str_slug($item_product['title'])])}}" class="b-img">
                                            <img data-src="{{\ImageURL::getImageUrl($item_product['image'], \App\Models\Product::KEY, 'small')}}" class="lazyload" alt="{{$item_product['title']}}">
                                        </a>
                                        <div class="b-info">
                                            <a href="{{route('product.detail', ['alias' => str_slug($item_product['title'])])}}" class="b-name">{{$item_product['title']}}</a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('js_bot')

    <script type="text/javascript">
        $(document).ready(function () {
            $('.main-load-more').each(function () {

                $(this).find('.load').slice(0, 1).hide();

                if($(this).find('.load').length <= 1){
                    $(this).siblings('.parent-btn-load').children('.btn-load-more').css('display', 'none')
                }
            })
            $('.btn-load-more').click(function () {
                $(this).parent('.parent-btn-load').siblings('.main-load-more').find('.load:hidden').slice(0, 1).slideDown();
                if($(this).parent('.parent-btn-load').siblings('.main-load-more').find('.load:hidden').length == 0) {
                    $(this).css('display', 'none')
                }
            })
        });
    </script>

@stop
