@extends('emails.template')

@section('content')
    @if(!empty($err))
        <div style="font-weight: bold;">Có lỗi xảy ra!!!</div>
        <ul style="margin-top:10px">
        @foreach($err as $e)
            <li>{{ $e }}</li>
        @endforeach
        </ul>
    @else
        Thành công
    @endif
@stop