@extends('layouts.app')

@section('content')

    @component('components.breadcrumb')
        @slot('main_heading', false)
        @slot('title_active', $data_center->title)
        @slot('items', [[
            'href' => route('data_centers'),
            'name' => __('common.data_centers'),
            ],
        ])
    @endcomponent

    <div class="row b-data-center-view">
        <div class="col-lg-{{ $data_center->image ? '7' : '12' }} pr-lg-4">
            <div class="d-flex align-items-center">
                <h1 class="b-data-center-view__heading">{{ $data_center->title }}</h1>
                @if ($data_center->getMeta('is_partner'))
                    <img src="{{ asset('default/img/icons/bookmark.svg') }}"
                        width="40" height="40"
                        class="mb-3 ml-2" data-toggle="tooltip" data-placement="top" title="@lang('data_centers.is_partner')"
                        alt="partner">
                @endif
            </div>

            <ul class="list-unstyled b-data-center-view__info">
                @if ($data_center->cities->count())
                    @foreach ($data_center->cities->groupBy('country_id') as $cities)
                        <li class="list-inline-item">
                            <img src="{{ asset('default/img/icons/location.svg') }}" alt="location">
                            @foreach ($cities as $city)
                                <a class="text-dark" href="{{ route('data_centers.city', $city->alias) }}">{{ $city->title }}</a>@if (!$loop->last),&nbsp;@endif
                            @endforeach
                        </li>
                    @endforeach
                @endif
                <li class="list-inline-item">
                    <img src="{{ asset('default/img/icons/' . ($data_center->power_type == 0 ? 'type' : 'hydropower') . '.svg') }}"
                        alt="power type">
                    {{ __('common.power_type')[$data_center->power_type] }}
                </li>
            </ul>

            @if ($data_center->action_text)
                <div class="alert alert-success font-weight-bold mb-4" style="margin-top: -5px;">⇒ {{ $data_center->action_text }}</div>
            @endif

            @if (strip_tags($data_center->description))
                <div class="b-data-center-view__desc b-description">{!! $data_center->description !!}</div>
            @endif

            <div class="b-data-center-view__bottom">
                <div class="row">
                    <div class="col-md-7 col-lg-12 col-xxl-7">
                        @if (strip_tags($data_center->add_description))
                            <div class="b-data-center-view__attr">{!! $data_center->add_description !!}</div>
                        @endif
                    </div>
                    @if ($data_center->getMeta('show_contacts', true))
                        <div class="col-md-5 col-lg-12 col-xxl-5">
                            <ul class="list-unstyled b-data-center-view__contact">
                                @php
                                    /** @var object $data_center */
                                    $contacts = $data_center->getMeta('contacts');
                                @endphp
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
                        </div>
                    @endif
                </div>
            </div>

            <button class="btn btn-primary b-data-center-view__btn" data-id="{{ $data_center->id }}" data-form-type="data_centers"
                data-toggle="modal" data-target="#modal-equipment-order">@lang('common.find_cost')</button>
        </div>
        @if ($data_center->image)
            <div class="col-lg-5">
                <figure class="b-data-center-view__img-wrap">
                    <picture>
                        <source media="(min-width: 992px)" srcset="{{ ImageService::dataCenterImage($data_center->image) }}">
                        <img class="w-100" src="{{ ImageService::optimize($data_center->image, 710, 340) }}"
                            alt="{{ $data_center->title }}">
                    </picture>
                </figure>
            </div>
        @endif
    </div>

    @include('inc.promotion_x')

    @include('inc.equipment_order')

    @if ($related->count())
        <div class="b-heading text-left pt-4 text-primary">@lang('data_centers.related')</div>
        <div class="owl-carousel owl-theme b-data-center js-related">
            @foreach ($related as $item)
                <div>
                    @include('data_centers._item')
                </div>
            @endforeach
        </div>
    @endif

	<div class="b-support-btn js-support-btn"
        data-toggle="popover"
        data-placement="top"
        data-container="body"
        data-title='@lang('data_centers.support_title') <a href="#" class="close" data-dismiss="alert">&times;</a>'
        data-content='
            <form id="data-center-support" action="{{ route('data_centers.support') }}" method="post">
                @csrf
                <p class="font-weight-bold">@lang('data_centers.support_text')</p>
                <div class="mb-3">
                    <input placeholder="@lang('common.name')" type="text" name="name" class="form-control">
                </div>
                <div class="mb-3">
                    <input placeholder="@lang('common.email')*" type="email" name="email" class="form-control">
                </div>
                <div class="mb-4">
                    <input placeholder="Telegram / Whatsapp" type="text" name="telegram" class="form-control">
                </div>
				{!! NoCaptcha::renderJs() !!}
				<button class="btn btn-primary btn-block g-recaptcha"
					data-callback="dataCenterSupport"
					data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"
				>@lang('common.send')</button>
            </form>'>@lang('data_centers.support_action')
    </div>

@endsection

@section('script')
    <script type="module">
        /* js-support-btn */
        const $supportBtn = $(".js-support-btn");

        $supportBtn.popover({
            html: true,
            sanitize: false,
        });

        const myInterval = setTimeout(function () {
            $supportBtn.popover("show");
        }, 35000);

        $(document).on("click", ".popover .close", function () {
            clearInterval(myInterval);

            $(this).parents(".popover").popover("hide");
        });

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
                    1200: {
                        items: 2
                    }
                }
            });
        }
    </script>
@endsection
