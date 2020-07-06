@if ($paginator->hasPages())
    <ul class="rs pagin fc-gray fw-osb ta-c clearfix">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            {{--<li class="page-item disabled"><span class="page-link">Prev</span></li>--}}
        @else
            <li><a href="{{ $paginator->previousPageUrl() }}"><i class="icons iPrev"></i></a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <li><a href="#">{{ $element }}</a></li>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <li class="active"><a href="#">{{ $page }}</a></li>
                    @else
                        <li><a href="{{ $url }}">{{ $page }}</a></li>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <li><a href="{{ $paginator->nextPageUrl() }}"><i class="icons iNext"></i></a></li>
        @else
            {{--<li class="page-item disabled"><span class="page-link">Next</span></li>--}}
        @endif
    </ul>
@endif
