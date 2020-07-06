<div class="banner mt-2">
    <div class="home-banner">
        @if(!empty($static))
            @foreach($static as $item_page)
                <div class="quick-sale" >
                    <div class="wrap-img">
                        <img class="point" src="{{asset('html-viettech/./images/clipse.svg')}}" alt />
                    </div>
                    <div class="content" id="stepsId">
                        {{$item_page->title}}
                    </div>
                    <a class="action" href="{{route('trangtinh', ['link_seo' => \Illuminate\Support\Str::slug($item_page->title)])}}">
                        <span>Xem thêm</span>
                        <img src="{{'html-viettech/./images/magic_pointer.png'}}" alt />
                    </a>
                </div>
            @endforeach
        @endif
        <div class="row">
            <div class="slider-sale col-md-8">
                @if(!empty($slide))
                    <div id="sync1" class="owl-carousel owl-theme big-img">
                        @foreach($slide as $i_slide)
                        <a href="{{$i_slide->link}}" target="_blank" class="item">
                            <img src="{{$i_slide->getImageUrl('slide_custome')}}"  alt="{{$i_slide->title}}" />
                        </a>
                        @endforeach
                    </div>
                    <div id="sync2" class="owl-carousel owl-theme big-img-title">
                        @foreach($slide as $i_slide_title)
                        <div class="item">
                            <span>{{$i_slide_title->title}}</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="w-100">
                        <h5 class="name text-center w-100">Updating.....!!!</h5>
                    </div>
                @endif
            </div>

            <div class="col-md-4 right-banner">
                @if(!empty($banner_right))
                    @foreach($banner_right as $key => $i_b_r)
                        <div class="small-banner-1">
                            <a href="{{$i_b_r->link}}">
                                <img src="{{$i_b_r->getImageUrl('mediumx2')}}" alt="{{$i_b_r->title}}" />
                            </a>
                        </div>
                    @endforeach
                @else
                    <div class="w-100">
                        <h5 class="name text-center w-100">Updating.....!!!</h5>
                    </div>
                @endif

                <div class="news">
                    <div class="title">
                        <span class="name">tin công nghệ nổi bật</span>
                        @if(!empty($news))
                            <a href="{{route('news.list', ['slug_title' => 'tin-tuc', 'cat' => 0])}}">Xem tất cả</a>
                        @endif
                    </div>
                    <div class="item-list">
                        @if(!empty($news))
                            @foreach($news as $i_n)
                                <div class="item">
                                    <div class="thumbnail">
                                        <a href="{{route('news.detail', ['cate_title' => isset($i_n->cates) && !empty($i_n->cates) ? str_slug($i_n->cates->title) : 'no-cat' ,'alias' => str_slug($i_n->title)])}}" class="wrap-img">
                                            <img src="{{$i_n->getImageUrl('small')}}"  alt />
                                        </a>
                                    </div>
                                    <a href="{{route('news.detail', ['cate_title' => isset($i_n->cates) && !empty($i_n->cates) ? str_slug($i_n->cates->title) : 'no-cat' ,'alias' => str_slug($i_n->title)])}}" class="name">
                                        <span>{{$i_n->title}}</span>
                                    </a>
                                </div>
                            @endforeach
                        @else
                            <div class="w-100">
                                <h5 class="name text-center w-100">Updating.....!!!</h5>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if(!empty($banner_bottom))
            <a href class="bottom-banner">
                <img src="{{$banner_bottom->getImageUrl('original')}}"  alt />
            </a>
        @endif
    </div>
</div>

@section('js_bot')
    <script type="text/javascript">
        var colors = ["#e74c3c","#00a8ff", "#9c88ff", "#fbc531", "#4cd137", "#487eb0"]
        var currentColor = 0
        var lis = document.querySelectorAll("#stepsId")
        function changeColor() {
            --currentColor
            if (currentColor < 0) currentColor = colors.length -1
            for (var i = 0; i < lis.length; i++) {
                lis[i].style.color = colors[(currentColor +i) % colors.length]
            }
        }
        setInterval(changeColor, 800)
    </script>
@endsection