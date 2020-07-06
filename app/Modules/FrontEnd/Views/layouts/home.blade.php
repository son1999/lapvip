<!doctype html>
<html lang="{{ $defLang }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="google-site-verification" content="GVH3-XXpEzJWEKLaV1Hh_nKybgRLiKt_KYCsa1oWLhA" />
    <meta name="google-site-verification" content="xKtcdYj918XXkeoeLjeopxPXCWy8_9QCMpAnkQg6yWA" />
    <link rel="shortcut icon" href="{{asset('favicon.png')}}">
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-TS5X4DV');
    </script>
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
            j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
            'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
        })(window,document,'script','dataLayer','GTM-THQ3G76');
    </script>
    <!-- End Google Tag Manager -->
    <style>
        img{
            max-width: 100%;
            height: auto;
        }
    </style>

@yield('title')

@if(View::hasSection('meta_basic'))
    @yield('meta_basic')
@else
    {!! $seoDefault['meta_basic'] !!}
@endif
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
    {!! @$def['web_push_notifications'] !!}
</head>
    {!! $def['script_box_fb'] !!}
    {!! $def['site_chatbot']  !!}
<body>
<!-- Google Tag Manager (noscript) -->
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TS5X4DV" height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<noscript>
    <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-THQ3G76" height="0" width="0" style="display:none;visibility:hidden"></iframe>
</noscript>
<!-- End Google Tag Manager (noscript) -->
{{--{!! $def['script_box_fb']!!}--}}
    <div id="app">

            @include('FrontEnd::layouts.header')
            @yield('content')
            @include('FrontEnd::layouts.components.loader')

            @include('FrontEnd::layouts.footer')

{{--            @include("FrontEnd::layouts.inbox")--}}
{{--            @include("FrontEnd::auth.pop_login")--}}
{{--            @include("FrontEnd::auth.pop_register")--}}
{{--            @include("FrontEnd::auth.pop_forgot_password")--}}
            <script type="text/javascript">
                var cart_items_root = [];
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
{{--            <script>--}}
{{--                const inbox = new Vue({--}}
{{--                    el : '#inbox',--}}
{{--                    data() {--}}
{{--                        return {--}}
{{--                            messeng: '',--}}
{{--                            email: '',--}}
{{--                            name: '',--}}
{{--                        }--}}
{{--                    },--}}
{{--                    methods: {--}}
{{--                        sendMes: function(even){--}}
{{--                            messeng = $('#messeng').val();--}}
{{--                            if(messeng.trim() === ''){--}}
{{--                                alert('Vui lòng nhập câu hỏi');--}}
{{--                            }else{--}}
{{--                                $('.popup_inbox-step1').hide();--}}
{{--                                $('.popup_inbox-step2').show();--}}
{{--                            }--}}
{{--                        },--}}
{{--                        sendInfo: function(even){--}}
{{--                            let err = '';--}}
{{--                            email = $('#mes_mail').val();--}}
{{--                            name = $('#mes_name').val();--}}
{{--                            if(email === ''){--}}
{{--                                err += 'Vui lòng nhập email hoạc số điện thoại \n';--}}
{{--                            }--}}
{{--                            if(name === ''){--}}
{{--                                err += 'Vui lòng nhập tên';--}}
{{--                            }--}}
{{--                            if(err != ''){--}}
{{--                                alert(err);--}}
{{--                            }else{--}}
{{--                                $('.popup_inbox-step2').hide();--}}
{{--                                $('.popup_inbox-step3').show();--}}
{{--                                alert(messeng + '\n'+ email + '\n' + name);--}}
{{--                            }--}}
{{--                        },--}}
{{--                        show_inbox: function(even){--}}
{{--                            $('.popup_inbox').show();--}}
{{--                        },--}}
{{--                        close_inbox: function(){--}}
{{--                            $(document).mouseup(function(e) {--}}
{{--                                var container = $(".popup-main");--}}
{{--                                if (!container.is(e.target) && container.has(e.target).length === 0) {--}}
{{--                                    $('.popup_inbox').hide();--}}
{{--                                }--}}
{{--                            });--}}
{{--                        }--}}
{{--                    }--}}
{{--                })--}}
{{--            </script>--}}

            <script type="text/javascript">
                $(window).bind('load', function() {
                    // $("img.lazyload").lazyload();
                    // $('.twitter-typeahead').addClass('w-100');
                    $('.tt-menu').addClass('w-100');
                });
                $(document).ready(function($) {

                    $('#search').keypress(function(event) {
                        if (event.keyCode == 13 || event.which == 13) {
                            $(document).ready(shop.search);
                        }
                    });

                    var engine1 = new Bloodhound({
                        remote: {
                            url: '/search/search-key?value=%QUERY%',
                            wildcard: '%QUERY%'
                        },
                        datumTokenizer: Bloodhound.tokenizers.whitespace('value'),
                        queryTokenizer: Bloodhound.tokenizers.whitespace
                    });


                    $(".s_input").typeahead({
                        hint: true,
                        highlight: true,
                        minLength: 1
                    }, [
                        {
                            source: engine1,
                            name: 'engine1',
                            limit: 20,
                            display: function(data) {
                                return data.title;
                            },
                            templates: {
                                empty: [
                                    '<div class=" w-100 list-group-item">Nothing found.</div>'
                                ],
                                // header: [
                                //     '<div class="list-group search-results-dropdown" style="width: 150% !important;"></div>'
                                // ],
                                suggestion: function (data) {
                                    return '<a href="{!! route('product.detail', ['alias' => ''])!!}/'+data.alias+'" class="list-group-item w-100" >' + data.title + '</a> <hr>'


                                }
                            }
                        },

                    ]);
                });


            </script>

            <script type="text/javascript">
                // $('#search').keypress(function(event) {
                //     if (event.keyCode == 13 || event.which == 13) {
                //         $(document).ready(shop.search);
                //     }
                // });
                if(window.innerWidth < 991){
                    $('.top-custommer > a').click(function(){
                        $(".top-custommer-drop").slideToggle();
                        // $(".top-custommer-drop").hide();
                    });
                    $('.top-custommer-drop a').click(function(){
                        // $(".top-custommer-drop").slideToggle();
                        $(".top-custommer-drop").hide();
                    });
                }
            </script>
            @if(!empty(\Request::route()) ? \Request::route()->getName() != 'cart.checkout.cart' : '')
                <script>
                    $(document).ready(function () {
                        $.ajaxSetup({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            }
                        });

                        $.ajax({
                            type: 'POST',
                            url: ENV.BASE_URL+"ajax/cart-number",
                            data: {_token:ENV.token, coupons_code: this.coupons_code},
                            dataType: 'json',
                        }).done(function(json) {
                            // if (json.error == 1) {
                            //     Swal.fire({
                            //         title: 'Thông báo',
                            //         text: json.msg,
                            //         type: 'warning',confirmButtonText: 'Đồng ý',confirmButtonColor: '#f37d26',
                            //     });
                            // } else {
                                $('.counter-cart').html(json.data);
                            // }
                        });

                    });

                </script>
            @endif
        <script async src="https://cdn.jsdelivr.net/npm/vanilla-lazyload@16.1.0/dist/lazyload.min.js"></script>
        <script type="text/javascript">
            var imgLazy = document.getElementsByTagName('img');
            for( i=0; i < imgLazy.length; i++)
            {
                imgLazy[i]=imgLazy[i].classList.add("lazy");
            }
            window.lazyLoadOptions = {
                elements_selector: '.lazy',
            };
        </script>

    </div>

</body>
</html>