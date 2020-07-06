<!doctype html>
<html lang="{{ $defLang }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{$def['site_description']}}">
    <meta name="keyword" content="{{$def['site_keyword']}}">
    <meta name="csrf-token" content="{{csrf_token()}}">

    <link rel="shortcut icon" href="{{asset($def['favicon'])}}">

@if(\View::hasSection('title'))
    @yield('title')
@else
    {!! \Lib::siteTitle($site_title, $def['site_title']) !!}
@endif

<!-- Styles required by this views -->

@yield('css')
<!-- Icons -->
@foreach($def['site_media'] as $css)
    {!! \Lib::addMedia($css) !!}
@endforeach

<!-- Main styles for this application -->
@foreach($def['site_css'] as $css)
    {!! \Lib::addMedia($css) !!}
@endforeach
@stack('css_after_all')
<!-- top script -->
@yield('js_top')
    <style>
        .al {
            height: auto;
            background-color: #3498db;
            color: white;
            border-top-left-radius: 50px;
            border-bottom-left-radius: 50px;
            border-top-right-radius: 50px;
            border-bottom-right-radius: 50px;
            margin-bottom: 10px;
            padding: 10px 20px;
        }

        .closebtn {
            margin-left: 15px;
            color: white;
            font-weight: bold;
            float: right;
            font-size: 22px;
            line-height: 20px;
            cursor: pointer;
            transition: 0.3s;
        }

        .closebtn:hover {
            color: black;
        }
    </style>
</head>

<body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden pb-0">

@include("BackEnd::layouts.header")

<div class="app-body">
    <div class="sidebar">
        <nav class="sidebar-nav">
            @include("BackEnd::layouts.menuLeft")
        </nav>
        <button class="sidebar-minimizer brand-minimizer" type="button"></button>
    </div>

    <!-- Main content -->
    <main class="main">
        @if(\View::hasSection('breadcrumb'))
            @yield('breadcrumb')
        @else
            {!! \Lib::renderBreadcrumb() !!}
        @endif

        <div class="container-fluid">
            <div class="animated fadeIn">
                @yield('content')
            </div>
        </div>
        <!-- /.conainer-fluid -->
    </main>

</div>

@include("BackEnd::layouts.footer")

<script type="text/javascript">
    var ENV = {
        version: '{{ env('APP_VER', 0) }}',
        token: '{{ csrf_token() }}',
@foreach($def['site_js_val'] as $key => $val)
        {{$key}}: '{{$val}}',
@endforeach
    },
    COOKIE_ID = '{{ env('APP_NAME', '') }}',
    DOMAIN_COOKIE_REG_VALUE = 1,
    DOMAIN_COOKIE_STRING = '{{ config('session.domain') }}';
</script>

@if ( env('APP_DEBUG') )
{!! \Lib::addMedia('js/prettyprint.js') !!}
@endif

<!-- Bootstrap and necessary plugins -->
@foreach($def['site_js'] as $js)
{!! \Lib::addMedia($js) !!}
@endforeach

<!-- other script -->
@yield('js_bot')
@stack('js_bot_all')

<script type="text/javascript">
    jQuery(document).ready(shop.ready.run);
</script>

</body>
</html>