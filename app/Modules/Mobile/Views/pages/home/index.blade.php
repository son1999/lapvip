@extends('Mobile::layouts.home')

@section('title') {!! \Lib::siteTitle($site_title, $def['site_title']) !!} @stop

@section('content')
    <div class="container">
        Hello world !!! This is mobile version
    </div>
@endsection