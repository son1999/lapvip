@php($countBr = count($breadcrumb))
@if($countBr > 0 || !empty($extraCommand))
    <ol class="breadcrumb">
        @if($countBr > 1)
            @foreach($breadcrumb as $item)
                @if($loop->last)
                    <li class="breadcrumb-item active">{{ $item['title'] }}</li>
                @else
                    <li class="breadcrumb-item">
                        @if(!empty($item['link']))
                            <a href="{{ $item['link'] }}">{{ $item['title'] }}</a>
                        @else
                            {{ $item['title'] }}
                        @endif
                    </li>
                @endif
            @endforeach
        @else
            <li class="breadcrumb-item">{{ $defBr['title'] }}</li>
        @endif

        @if(!empty($extraCommand))
            <li class="breadcrumb-menu d-md-down-none">
                <div class="btn-group" role="group" aria-label="Button group">
                    @foreach($extraCommand as $item)
                        <a class="btn" href="{{ is_array($item['link']) ? route($item['link']['route'],$item['link']['params']) : route($item['link']) }}">@if(!empty($item['icon']))<i class="{{ $item['icon'] }}"></i>@endif &nbsp;{{ $item['title'] }}</a>
                    @endforeach
                </div>
            </li>
        @endif
    </ol>
@endif