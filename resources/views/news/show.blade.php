@extends('layouts.app')

@section('content')

    <div class="row">
        <main class="col-lg-8 b-news-view">
            @component('components.breadcrumb')
                @slot('main_heading', false)
                @slot('title_active', $news->title)
                @slot('items', [[
                    'href' => route('news'),
                    'name' => __('common.news'),
                ]])
            @endcomponent

            <div class="d-flex align-items-center">
                <h1 class="b-news-view__heading">{{ $news->title }}</h1>
            </div>

            <div class="d-flex flex-wrap align-items-end justify-content-between mb-4">
                @if ($news->categories->count())
                    <ul class="list-inline b-news-view__categories order-last">
                        @foreach ($news->categories as $item)
                            <li class="list-inline-item">
                                <a href="{{ route('news.category', $item->alias) }}">{{ $item->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                @endif

                @include('inc.author', ['object' => $news])
            </div>

            @if ($news->image)
                <figure class="b-news-view__img-wrap mb-4">
                    <img src="{{ ImageService::newsImage($news->image) }}" alt="{{ $news->title }}">
                </figure>
            @endif

            <link href="/vendor/laraberg/css/laraberg.css" rel="stylesheet preload" as="style">
            <link href="/default/css/laraberg.css" rel="stylesheet preload" as="style">

            @if (strip_tags($news->description))
                <div class="b-news-view__desc b-description">
                    {!! preg_replace('#(<img[^>]+) style=".*?"#i', '$1', $news->render('description')) !!}
                </div>
            @endif

            @include('inc.share')

            @if ($related->count())
                <div class="b-heading">@lang('news.related_news')</div>
                @if ($items_0_2 = $related->slice(0, 2))
                    <div class="row js-mobile-carousel-md">
                        @foreach ($items_0_2 as $item)
                            <div class="col-lg-6" data-col="col-lg-6">
                                @include('news._item_main')
                            </div>
                        @endforeach
                    </div>
                @endif

                @if ($items_2_6 = $related->slice(2, 6))
                    <div class="row js-identical-height">
                        @foreach ($items_2_6->chunk(3) as $tree)
                            <div class="col-md-6 {{ !$loop->first ? 'd-none d-md-block' : '' }}">
                                @foreach ($tree as $item)
                                    @include('news._item_short')
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                @endif

                <div class="text-center d-lg-none mt-4">
                    <a href="{{ route('news.category', $news->categories->first()->alias) }}" class="btn btn-outline-primary px-4">@lang('home.view_all_news')</a>
                </div>
            @endif
        </main>

        <aside class="col-lg-4">
            <div class="sticky-top b-aside-right">
                @if ($popular->count())
                    <div class="b-aside-more-in">
                        <div class="b-aside-more-in__heading">
                            @lang('news.more_in') «{{ $news->categories->first()->title }}»
                        </div>

                        @include('news._item_main', ['item' => $related->first()])

                        <div class="row js-identical-height" data-type="mobile">
                            {{-- @php $index = 0; @endphp --}}
                            @foreach ($popular->slice(1, 7)->chunk(3) as $tree)
                                <div class="col-md-6 col-lg-12 {{ !$loop->first ? 'd-none d-md-block' : '' }}">
                                    @if (!$loop->first) <hr class="m-0"> @endif
                                    @foreach ($tree as $item)
                                        {{-- @if ($index == 1)
                                            <a href="#" class="b-news-short-item d-block p-0"">
                                                <img class="w-100" src="{{ asset('default/img/promotion/307x78.png') }}" alt="">
                                            </a>
                                        @endif --}}
                                        @include('news._item_short')
                                        {{-- @if ($index == 5)
                                            <a href="#" class="b-news-short-item d-block p-0"">
                                                <img class="w-100" src="{{ asset('default/img/promotion/307x78.png') }}" alt="">
                                            </a>
                                        @endif
                                        @php $index++ @endphp --}}
                                    @endforeach
                                </div>
                            @endforeach
                        </div>

                        <div class="text-center d-lg-none mt-4">
                            <a href="{{ route('news.category', $news->categories->first()->alias) }}" class="btn btn-outline-primary px-4">@lang('home.view_all_news')</a>
                        </div>
                    </div>
                @endif

                @include('inc.subscribe_v2')
            </div>
        </aside>
    </div>

@endsection

@section('script')
    <script type="module">
        // wp-block-gallery
        $(".wp-block-gallery").each(function () {
            let $current = $(this).html(),
                $thumb = $(this).clone();

            $thumb.find("figcaption").remove();

            let galleryTmp = `
                <div class="b-equipment-slide" style="max-width: inherit;">
                    <div class="b-equipment-slide__gallery js-equipment-gallery">${$current}</div>
                    <div class="b-equipment-slide__thumbs">
                        <div class="b-equipment-slide__thumbs-inner js-equipment-thumbs">${$thumb.html()}</div>
                    </div>
                </div>`;

            $(this).html(galleryTmp);
        });
    </script>
@endsection
