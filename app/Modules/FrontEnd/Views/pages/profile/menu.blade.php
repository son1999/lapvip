<div class="tab-left-link">
    @foreach($profile_menu as $m)
        <a href="{{ $m['link'] }}" @if($m['active']) class="active" @endif><i class="fa {{ $m['icon'] }}" aria-hidden="true"></i>{{ $m['title'] }}
            {{-- {!! isset($m['notice']) ? '<span>'.$count_unread.'</span>' : '' !!} --}}
        </a>
    @endforeach
</div>