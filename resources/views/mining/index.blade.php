@extends('layouts.app', ['body_white' => true])

@section('content')

    @component('components.breadcrumb')
        @slot('title_active', __('common.mining'))
    @endcomponent

    <div class="b-heading text-center">@lang('mining.advantages')</div>
    @php $lang = app()->getLocale(); @endphp
    <div class="row mt-0 mt-md-5 b-mining-advantages js-mobile-carousel">
        <div class="col-6 col-lg-3" data-col="col-6 col-lg-3">
            <div class="b-mining-advantages__item">
                <img src="{{ asset('default/img/icons/mining-advantage1.svg') }}"
                    alt="{{ setting($lang . '.mining_advantage1_title') }}">
                <div class="b-mining-advantages__title">{{ setting($lang . '.mining_advantage1_title') }}</div>
                <p>{{ setting($lang . '.mining_advantage1_text') }}</p>
            </div>
        </div>
        <div class="col-6 col-lg-3" data-col="col-6 col-lg-3">
            <div class="b-mining-advantages__item">
                <img src="{{ asset('default/img/icons/mining-advantage2.svg') }}"
                    alt="{{ setting($lang . '.mining_advantage2_title') }}">
                <div class="b-mining-advantages__title">{{ setting($lang . '.mining_advantage2_title') }}</div>
                <p>{{ setting($lang . '.mining_advantage2_text') }}</p>
            </div>
        </div>
        <div class="col-6 col-lg-3" data-col="col-6 col-lg-3">
            <div class="b-mining-advantages__item">
                <img src="{{ asset('default/img/icons/mining-advantage3.svg') }}"
                    alt="{{ setting($lang . '.mining_advantage3_title') }}">
                <div class="b-mining-advantages__title">{{ setting($lang . '.mining_advantage3_title') }}</div>
                <p>{{ setting($lang . '.mining_advantage3_text') }}</p>
            </div>
        </div>
        <div class="col-6 col-lg-3" data-col="col-6 col-lg-3">
            <div class="b-mining-advantages__item">
                <img src="{{ asset('default/img/icons/mining-advantage4.svg') }}"
                    alt="{{ setting($lang . '.mining_advantage4_title') }}">
                <div class="b-mining-advantages__title">{{ setting($lang . '.mining_advantage4_title') }}</div>
                <p>{{ setting($lang . '.mining_advantage4_text') }}</p>
            </div>
        </div>
    </div>

    <div class="b-how-mining-work">
        <div class="b-heading text-center mt-0" style="margin-bottom: 44px;">@lang('mining.how_work')</div>
        <div class="row no-gutters">
            <div class="col-5 col-lg">
                <div class="b-how-mining-work__item">
                    <img width="35" height="33"
                        src="{{ asset('default/img/icons/registration.svg') }}" alt="">
                    <div class="b-how-mining-work__title">{{ __('mining.how_work_steps')[0] }}</div>
                </div>
            </div>
            <div class="col-auto b-how-mining-work__line"></div>
            <div class="col-5 col-lg">
                <div class="b-how-mining-work__item">
                    <img width="35" height="34" src="{{ asset('default/img/icons/buy.svg') }}"
                        alt="">
                    <div class="b-how-mining-work__title">{{ __('mining.how_work_steps')[1] }}</div>
                </div>
            </div>
            <div class="col-auto b-how-mining-work__line d-none d-lg-block"></div>
            <div class="col-5 col-lg">
                <div class="b-how-mining-work__item">
                    <img width="35" height="34" src="{{ asset('default/img/icons/mining.svg') }}"
                        alt="">
                    <div class="b-how-mining-work__title">{{ __('mining.how_work_steps')[2] }}</div>
                </div>
            </div>
            <div class="col-auto b-how-mining-work__line"></div>
            <div class="col-5 col-lg">
                <div class="b-how-mining-work__item">
                    <img width="35" height="34" src="{{ asset('default/img/icons/money.svg') }}"
                        alt="">
                    <div class="b-how-mining-work__title">{{ __('mining.how_work_steps')[3] }}</div>
                </div>
            </div>
        </div>
    </div>

    @include('inc.promotion_x')

    <div style="margin-bottom: 40px;">
        <div class="b-heading text-center mt-5" style="margin-bottom: 8px;">@lang('mining.services')</div>
        @php $services_text = setting(app()->getLocale() . '.mining_services_text'); @endphp
        @if (strip_tags($services_text))
            <div class="text-center">{!! $services_text !!}</div>
        @endif
    </div>

    @if ($mining->count())
        <div class="row b-mining-service">
            <div class="col-lg-4 col-xxl-3">
                <div class="b-mining-service__nav-heading">@lang('mining.nav_heading')</div>
                <div class="nav flex-column nav-pills b-mining-service__nav" id="v-pills-tab" role="tablist">
                    @foreach ($mining as $item)
                        <a class="text-truncate nav-link {{ $loop->first ? 'active' : '' }}" data-toggle="pill"
                            href="#v-pills-service{{ $loop->index }}" role="tab">
                            {{ $item->title }}
                        </a>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-8 col-xxl-9">
                <div class="tab-content b-mining-service__content" id="v-pills-tab-content">
                    @foreach ($mining as $item)
                        <div class="tab-pane {{ $loop->first ? 'fade show active' : '' }}"
                            id="v-pills-service{{ $loop->index }}" role="tabpanel">
                            <div class="b-mining-service__top">
                                @if ($item->image)
                                    <img class="b-mining-service__img"
                                        src="{{ ImageService::optimize($item->image, 40, 40) }}"
                                        alt="{{ $item->title }}">
                                @endif

                                @if ($item->link)
                                    <a target="_blank" rel="nofollow" href="{{ $item->link }}"
                                        class="btn btn-outline-primary">@lang('mining.visit_site')</a>
                                @endif
                            </div>
                            <div class="b-mining-service__desc b-description">
                                @if (strip_tags($item->description))
                                    {!! $item->description !!}
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    @component('components.page_description')
    @endcomponent

@endsection
