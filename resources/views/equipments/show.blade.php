@extends('layouts.app')

@section('content')

    @component('components.breadcrumb')
        @slot('main_heading', false)
        @slot('title_active', $equipment->title)
        @slot('items', [[
            'href' => route('equipments'),
            'name' => __('common.equipments'),
            ],
        ])
    @endcomponent

    <div class="b-equipment-view">
        <div class="row">
            @if ($equipment->image)
                <div class="col-lg-5">
                    <div data-moved-block-position-pc="1"></div>
                    <div class="b-equipment-slide" data-moved-block="1">
                        <div class="b-equipment-slide__gallery js-equipment-gallery">
                            @if ($equipment->image)
                                <div><img src="{{ ImageService::equipmentImage($equipment->image) }}"
                                        alt="{{ $equipment->title }}"></div>
                            @endif

                            @if ($equipment->gallery)
                                @foreach ($equipment->gallery as $item)
                                    <div><img src="{{ ImageService::equipmentImage($item->getUrl()) }}"
                                            alt="{{ $equipment->title }}"></div>
                                @endforeach
                            @endif
                        </div>
                        @if ($equipment->gallery)
                            <div class="b-equipment-slide__thumbs">
                                <div class="b-equipment-slide__thumbs-inner js-equipment-thumbs">
                                    @if ($equipment->image)
                                        <div><img src="{{ $equipment->thumb }}"
                                                alt="{{ $equipment->title }}"></div>
                                    @endif

                                    @if ($equipment->gallery)
                                        @foreach ($equipment->gallery as $item)
                                            <div><img src="{{ ImageService::equipmentThumb($item->getUrl()) }}"
                                                    alt="{{ $equipment->title }}"></div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
            <div class="col-lg-{{ $equipment->image ? '7' : '12' }}">
                <h1 class="b-equipment-view__heading">{{ $equipment->title }}</h1>

                <div data-moved-block-position-mobile="1"></div>

                {{-- <div class="b-equipment-view__stock-status">
                <img src="{{ asset('default/img/icons/' . ($equipment->available ? 'check' : 'uncheck') . '.svg') }}"
                    alt="">{{ __('common.available')[$equipment->available] ?? 'n/a' }}
                </div> --}}

                <ul class="list-unstyled b-equipment-view__params">
                    <li>
                        <span>@lang('equipments.coins')</span>
                        <strong><a href="{{ route('equipments.coin', $equipment->coin->alias) }}">{{ $equipment->coin->title }}</a></strong>
                    </li>
                    <li>
                        <span>@lang('equipments.algorithm')</span>
                        @php
                            $algorithm_title = $equipment->coin->algorithm->title;
                        @endphp
                        <strong><a href="{{ route('equipments', ['algorithm' => $algorithm_title]) }}">{{ $algorithm_title }}</a></strong>
                    </li>
                    <li>
                        <span>@lang('equipments.hashrate')</span>
                        <strong>{{ $equipment->hashrate }} {{ $equipment->coin->whattomine_unit }}</strong>
                    </li>
                    <li>
                        <span>@lang('equipments.power')</span>
                        <strong>{{ $equipment->power }} @lang('crypto_calc.consumption_unit')</strong>
                    </li>
                </ul>

                <div class="row text-center b-equipment-view__price-wrap">
                    {{-- <div class="b-equipment-view__price">
                        <strong class="text-primary">$</strong>{{ number_format($equipment->price, 0, '', ' ') }}
                    </div> --}}
                    <div class="col-md-6">
                        <button class="btn btn-primary btn-lg" data-id="{{ $equipment->id }}" data-toggle="modal"
                            data-target="#modal-equipment-order">@lang('equipments.find_cost')</button>
                    </div>
                    @isset ($equipment->firmware->attach)
                        <div class="col-md-6 mt-3 mt-md-0">
                            @if ($equipment->firmware->attach->count() > 1)
                                <div class="btn-group">
                                    <button class="btn btn-outline-primary btn-lg dropdown-toggle" data-toggle="dropdown">@lang('equipments.download_firmware')</button>
                                    <div class="dropdown-menu dropdown-menu-right">
                                        @foreach ($equipment->firmware->attach as $file)
                                            <a href="{{ $file->getUrl() }}" download class="dropdown-item">{{ $file->name }}</a>
                                        @endforeach
                                    </div>
                                </div>
                            @else
                                <a href="{{ $equipment->firmware->attach->first()->getUrl() }}" download
                                    class="btn btn-outline-primary btn-lg">@lang('equipments.download_firmware')</a>
                            @endif
                        </div>
                    @endisset
                </div>

                @if ($featured_pools->count())
                    <div class="b-featured-pools">
                        <div class="h3 b-featured-pools__heading">@lang('equipments.featured_pools')</div>
                        @foreach ($featured_pools as $pool)
                            <div class="b-featured-pools__item" onclick="window.open('{{ $pool->ref_link ?: $pool->link }}', '_blank');">
                                <div class="b-featured-pools__icon">
                                    @if ($pool->image)
                                        <img src="{{ ImageService::optimize($pool->image, 38, 38) }}"
                                            alt="{{ $pool->title }}">
                                    @endif
                                </div>
                                <a target="_blank" rel="nofollow" href="{{ $pool->ref_link ?: $pool->link }}" class="b-featured-pools__title">{{ $pool->title }}</a>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    @if (setting('coin_calc_show'))
        @include('crypto_calc._coin', [
            'coin' => $equipment->coin,
            'hashrate_base' => $equipment->hashrate,
            'consumption_base' => $equipment->power,
            'add_to_heading' => $equipment->title,
        ])
    @endif

    @php
        $check_desc = strip_tags($equipment->description);
        $check_add_desc = strip_tags($equipment->add_description);
    @endphp

    @if ($check_desc or $check_add_desc)
        <div class="row mt-5">
            <div class="col-lg-3">
                <div class="nav flex-column nav-pills text-uppercase font-weight-bold sticky-top" style="top: 80px;" role="tablist" aria-orientation="vertical">
                    @if ($check_desc)
                        <a class="nav-link py-3 pl-4 active" id="v-pills-description-tab" data-toggle="pill" href="#v-pills-description"
                            role="tab" aria-controls="v-pills-description" aria-selected="true">@lang('equipments.description')</a>
                    @endif
                    @if ($check_add_desc)
                        <a class="nav-link py-3 pl-4" id="v-pills-specification-tab" data-toggle="pill" href="#v-pills-specification"
                            role="tab" aria-controls="v-pills-specification" aria-selected="false">@lang('equipments.characteristics')</a>
                    @endif
                </div>
            </div>
            <div class="col-lg-9">
                <div class="tab-content pl-lg-4 pl-xl-5 mt-4 mt-lg-0 b-description">
                    @if ($check_desc)
                        <div class="tab-pane fade show active" id="v-pills-description" role="tabpanel"
                            aria-labelledby="v-pills-description-tab">
                            <div class="b-equipment-view__desc">
                                {!! $equipment->description !!}
                            </div>
                        </div>
                    @endif
                    @if ($check_add_desc)
                        <div class="tab-pane fade" id="v-pills-specification" role="tabpanel"
                            aria-labelledby="v-pills-specification-tab">
                            <div class="b-equipment-view__attr">
                                {!! $equipment->add_description !!}
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @include('inc.promotion_x')

    @include('inc.equipment_order')

    @if ($related->count())
        <div class="b-heading text-left pt-4 text-primary">@lang('equipments.related')</div>
        <div class="owl-carousel owl-theme js-related">
            @foreach ($related as $item)
                <div class="b-equipment-item">
                    <a href="{{ route('equipments.show', $item->alias) }}" class="b-equipment-item__img-wrap">
                        <img class="w-100" src="{{ $item->thumb }}" alt="{{ $item->title }}">
                    </a>
                    <div class="b-equipment-item__title">
                        <a href="{{ route('equipments.show', $item->alias) }}">{{ $item->title }}</a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

@endsection

@section('script')
    @parent

    <script type="module">
        // js-related
        const $related = $('.js-related');

        if ($related.length) {
            $related.owlCarousel({
                nav: false,
                dots: true,
                loop: true,
                margin: 10,
                responsiveClass: true,
                responsive: {
                    0: {
                        items: 1,
                    },
                    768: {
                        items: 2
                    },
                    992: {
                        items: 3
                    },
                    1200: {
                        items: 4
                    }
                }
            });
        }
    </script>
@endsection
