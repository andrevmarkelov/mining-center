{{-- START b-header --}}
<header class="b-header">
    <div class="container">
        <div class="navbar navbar-expand-xl navbar-light">

            <a href="{{ route('home') }}" class="navbar-brand">
                <img width="190" height="28" src="{{ asset('default/img/logo.svg') }}"
                    alt="{{ config('app.name') }}">
            </a>

            @if ($btc_halving)
                <a href="{{ route('news.category', $btc_halving->alias) }}"
                    class="btn btn-primary btn-sm d-none d-md-block b-header__news-category">
                    <img width="14" height="20" src="{{ asset('default/img/icons/hot-topic.svg') }}" alt="{{ $btc_halving->title }}">
                    <span>@lang('news.btc_news')</span>
                </a>
            @endif

            <nav class="collapse navbar-collapse b-main-nav" id="main-nav">
                <ul class="navbar-nav">
                    <li class="nav-item @routeActive('news')">
                        <a class="nav-link" href="{{ route('news') }}">@lang('common.news')</a>
                    </li>
                    @if (isset($coins_nav) && $coins_nav->count())
                        <li class="nav-item dropdown dropdown-hovered">
                            <a class="nav-link dropdown-toggle" href="#" data-toggle="dropdown">@lang('common.ratings')</a>
                            <div class="dropdown-menu">
                                <div class="js-scrollbar" style="max-height: 320px;">
                                    @foreach ($coins_nav as $item)
                                        <a class="dropdown-item"
                                            href="{{ route('ratings.show', $item->alias) }}">{{ $item->title }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </li>
                    @endif
                    <li class="nav-item @routeActive('data_centers*')">
                        <a class="nav-link" href="{{ route('data_centers') }}">@lang('common.data_centers')</a>
                    </li>
                    <li class="nav-item @routeActive('equipments*')">
                        <a class="nav-link" href="{{ route('equipments') }}">@lang('common.equipments')</a>
                    </li>
                    {{-- @if (app()->getLocale() == 'en')
                        <li class="nav-item @routeActive('home')">
                            <a class="nav-link" href="{{ route('home') }}">@lang('common.home')</a>
                        </li>
                    @endif
                    <li class="nav-item @routeActive('mining')">
                        <a class="nav-link" href="{{ route('mining') }}">@lang('common.mining')</a>
                    </li>
                    @if (isset($firmwares_nav) && $firmwares_nav->count())
                        <li class="nav-item dropdown dropdown-hovered">
                            <a class="nav-link dropdown-toggle" href="{{ route('firmwares') }}">@lang('common.firmwares')</a>
                            <div class="dropdown-menu">
                                <div class="js-scrollbar" style="max-height: 320px;">
                                    @foreach ($firmwares_nav as $item)
                                        <a class="dropdown-item"
                                            href="{{ route('firmwares.category', $item->alias) }}">{{ $item->title }}</a>
                                    @endforeach
                                </div>
                            </div>
                        </li>
                    @endif
                    <li class="nav-item @routeActive('wiki')">
                        <a class="nav-link" href="{{ route('wiki') }}">WIKI</a>
                    </li> --}}
                </ul>
            </nav>

            @include('inc.change_lang')

            <button class="navbar-toggler b-navbar-toggler" data-toggle="collapse" data-target="#main-nav" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>

        </div>

        @if ($btc_halving)
            <a href="{{ route('news.category', $btc_halving->alias) }}"
                class="btn btn-primary btn-sm b-header__news-category d-md-none">
                <img width="14" height="20" src="{{ asset('default/img/icons/hot-topic.svg') }}" alt="{{ $btc_halving->title }}">
                <span>@lang('news.btc_news')</span>
            </a>
        @endif

        <x-price-schedule></x-price-schedule>
    </div>
</header>
{{-- END b-header --}}
