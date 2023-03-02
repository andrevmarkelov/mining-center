@extends('layouts.app')

@section('content')

    @component('components.breadcrumb')
        @slot('main_heading', false)
        @slot('title_active', $service->title)
        @slot('items', [[
            'href' => route('services'),
            'name' => __('common.services'),
            ],
        ])
    @endcomponent

    <div class="row b-data-center-view">
        <div class="col-lg-{{ $service->image ? '7' : '12' }} pr-lg-4">
            <div class="d-flex align-items-center">
                <h1 class="b-data-center-view__heading">{{ $service->title }}</h1>
            </div>

            <ul class="list-unstyled b-data-center-view__info">
                @if ($service->cities->count())
                    @foreach ($service->cities->groupBy('country_id') as $cities)
                        <li class="list-inline-item">
                            <img src="{{ asset('default/img/icons/location.svg') }}" alt="location">
                            @foreach ($cities as $city)
                                <a class="text-dark" href="{{ route('services.city', $city->alias) }}">{{ $city->title }}</a>@if (!$loop->last),&nbsp;@endif
                            @endforeach
                        </li>
                    @endforeach
                @endif
                <li class="list-inline-item">
                    <img width="18" height="18"
                        src="{{ asset('default/img/icons/' . ($service->equipment_type == 0 ? 'asic' : 'gpu') . '.svg') }}"
                        alt="equipment type">
                    {{ __('common.equipment_type')[$service->equipment_type] }}
                </li>
            </ul>

            @if (strip_tags($service->description))
                <div class="b-data-center-view__desc b-description">{!! $service->description !!}</div>
            @endif

            <div class="b-data-center-view__bottom">
                @if (!empty($contacts = $service->contacts))
                    <ul class="list-unstyled b-data-center-view__contact">
                        @if (!empty($contacts['email']))
                            <li><a href="mailto:{{ $contacts['email'] }}">{{ $contacts['email'] }}</a></li>
                        @endif
                        @if (!empty($contacts['telegram']))
                            @php
                                $telegram = parse_url($contacts['telegram'])['path'] ?? '';
                                $telegram = ltrim($telegram, '/');
                            @endphp
                            <li>Telegram: <a rel="nofollow" href="https://t.me/{{ $telegram }}">{{ $telegram }}</a></li>
                        @endif
                        @if (!empty($contacts['whatsapp']))
                            <li>Whatsapp: <a rel="nofollow" href="{{ $contacts['whatsapp'] }}">{{ $contacts['whatsapp'] }}</a></li>
                        @endif
                        @if (!empty($contacts['phone']))
                            <li><a
                                    href="tel:{{ preg_replace('/\s/', '', $contacts['phone']) }}">{{ $contacts['phone'] }}</a>
                            </li>
                        @endif
                    </ul>
                @endif
            </div>
        </div>
        @if ($service->image)
            <div class="col-lg-5">
                <figure class="b-data-center-view__img-wrap">
                    <picture>
                        <source media="(min-width: 992px)" srcset="{{ ImageService::dataCenterImage($service->image) }}">
                        <img class="w-100" src="{{ ImageService::optimize($service->image, 710, 340) }}"
                            alt="{{ $service->title }}">
                    </picture>
                </figure>
            </div>
        @endif
    </div>

    @include('inc.promotion_x')

    @if ($related->count())
        <div class="b-heading text-left pt-4 text-primary">@lang('services.related')</div>
        <div class="owl-carousel owl-theme b-data-center js-related">
            @foreach ($related as $item)
                <div>
                    @include('services._item')
                </div>
            @endforeach
        </div>
    @endif

@endsection

@section('script')
    <script type="module">
        /* js-related */
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
                    1200: {
                        items: 3
                    }
                }
            });
        }
    </script>
@endsection
