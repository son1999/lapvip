<div class="container py-3 mb-3">
    <h5><i class="la la-1x la-bars"></i> DANH MỤC SẢN PHẨM</h5>
    <div class="row no-gutters">
        <div class="col-sm-3">
            <div id="homeCat" class="list-group bg-white shadow-sm">
                @foreach($category as $item)
                <a href="{{ $item['link'] }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                    {{ $item['title'] }}
                    <span class="badge p-0"><i class="la la-angle-right"></i></span>
                </a>
                @endforeach
            </div>
        </div>
        <div class="col-sm-9">
            <div id="bannerSlide" class="carousel slide" data-ride="carousel">
                @if(!empty($slide))
                    @if($slide->count() > 1)
                        <ol class="carousel-indicators">
                            @foreach($slide as $item)
                                <li data-target="#bannerSlide" data-slide-to="{{ $loop->index }}" @if($loop->first) class="active" @endif></li>
                            @endforeach
                        </ol>
                    @endif
                    <div class="carousel-inner">
                        @foreach($slide as $item)
                            <div class="carousel-item @if($loop->first) active @endif">
                                <img class="d-block w-100" src="{{ $item->getImageUrl('large') }}" alt="{{ $item->title }}">
                            </div>
                        @endforeach
                    </div>
                    @if($slide->count() > 1)
                        <a class="carousel-control-prev" href="#bannerSlide" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#bannerSlide" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>