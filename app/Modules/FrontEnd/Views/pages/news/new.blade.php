@if($newList->isNotEmpty())
<div class="travel-news">
    <div class="fs-26 fw-rbl travel-news-title">Tin mới</div>
    <div class="travel-news-list">
        <ul class="rs small-list">
            @foreach($newList as $item)
            <li class="small-list-item clearfix">
                <div class="small-list-img">
                    <a href="{{ $item->getLink() }}">
                        <img data-src="{{ $item->getImageUrl('medium2') }}" class="lazyload" alt="{{ $item->title_seo }}">
                    </a>
                </div>
                <div class="small-list-info">
                    <div class="big-list-item-tit fs-16 fc-black fw-osb">
                        <a href="{{ $item->getLink() }}">{{ $item->title }}</a></div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endif