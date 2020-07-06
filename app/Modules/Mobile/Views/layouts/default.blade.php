<!doctype html>
<html lang="{{ $defLang }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
@yield('title')

@if(View::hasSection('facebook_meta'))
    @yield('facebook_meta')
@else
    {!! $seoDefault['facebook_meta'] !!}
@endif
@if(View::hasSection('twitter_meta'))
    @yield('twitter_meta')
@else
    {!! $seoDefault['twitter_meta'] !!}
@endif
@if(View::hasSection('g_meta'))
    @yield('g_meta')
@else
    {!! $seoDefault['g_meta'] !!}
@endif

<!-- Icons -->
@foreach($def['site_media'] as $css)
    {!! \Lib::addMedia($css) !!}
@endforeach

<!-- Main styles for this application -->
@foreach($def['site_css'] as $css)
    {!! \Lib::addMedia($css) !!}
@endforeach

    @yield('css_top')

    @yield('js_top')
</head>

<body>
    @yield('content')

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

<script type="text/javascript">
    jQuery(document).ready(shop.ready.run);    //ready
</script>

</body>
</html>