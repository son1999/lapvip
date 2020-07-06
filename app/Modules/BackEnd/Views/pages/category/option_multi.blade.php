@php($mode = !empty($mode) ? $mode : 0)
@foreach($options as $v)
    @if(!empty($v['sub']))
        @if($mode == 0)
            <optgroup label="{{ $v['title'] }}">
                @else
                    <option value="{{ $v['id'] }}" @foreach($def as $df){{ $df['id']==$v['id'] ? ' selected="selected"' : '' }}@endforeach>{{ $v['title'] }}</option>
                @endif
                @foreach($v['sub'] as $sub)
                    @if(!empty($sub['sub']))
                        @if($mode == 0)
                            <optgroup label=" &nbsp; &nbsp; &nbsp; &nbsp;{{ $sub['title'] }}">
                                @else
                                    <option value="{{ $sub['id'] }}" @foreach($def as $df) {{ $df['id']==$sub['id'] ? ' selected="selected"' : '' }} @endforeach> &nbsp; &nbsp; {{ $sub['title'] }}</option>
                                @endif
                                @foreach($sub['sub'] as $s)
                                    <option value="{{ $s['id'] }}" @foreach($def as $df) {{ $df['id']==$s['id'] ? ' selected="selected"' : '' }} @endforeach> &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp;{{ $s['title'] }} - {{ $sub['title'] }}</option>
                                @endforeach
                                @if($mode == 0)
                            </optgroup>
                        @endif
                    @else
                        <option value="{{ $sub['id'] }}" @foreach($def as $df) {{ $df['id']==$sub['id'] ? ' selected="selected"' : '' }} @endforeach> &nbsp; &nbsp; {{ $sub['title'] }}</option>
                    @endif
                @endforeach
                @if($mode == 0)
            </optgroup>
        @endif
    @else
        <option value="{{ $v['id'] }}"@foreach($def as $df){{ $df['id']==$v['id'] ? ' selected="selected"' : '' }}@endforeach>{{ $v['title'] }}</option>
    @endif
@endforeach
