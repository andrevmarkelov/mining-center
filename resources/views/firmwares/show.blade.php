@extends('layouts.app', ['body_white' => true])

@section('content')

    @component('components.breadcrumb')
        @slot('main_heading', false)
        @slot('title_active', $firmware->title)
        @slot('items', [
            [
            'href' => route('firmwares'),
            'name' => __('common.firmwares'),
            ],
        ])
    @endcomponent

    <div class="row b-firmware-view">
        <div class="col-lg-{{ $firmware->image ? '6' : '12' }}">
            <h1 class="b-firmware-view__heading">{{ $firmware->title }}</h1>

            <div class="float-none float-md-right" data-moved-block-position-mobile="1"></div>

            @if (strip_tags($firmware->description))
                <div class="b-firmware-view__desc b-description">
                    {!! $firmware->description !!}
                </div>
            @endif

            @if (strip_tags($firmware->add_description))
                <div class="b-firmware-view__attr">
                    {!! $firmware->add_description !!}
                </div>
            @endif

            @if ($firmware->attach)
                @if ($firmware->attach->count() > 1)
                    <div class="btn-group b-firmware-view__btn">
                        <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">@lang('firmwares.download')</button>
                        <div class="dropdown-menu">
                            @foreach ($firmware->attach as $file)
                                <a href="{{ $file->getUrl() }}" download class="dropdown-item">{{ $file->name }}</a>
                            @endforeach
                        </div>
                    </div>
                @else
                    <a href="{{ $firmware->attach->first()->getUrl() }}" download
                        class="btn btn-primary b-firmware-view__btn">@lang('firmwares.download')</a>
                @endif
            @endif
        </div>
        @if ($firmware->image)
            <div class="col-lg-6">
                <div data-moved-block-position-pc="1"></div>
                <figure class="b-firmware-view__img-wrap" data-moved-block="1">
                    <img src="{{ ImageService::equipmentImage($firmware->image) }}"
                        alt="{{ $firmware->title }}">
                </figure>
            </div>
        @endif
    </div>

    @include('inc.promotion_x')

    @if ($related->count())
        <div class="b-heading text-left pt-4 text-primary">@lang('firmwares.related')</div>
        <div class="owl-carousel owl-theme b-firmware js-related">
            @foreach ($related as $item)
                @include('firmwares._item')
            @endforeach
        </div>
    @endif

@endsection

@section('script')
    <script type="module">
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
