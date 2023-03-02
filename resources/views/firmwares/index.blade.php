@extends('layouts.app')

@section('content')

    @component('components.breadcrumb')
        @if (Route::is('firmwares.category'))
            @slot('title', $category->meta_h1 ?: $category->title)
            @slot('subtitle', $category->subtitle)
            @slot('title_active', $category->title)
            @slot('items', [[
                'href' => route('firmwares'),
                'name' => __('common.firmwares'),
                ],
            ])
        @else
            @slot('title_active', __('common.firmwares'))
        @endif

        @php $top_text = setting(app()->getLocale() . '.firmwares_top_text'); @endphp
        @if (strip_tags($top_text))
            <div class="mb-4">{!! $top_text !!}</div>
        @endif
        <a href="#send-firmware" class="btn btn-primary d-block d-md-inline-block px-4">@lang('firmwares.send_firmware')</a>
    @endcomponent

    @if (Route::is('firmwares.category'))
        <div class="b-heading mt-5">@lang('firmwares.advantages')</div>
        @php
            $advantages_desc = setting(app()->getLocale() . '.firmwares_advantages_desc');
        @endphp
        @if (strip_tags($advantages_desc))
            <div class="row justify-content-center mb-4">
                <div class="col-xl-8 col-xxl-6">
                    <p>{{ $advantages_desc }}</p>
                </div>
            </div>
        @endif

        @php $lang = app()->getLocale(); @endphp
        <div class="row mt-0 mt-md-5 b-advantages js-mobile-carousel">
            <div class="col-md-6 col-xl-4" data-col="col-md-6 col-xl-4">
                <div class="b-advantages__item">
                    <figure class="b-advantages__img-wrap">
                        <img src="{{ asset('default/img/icons/advantage1.svg') }}" alt="">
                    </figure>
                    <p>{{ setting($lang . '.firmwares_advantage1_text') }}</p>
                </div>
            </div>
            <div class="col-md-6 col-xl-4" data-col="col-md-6 col-xl-4">
                <div class="b-advantages__item">
                    <figure class="b-advantages__img-wrap">
                        <img src="{{ asset('default/img/icons/advantage2.svg') }}" alt="">
                    </figure>
                    <p>{{ setting($lang . '.firmwares_advantage2_text') }}</p>
                </div>
            </div>
            <div class="col-md-6 col-xl-4" data-col="col-md-6 col-xl-4">
                <div class="b-advantages__item">
                    <figure class="b-advantages__img-wrap">
                        <img src="{{ asset('default/img/icons/advantage3.svg') }}" alt="">
                    </figure>
                    <p>{{ setting($lang . '.firmwares_advantage3_text') }}</p>
                </div>
            </div>
            <div class="col-md-6 col-xl-4" data-col="col-md-6 col-xl-4">
                <div class="b-advantages__item">
                    <figure class="b-advantages__img-wrap">
                        <img src="{{ asset('default/img/icons/advantage4.svg') }}" alt="">
                    </figure>
                    <p>{{ setting($lang . '.firmwares_advantage4_text') }}</p>
                </div>
            </div>
            <div class="col-md-6 col-xl-4" data-col="col-md-6 col-xl-4">
                <div class="b-advantages__item">
                    <figure class="b-advantages__img-wrap">
                        <img src="{{ asset('default/img/icons/advantage5.svg') }}" alt="">
                    </figure>
                    <p>{{ setting($lang . '.firmwares_advantage5_text') }}</p>
                </div>
            </div>
            <div class="col-md-6 col-xl-4" data-col="col-md-6 col-xl-4">
                <div class="b-advantages__item">
                    <figure class="b-advantages__img-wrap">
                        <img src="{{ asset('default/img/icons/advantage6.svg') }}" alt="">
                    </figure>
                    <p>{{ setting($lang . '.firmwares_advantage6_text') }}</p>
                </div>
            </div>
        </div>
    @else
        <div class="mt-5 row justify-content-center b-firmware-category d-none">
            @foreach ($categories as $item)
                <div class="col-md-6 col-lg-4 col-xl-3">
                    <div class="b-firmware-category__item">
                        <a class="b-firmware-category__img-wrap" href="{{ route('firmwares.category', $item->alias) }}">
                            <img src="{{ $item->thumb }}" alt="{{ $item->title }}">
                        </a>
                        <div class="b-firmware-category__title">
                            <a href="{{ route('firmwares.category', $item->alias) }}">{{ $item->title }}</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    <div class="b-heading text-center mt-5" style="margin-bottom: 23px;">@lang('firmwares.for_mining') <span class="text-primary d-block">Bitmain ASICs</span></div>
    @php $bottom_text = setting(app()->getLocale() . '.firmwares_bottom_text'); @endphp
    @if (strip_tags($bottom_text))
        {!! $bottom_text !!}
    @endif

    @if ($firmwares->count())
        <div class="row b-firmware">
            @foreach ($firmwares as $item)
                <div class="col-xl-6">
                    @include('firmwares._item')
                </div>
            @endforeach
        </div>
    @endif

    @if ($firmwares->hasPages())
        <div class="mt-5" style="overflow: auto;">
            {{ $firmwares->links() }}
        </div>
    @endif

    @include('inc.promotion_x')

    @component('components.page_description')
        @if (Route::is('firmwares.category'))
            @slot('description', $category->description)
        @endif
    @endcomponent

    <div id="send-firmware" class="mt-5"></div>

    <form id="firmware-form" action="{{ route('firmwares.send') }}" method="post" class="b-theme-form">
        @csrf
        <div class="b-heading text-center mt-5">@lang('firmwares.form_heading')</div>
        <p class="form-group b-theme-form__text">@lang('firmwares.form_note')</p>
        <div class="b-theme-form__inner">
            <div class="form-group">
                <input placeholder="@lang('common.name')*" type="text" name="name" class="form-control" id="input-name">
            </div>
            <div class="form-group">
                <input placeholder="@lang('common.email')*" type="email" name="email" class="form-control" id="input-email">
            </div>
            <div class="form-group">
                <input placeholder="Telegram / Whatsapp" type="text" name="telegram" class="form-control"
                    id="input-telegram">
            </div>
            <div class="text-center">
                {!! NoCaptcha::renderJs() !!}
                <button class="btn btn-primary btn-lg b-theme-form__btn g-recaptcha"
                    data-callback="firmwareForm"
                    data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"
                >@lang('firmwares.send_me')</button>
            </div>
        </div>
    </form>

@endsection
