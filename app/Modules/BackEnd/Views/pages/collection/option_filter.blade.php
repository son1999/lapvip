@php($mode = !empty($mode) ? $mode : 0)
@foreach($options as $v)
    @if(!empty($v->filters))
        @if($mode == 0)
            <optgroup label="{{ $v->title }}">
                @else
                    <option value="{{ $v->id }}"{{ $def==$v->id ? ' selected="selected"' : '' }}>{{ $v->title }}</option>
                @endif
                @foreach($v->filters as $sub)
                        <option value="{{ $sub->id }}"{{ $def==$sub->id ? ' selected="selected"' : '' }}> &nbsp; &nbsp; {{ $sub->title }}</option>
                @endforeach
                @if($mode == 0)
            </optgroup>
        @endif
    @else
        <option value="{{ $v->id }}"{{ $def==$v->id ? ' selected="selected"' : '' }}>{{ $v->title }}</option>
    @endif
@endforeach