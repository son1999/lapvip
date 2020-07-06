<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="{{$def['site_description']}}">
    <meta name="keyword" content="{{$def['site_keyword']}}">

    <link rel="shortcut icon" href="{{asset($def['favicon'])}}">
@yield('title')

<!-- Icons -->
    @foreach($def['site_media'] as $css)
        <link href="{{ asset($css) }}?ver={{$def['version']}}" rel="stylesheet">
    @endforeach

<!-- Main styles for this application -->
    @foreach($def['site_css'] as $css)
        <link href="{{ asset($css)  }}?ver={{$def['version']}}" rel="stylesheet">
    @endforeach
</head>

<body class="app flex-row align-items-center">
<div class="container">
    <div class="row justify-content-center">
        @yield('content')
    </div>
</div>

<script>
    var ENV = {
        version: '{{ env('APP_VER', 1) }}',
    @foreach($def['site_js_val'] as $key => $val)
    {{$key}}: '{{$val}}',
    @endforeach
    };
</script>
<!-- Bootstrap and necessary plugins -->
@foreach($def['site_js'] as $js)
    <script src="{{ asset($js) }}?ver={{$def['version']}}"></script>
@endforeach
</body>
</html>