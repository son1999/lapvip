<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="{{$def['site_description']}}">
  <meta name="keyword" content="{{$def['site_keyword']}}">

  <link rel="shortcut icon" href="{{asset($def['favicon'])}}">

  {!! \Lib::siteTitle('403 - Access denied') !!}

<!-- Icons -->
@foreach($def['site_media'] as $css)
  {!! \Lib::addMedia($css) !!}
@endforeach

<!-- Main styles for this application -->
@foreach($def['site_css'] as $css)
  {!! \Lib::addMedia($css) !!}
@endforeach

</head>

<body class="app flex-row align-items-center">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="clearfix">
        <h1 class="float-left display-3 mr-4">403</h1>
        <h4 class="pt-3">Oops! Your access is denied.</h4>
        <p class="text-muted">You don't have permission to access this route.</p>
      </div>
      <div class="input-prepend input-group">
        <span class="input-group-addon"><i class="fa fa-search"></i></span>
        <input id="prependedInput" class="form-control" size="16" type="text" placeholder="What are you looking for?">
        <span class="input-group-btn">
            <button class="btn btn-info" type="button">Search</button>
          </span>
      </div>
      <div class="mt-4" align="center">
        <button class="btn btn-success" type="button" onclick="shop.redirect('{{ route('admin.home')  }}')">Go Back</button>
      </div>
    </div>
  </div>
</div>
<script>
  var ENV = {
    version: '{{ env('APP_VER', 1) }}',
@foreach($def['site_js_val'] as $key => $val)
    {{$key}}: '{{$val}}'
@endforeach
  };
</script>

<!-- Bootstrap and necessary plugins -->
@foreach($def['site_js'] as $js)
  {!! \Lib::addMedia($js) !!}
@endforeach
</body>
</html>