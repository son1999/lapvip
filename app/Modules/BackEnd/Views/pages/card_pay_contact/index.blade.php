@extends('BackEnd::layouts.default')

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <div class="mb-5"><h1>Quản trị {{ $site_title }}</h1></div>
            @dump($data)
        </div>
    </div>
@stop