@extends('layouts.app')

@section('content')

    @component('components.breadcrumb')
        @slot('main_heading', false)
        @slot('title_active', $wiki->title)
        @slot('items', [[
            'href' => route('wiki'),
            'name' => 'WIKI',
            ],
        ])
    @endcomponent

    <div class="row b-news-view">
        <div class="col-xl-11 col-xxl-10">
            <h1 class="b-news-view__heading">{{ $wiki->title }}</h1>

            <div class="d-flex flex-wrap align-items-end justify-content-between">
                @if ($wiki->category)
                    <ul class="list-inline b-news-view__categories order-last">
                        <li class="list-inline-item">
                            <a href="{{ route('wiki.category', $wiki->category->alias) }}">{{ $wiki->category->title }}</a>
                        </li>
                    </ul>
                @endif

                @include('inc.author', ['object' => $wiki])
            </div>

            @if ($wiki->image)
                <figure class="b-news-view__img-wrap my-4">
                    <img src="{{ ImageService::newsImage($wiki->image) }}" alt="{{ $wiki->title }}">
                </figure>
            @endif

            @if (strip_tags($wiki->description))
                <div class="b-news-view__desc b-description">{!! preg_replace('#(<[^>]+) style=".*?"#i', '$1', $wiki->description) !!}</div>
            @endif

            @include('inc.share')

            @include('inc.promotion_x')
        </div>
    </div>

    @if ($related->count())
        <div class="b-heading text-left pt-4 text-primary">@lang('wiki.related')</div>
        <div class="owl-carousel owl-theme b-firmware-category js-related">
            @foreach ($related as $item)
                <div>
                    @include('wiki._item')
                </div>
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
                    768: {
                        items: 2
                    },
                    // 992: {
                    //     items: 2
                    // },
                    1200: {
                        items: 3
                    }
                }
            });
        }
    </script>
@endsection
