<!DOCTYPE html>
@php
    $lang = LaravelLocalization::getSupportedLocales()[app()->getLocale()]['regional'];
@endphp
<html lang="{{ str_replace('_', '-', $lang) }}">
<head>
    @meta_tags

    <link href="{{ asset('default/fonts/open-sans/OpenSans-Bold.woff') }}" rel="preload" as="font" type="font/woff2" crossorigin="anonymous">
    <link href="{{ asset('default/fonts/open-sans/OpenSans-ExtraBold.woff') }}" rel="preload" as="font" type="font/woff2" crossorigin="anonymous">
    <link href="{{ asset('default/fonts/open-sans/OpenSans-SemiBold.woff') }}" rel="preload" as="font" type="font/woff2" crossorigin="anonymous">
    <link href="{{ asset('default/fonts/open-sans/OpenSans-Regular.woff') }}" rel="preload" as="font" type="font/woff2" crossorigin="anonymous">

    {!! Minify::stylesheet([
            '/default/fonts/open-sans/stylesheet.css',
            '/default/fonts/montserrat/stylesheet.css',
            '/default/libs/priority-nav/priority-nav-core.css',
            '/default/css/main.css',
        ], ['rel' => 'stylesheet preload', 'as' => 'style']) !!}

    <style>{!! setting('style_head') !!}</style>
    {!! setting('code_head') !!}
</head>
<body class="{{ isset($body_white) ? 'bg-white' : '' }} {{ Route::is('home') ? 'home-page' : '' }}">

<script>
    const lang = JSON.parse('{!! json_encode([
        'more' => __('common.more'),
        'category' => __('common.category'),
    ]) !!}');
</script>

<div id="wrapper">
	@include('inc.header')
	<div id="content" class="container @if (request()->has('show_map')) pb-0 @endif">
        @yield('content')
	</div>
    @include('inc.promotion_y')
	@include('inc.footer')
</div>

@env(['APP_ENV', 'local'])
<script src="/default/libs/live.js"></script>
@endenv

{!! Minify::javascript(array_merge(
    [
        '/default/libs/jquery.min.js',
    ],
    array_column(Meta::footer()->toArray(), 'src'),
    [
        '/default/libs/bootstrap/js/popper.min.js',
        '/default/libs/bootstrap/js/bootstrap.min.js',
        '/default/libs/priority-nav/priority-nav.min.js',
        '/default/js/main.js'
    ]
), ['defer' => true]) !!}

@yield('script')

@include('inc.messages')

<script>
    const busCache = {
        'timeInterval': false,
        'status': false,
        'start': function(busAppSetting) {
            if (busCache.status === false) {
                busCache.status = true;
                document.removeEventListener('DOMContentLoaded', busCache.start, {capture:true, passive:true});
                window.removeEventListener('pagehide', busCache.start, {capture:true, passive:true});
                window.removeEventListener('scroll', busCache.start, {capture:true, passive:true});
                window.removeEventListener('mouseover', busCache.start, {capture:true, passive:true});
                window.removeEventListener('touchstart', busCache.start, {capture:true, passive:true});
            }

            if (typeof window.CustomEvent !== 'function') {
                window.CustomEvent = function(event, params) {
                    params = params || {
                        bubbles: false,
                        cancelable: false,
                        detail: null
                    };

                    let evt = document.createEvent('CustomEvent');
                    evt.initCustomEvent(event, params.bubbles, params.cancelable, params.detail);

                    return evt;
                };
            }

            let element = new CustomEvent('busCache', {
                bubbles: true
            });

            document.dispatchEvent(element);
        }
    };

    let styles = [
        '/admin/libs/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css'
    ];

    let scripts = [
        '/default/libs/smooth-scrollbar/smooth-scrollbar.js',
        '/admin/libs/sweetalert2/sweetalert2.min.js',
        '/default/libs/particles/particles.min.js',
    ];
</script>

@yield('busCacheInline')

<script>
    const busCacheInline = function() {
        let link = document.getElementsByTagName('link')[0];

        styles.forEach(function (value, index) {
            let s = document.createElement('link');
                s.href = value;
                s.rel = 'stylesheet preload';
                s.as = 'style';
                link.parentNode.insertBefore(s, link);
        });

        let script = document.getElementsByTagName('script')[0];

        scripts.forEach(function (value, index) {
            let s = document.createElement('script');
                s.src = value;
                s.async = true;
                script.parentNode.insertBefore(s, script);
        });
    };

    window.addEventListener('load', function() {
        window.addEventListener('pagehide', busCache.start, {once:true, passive:true});
        window.addEventListener('scroll', busCache.start, {once:true, passive:true});
        window.addEventListener('mouseover', busCache.start, {once:true, passive:true});
        window.addEventListener('touchstart', busCache.start, {once:true, passive:true});
        setTimeout(busCache.start, 3000);
    }, {once:true, passive:true});

    if ('busCache' in window) {
        window.addEventListener('busCache', function() {
            busCacheInline();
        });
    } else {
        busCacheInline();
    }

    @if (!empty(file_get_contents(public_path('default/libs/particles/particles.json'))))
    window.addEventListener("busCache", function () {
        particlesJS.load("wrapper", "/default/libs/particles/particles.json");
    });
    @endif
</script>

{!! setting(app()->getLocale() . '.code_footer') !!}

</body>
</html>
