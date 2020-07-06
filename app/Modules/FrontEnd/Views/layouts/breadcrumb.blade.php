
<nav class="breadcrumb">
    @php($countBr = count($breadcrumb))
    @if($countBr > 0 || !empty($extraCommand))
        @if($countBr > 1)
            @foreach($breadcrumb as $item)
                @if($loop->last)
                    <span class="breadcrumb-item active">{{ $item['title'] }}</span>
                @else
                    @if(!empty($item['link']))
                        <a class="breadcrumb-item" href="{{ $item['link'] }}">{{ $item['title'] }}</a>
                    @else
                        {{ $item['title'] }}
                    @endif
                @endif
            @endforeach
        @else
            <a class="breadcrumb-item" >{{ $defBr['title'] }}</a>
        @endif
        @if(!empty($extraCommand))
            <li class="breadcrumb-menu d-md-down-none">
                <div class="btn-group" role="group" aria-label="Button group">
                    @foreach($extraCommand as $item)
                        <a class="btn{{!empty($item['class'])?' '.$item['class']:''}}" href="{{ route($item['link']) }}">@if(!empty($item['icon']))<i class="{{ $item['icon'] }}"></i>@endif &nbsp;{{ $item['title'] }}</a>
                    @endforeach
                </div>
            </li>
        @endif
    @endif
</nav>