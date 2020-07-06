@if($related->isNotEmpty())
<div class="related-list">
    <div class="fs-26 fw-rbl related-list-title">Tin liên quan</div>
    <ul class="rs small-list">
        @foreach ($related as $item)
        <li class="small-list-item  make-left">
            <div class="small-list-img">
                <a href="{{ $item->getLink() }}">
                    <img data-src="{{ $item->getImageUrl('medium2') }}" class="lazyload" alt="{{ $item->title_seo }}">
                </a>
            </div>
            <div class="small-list-info">
                <div class="big-list-item-tit fs-16 fc-white fw-osb">
                    <a href="{{ $item->getLink() }}">{{ $item->title }}</a></div>
                <div class="big-list-date">
                    <i class="icons iDate"></i>
                    <span class="fs-13">{{ \Lib::dateFormat($item->published, 'd. m. Y') }}</span>
                </div>
            </div>
        </li>
        @endforeach
    </ul>
</div>
@endif