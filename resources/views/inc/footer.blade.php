<footer class="b-footer">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md">
                <a href="{{ route('home') }}">
                    <img class="b-footer__logo" width="190" height="28"
                        src="{{ ImageService::optimize('default/img/logo.svg') }}" alt="{{ config('app.name') }}">
                </a>
                <div class="b-footer__text">
                    @lang('common.contact_us')
                    @if ($email = setting('email'))
                        <strong><a href="mailto:{{ $email }}">{{ $email }}</a></strong>
                    @endif
                </div>
            </div>
            <div class="col-6 col-md-3 col-lg-2">
                <ul class="list-unstyled b-footer__links">
                    <li>
                        <a href="{{ route('home') }}">@lang('common.home')</a>
                    </li>
                    <li>
                        <a href="{{ route('news') }}">@lang('common.news')</a>
                    </li>
                    <li>
                        <a href="{{ route('mining') }}">@lang('common.mining')</a>
                    </li>
                    <li>
                        <a href="{{ route('firmwares') }}">@lang('common.firmwares')</a>
                    </li>
                    <li>
                        <a href="{{ route('data_centers') }}">@lang('common.data_centers')</a>
                    </li>
                    {{-- <li>
                        <a href="{{ route('services') }}">@lang('common.services')</a>
                    </li> --}}
                    {{-- <li>
                        <a href="{{ route('contacts') }}">@lang('common.contacts')</a>
                    </li> --}}
                </ul>
            </div>
            <div class="col-6 col-md-3 col-lg-auto">
                <ul class="list-unstyled b-footer__links">
                    <li>
                        <a href="{{ route('crypto_calc') }}">@lang('common.crypto_calc')</a>
                    </li>
                    <li>
                        <a href="{{ route('pages.show_short_url', 'crypto-converter') }}">@lang('common.crypto_converter')</a>
                    </li>
                    <li>
                        <a href="{{ route('pages.show_short_url', 'hashrate-converter') }}">@lang('common.hashrate')</a>
                    </li>
                    <li>
                        <a href="{{ route('equipments') }}">@lang('common.equipments')</a>
                    </li>
                    {{-- <li>
                        <a href="{{ route('wiki') }}">Wiki</a>
                    </li> --}}
                    <li>
                        <a href="{{ route('pages.show', 'about') }}">@lang('common.about')</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="b-footer__bot-box">
            <div class="row align-items-center justify-content-center">
                <div class="col">
                    <strong>© {{ date('Y') }} {{ config('app.name') }}</strong>
                </div>
                <div class="col-12 col-md">
                    @include('inc.social-network')
                </div>
            </div>
        </div>
    </div>
</footer>

