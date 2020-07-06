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
{{--            @if(isset($video_g))--}}
{{--                <div class="page-video-hot">--}}
{{--            @endif--}}
                    <div class="row">
{{--                        @if(isset($video_g))--}}
{{--                            <div class="col-12 col-lg-9">--}}
{{--                                <div class="video-hot-content-left">--}}
{{--                                    @if(!empty($v_hot))--}}
{{--                                        <div class="video-advertise has-padding">--}}
{{--                                            <div class="fs-vdsboxs">--}}
{{--                                                <div class="fs-newsrow clearfix">--}}
{{--                                                    <div id="lastVideoShow">--}}
{{--                                                        <div class="title-video-top">--}}
{{--                                                            <h2 class="fs-vds--tit"><img data-src="{{asset('html-viettech/images/youtube2.png')}}" class="lazyload" alt="VIDEO HOT"> <span>VIDEO</span></h2>--}}
{{--                                                        </div>--}}
{{--                                                        <div class="fs-iframes">--}}
{{--                                                            <iframe width="560" height="315" src="https://www.youtube.com/embed/{{\Lib::youtube_id($v_hot['link'])}}" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>--}}
{{--                                                        </div>--}}
{{--                                                        <p class="fs-vds-bots"><span class="fs-vds-view">{{\Lib::youtube_view_count($v_hot['link'])}} luợt xem</span> <span class="fs-vds-time">{{\Lib::time_stamp(\Lib::youtube_view_date($v_hot['link']))}} trước</span>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                    @endif--}}
{{--                                    @if(!empty($group))--}}
{{--                                        @foreach($group as $item_gr)--}}
{{--                                        <div class="video-news-content-wrap has-padding">--}}
{{--                                            <div class="title-infomation">--}}
{{--                                                <h4>{{$item_gr->title}}</h4>--}}
{{--                                                <p>{!! $item_gr->description !!}</p>--}}
{{--                                            </div>--}}
{{--                                            <div class="products">--}}
{{--                                                <div class="js-carousel"  data-items="3" data-dots="true" data-arrows="false" data-margin="25">--}}
{{--                                                    @foreach($video_g as $item_vi)--}}
{{--                                                        @if($item_vi->gr_id == $item_gr->id)--}}
{{--                                                            <div class="item-product">--}}
{{--                                                                <div data-video-id="c0qvqHL6kVQ" data-video-url="https://www.youtube.com/embed/{{\Lib::youtube_id($item_vi['link'])}}?showinfo=0"--}}
{{--                                                                     data-video-title="{{\Lib::youtube_view_name($item_vi['link'])}}"--}}
{{--                                                                     data-video-view="{{\Lib::youtube_view_count($item_vi['link'])}}" data-video-date="{{\Lib::time_stamp(\Lib::youtube_view_date($item_vi['link']))}}">--}}
{{--                                                                    <a class="clearfix video-btn" href="javascript:;"    data-toggle="modal"--}}
{{--                                                                       data-src="https://www.youtube.com/embed/{{\Lib::youtube_id($item_vi['link'])}}?showinfo=0" data-target="#myModal">--}}
{{--                                                                        <span class="fs-vds-img"><img data-src="https://i.ytimg.com/vi/{{\Lib::youtube_id($item_vi['link'])}}/0.jpg" class="lazyload" alt="{{\Lib::youtube_view_name($item_vi['link'])}}"></span>--}}
{{--                                                                        <small>{{\Lib::youtube_view_name($item_vi['link'])}}</small>--}}
{{--                                                                        <span>{{\Lib::youtube_view_count($item_vi['link'])}} lượt xem - {{\Lib::time_stamp(\Lib::youtube_view_date($item_vi['link']))}} giờ trước</span>--}}
{{--                                                                    </a>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        @endif--}}
{{--                                                    @endforeach--}}
{{--                                                </div>--}}
{{--                                                <div class="modal-box mt-5">--}}
{{--                                                    <!-- Modal -->--}}
{{--                                                    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">--}}
{{--                                                        <div class="modal-dialog" role="document">--}}
{{--                                                            <div class="modal-content">--}}
{{--                                                                <div class="modal-body">--}}
{{--                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span--}}
{{--                                                                                aria-hidden="true">&times;</span></button>--}}
{{--                                                                    <!-- 16:9 aspect ratio -->--}}
{{--                                                                    <div class="embed-responsive embed-responsive-16by9">--}}
{{--                                                                        <iframe class="embed-responsive-item" src="" id="video" allowscriptaccess="always"--}}
{{--                                                                                allow="autoplay"></iframe>--}}
{{--                                                                    </div>--}}
{{--                                                                </div>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        </div>--}}
{{--                                        @endforeach--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        @else--}}
                            <div class="col-12 col-lg-9">
                                <div class="blog-feature-list m-0">
                                    @if(count($list_hot) > 0)
                                        @foreach($list_hot as $hot)
                                            @if($hot->hot_new == 1)
                                                <div class="blog-item-big">
                                                    <div class="b-item">
                                                        <a href="{{route('news.detail', ['cate_title' => isset($hot->cates) && !empty($hot->cates) ? str_slug($hot->cates->title) : 'no-cat' ,'alias' => str_slug($hot['title'])])}}" class="b-img">
                                                            <img data-src="{{$hot->getImageUrl('large')}}" class="lazyload" alt="{{$hot['title']}}">
                                                        </a>
                                                        <div class="b-info">
                                                            <a href="{{route('news.detail', ['cate_title' => isset($hot->cates) && !empty($hot->cates) ? str_slug($hot->cates->title) : 'no-cat' ,'alias' => str_slug($hot['title'])])}}" class="b-name">
                                                                {{$hot['title']}}
                                                            </a>
                                                            <span><time> {{\Lib::time_stamp($hot['created'])}}</time> </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @break;
                                            @endif
                                        @endforeach
                                    @else
                                        <div class="w-100">
                                            <h5 class="name text-center w-100">Updating.....!!!</h5>
                                        </div>
                                    @endif
                                    @if(count($list_hot) > 0)
                                        <div class="blog-list-item">
                                            @foreach($list_hot as $item_list_hot)
                                                @if($item_list_hot->list_hot == 1)
                                                    <div class="b-item">
                                                        <a href="{{route('news.detail', ['cate_title' => isset($item_list_hot->cates) && !empty($item_list_hot->cates) ? str_slug($item_list_hot->cates->title) : 'no-cat' ,'alias' => str_slug($item_list_hot->title)])}}" class="b-img">
                                                            <img data-src="{{$item_list_hot->getImageUrl('small')}}" class="lazyload" alt="{{$item_list_hot->title}}">
                                                        </a>
                                                        <div class="b-info">
                                                            <a href="{{route('news.detail', ['cate_title' => isset($item_list_hot->cates) && !empty($item_list_hot->cates) ? str_slug($item_list_hot->cates->title) : 'no-cat' ,'alias' => str_slug($item_list_hot->title)])}}" class="b-name">
                                                                {{$item_list_hot->title}}
                                                            </a>
                                                            <span><time> {{\Lib::time_stamp($item_list_hot->created)}}</time></span>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                                @if(!empty($data))
                                    <div class="b-list mt-4">
                                        @foreach($data as $key => $item )
                                            @if($key < 4)
                                                <div class="blog-list-item ">
                                                    <a href="{{route('news.detail', ['cate_title' => isset($item->cates) && !empty($item->cates) ? str_slug($item->cates->title) : 'no-cat'  ,'alias' => str_slug($item->title)])}}" class="b-img">
                                                        <img data-src="{{$item->getImageUrl('medium')}}" class="lazyload" alt="{{$item->title}}">
                                                    </a>
                                                    <div class="b-meta">
                                                        <a href="{{route('news.detail', ['cate_title' => isset($item->cates) && !empty($item->cates) ? str_slug($item->cates->title) : 'no-cat' ,'alias' => str_slug($item->title)])}}" class="b-name">
                                                            {{$item->title}}
                                                        </a>
                                                        <p class="b-des">{!! strip_tags($item->sort_body) !!} </p>
                                                        <div class="b-author">
                                                            <img data-src="{{!empty($item->user->image) ? $item->user->getImageAvatar('small') : ''}}" class="lazyload" alt="">
                                                            <span>{{\Lib::time_stamp($item->created)}}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <div class="w-100">
                                        <h5 class="name text-center w-100">Updating.....!!!</h5>
                                    </div>
                                @endif
                                @if(count($v_defaul) > 0)
                                    <div class="fs-vdsboxs">
                                        <div class="fs-newsrow clearfix">
                                            <div id="lastVideoShow" class="fs-newscol fs-newscol5">
                                                <h2 class="fs-vds-tit"><img data-src="{{asset('html-viettech/images/youtube2.png')}}" class="lazyload" alt="VIDEO HOT"> <span>VIDEO</span></h2>
                                                <div class="fs-iframes">
                                                    <iframe width="560" height="315" src="https://www.youtube.com/embed/{{$v_defaul[0]['video_id']}}?showinfo=0" frameborder="0" allowfullscreen></iframe>
                                                </div>
                                                <p class="fs-vds-bots"><span class="fs-vds-view">{{\Lib::numberFormat($v_defaul[0]['view_count'])}} luợt xem</span> <span class="fs-vds-time">{{\Lib::time_stamp($v_defaul[0]['published_at'])}} trước</span>
                                            </div>
                                            <div class="fs-newscol fs-newscol6">
                                                <div class="fs-vds-flow"><span>Theo dõi trên YouTube</span>
                                                    <div style="vertical-align: middle;display:inline-block;">
                                                        <div class="g-ytsubscribe" data-channelid="{{$v_defaul[0]['channel_id']}}" data-layout="default" data-count="default"></div>
                                                    </div>
                                                </div>
                                                <div id="lastVideo" class="fs-vds-ul">
                                                    <ul class="p-0">
                                                        @foreach($v_defaul as $key => $item_video)
                                                            @if($key != 0)
                                                            <li data-video-id="{{$item_video['video_id']}}" data-video-title="{{$item_video['title']}}" data-video-view="{{\Lib::numberFormat($item_video['view_count'])}}" data-video-date="{{\Lib::time_stamp($item_video['published_at'])}} trước">
                                                                <a class="clearfix" href="javascript:;" >
                                                                    <span class="fs-vds-img"><img data-src="{{$item_video['image_thumbnail']}}" class="lazyload"alt="{{$item_video['title']}}"></span>
                                                                    <h3 class="fs-vds-h">{{$item_video['title']}}</h3>
                                                                </a>
                                                            </li>
                                                            @endif
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(!empty($data))
                                    <div class="b-list" >
                                        <div class="main-load-more">
                                            @foreach($data as $key => $item )
                                                @if($key > 4)
                                                    <div class="blog-list-item load">
                                                        <a href="{{route('news.detail', ['cate_title' => isset($item->cates) && !empty($item->cates) ? str_slug($item->cates->title) : 'no-cat' ,'alias' => str_slug($item->title)])}}" class="b-img">
                                                            <img data-src="{{$item->getImageUrl('medium')}}" class="lazyload" alt="{{$item->title}}">
                                                        </a>
                                                        <div class="b-meta">
                                                            <a href="{{route('news.detail', ['cate_title' => isset($item->cates) && !empty($item->cates) ? str_slug($item->cates->title) : 'no-cat' ,'alias' => str_slug($item->title)])}}" class="b-name">
                                                                {{$item->title}}
                                                            </a>
                                                            <p class="b-des">{!! strip_tags($item->sort_body) !!}</p>
                                                            <div class="b-author">
                                                                <img data-src="{{!empty($item->user->image) ? $item->user->getImageAvatar('small') : ''}}" class="lazyload" alt="">
                                                                <span>{{\Lib::time_stamp($item->created)}}</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                        @if ($data->total() > 9)
                                            <div class="col-12 m-0 d-none d-lg-block">
                                                <div class="bg-white row py-5 justify-content-center mt-5">
                                                    <nav aria-label="Page navigation" class="main-wrap">
                                                        {{$data->links('FrontEnd::layouts.pagin')}}
                                                    </nav>
                                                </div>
                                            </div>
                                        @endif
{{--                                        <div class="text-center py-5 parent-btn-load">--}}
{{--                                            <a href="javascript:;" class="btn-load-more">Xem thêm tin tức →</a>--}}
{{--                                        </div>--}}
                                    </div>
                                @endif
                            </div>
{{--                        @endif--}}


                        {{--                @if(!empty($hot) && count($list_hot) > 0 && !empty($data) && !empty($v_defaul))--}}
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
                        {{--                @endif--}}
                    </div>
{{--            @if(isset($video_g))--}}
{{--                </div>--}}
{{--            @endif--}}


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
