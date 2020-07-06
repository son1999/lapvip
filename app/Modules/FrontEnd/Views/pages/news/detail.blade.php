@extends('FrontEnd::layouts.home')

@section('title') {!! \Lib::siteTitle($site_title, $def['site_title']) !!} @stop
@section('g_meta')
    <meta itemprop="name" content="{{$data->title}}" />
    <meta itemprop="description" content="{{ strip_tags($data->sapo) }}" />
    <meta itemprop="image" content="{{\ImageURL::getImageUrl($data->image, \App\Models\Product::KEY, 'original')}}">
@endsection
@section('meta_basic')
    <meta name="title" content="{{ $data->title_seo}}"/>
    <meta name="description" content="{{ $data->description_seo}}"/>
    <meta name="keywords" content="{{ $data->keywords}}"/>
@stop
@section('facebook_meta')
    <meta property="og:locale" content="vi_VN" />
    <meta property="og:title" content="{{$data->title_seo}}" />
    <meta property="og:description" content="{{$data->description_seo}}" />
    <meta property="og:url" content="{{url()->current()}}" />
    <meta property="og:site_name" content="{{env('APP_NAME')}}" />
    <meta property="og:image" content="{{ !empty($data->image_seo) ? $data->getImageSeoUrl('original') : $data->getImageUrl('original')}}" />
    <meta property="og:image:width" content="800" />
    <meta property="og:image:height" content="800" />
@stop
@section('content')
    <main>
        <div class="container news-detail-page">
            <div class="blog-list-cat">
                <ul>
                    @if(!empty($news))
                    @foreach($news as $item_news)
                        <li @if(isset($data->cates) && !empty($data->cates)) @if($item_news['id'] == $data->cates->id) class="active" @endif @endif>
                            <a href="{{route('news.list', ['slug_title' => str_slug($item_news['title']), 'cat'=>$item_news['id']])}}">{{$item_news['title']}}</a>
                        </li>
                    @endforeach
                    @endif
                </ul>
            </div>
            <div class="wrap-content">
                <div class="news-detail-content">
                    <div class="header">
                        <div class="title">{{$data['title']}}</div>
                        <div class="info">
                            <div class="author">
                                <div class="wrap-img"><img data-src="{{!empty($data->user->image) ? $data->user->getImageAvatar('small') : ''}}" class="lazyload" alt=""></div>
                                <span>{{!empty($data->user->fullname) ? $data->user->fullname : ''}}</span>
                            </div>
                            <span class="date">{{\Lib::dateFormat($data->created, 'd-m-Y')}}</span>
{{--                            <div class="comment"><span><i class="fa fa-comments" aria-hidden="true"></i> 47</span> Bình luận</div>--}}
                        </div>
                        <div class="suggest">
                            @foreach($reletd_new as $item_relate)
                                <a href="{{route('news.detail', ['cate_title' => isset($item_relate->cates) && !empty($item_relate->cates) ? str_slug($item_relate->cates->title) : 'no-cat' ,'alias' => str_slug($item_relate->title)])}}">{{$item_relate->title}}</a>
                            @endforeach
                        </div>
                    </div>
                    <div class="main-content">
                        {!! $data->body !!}
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
@section('js_bot')
    {{-- <script language="javascript">
        shop.formSearch.saveInfoFromVar('{!! $data->start() !!}','{!! $data->from !!}','{!! $data->to !!}');
    </script> --}}
    <script src="{{asset('js/home.js')}}?ver={{$def['version']}}"></script>
@endsection
