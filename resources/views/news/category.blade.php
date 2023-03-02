@extends('layouts.app')

@section('content')
    @component('components.breadcrumb')
        @slot('title', $category->meta_h1 ?: $category->title)
        @slot('subtitle', $category->subtitle)
        @slot('title_active', $category->title)
        @slot('items', [[
            'href' => route('news'),
            'name' => __('common.news'),
        ]])
    @endcomponent

    <div class="row b-news-page">
        <div class="col-lg-8 b-news-page__col-first">

            @if ($news->count())
                <div class="b-news-list js-news-list" data-next-page="{{ $news->nextPageUrl() }}">
                    @include('news._list_category')
                </div>

                @if ($news->hasPages())
                    <div class="text-center">
                        <button class="btn btn-outline-primary b-news-page__show-more js-show-more">
                            <div style="width: 18px; height: 18px; display: none;" class="spinner-grow" role="status">
                                <span class="sr-only">Loading...</span>
                            </div>
                            @lang('news.show_more')
                        </button>
                    </div>
                @endif
            @else
                @lang('common.no_results')
            @endif

        </div>
        <div class="col-lg-4 b-news-page__col-last">

            <div class="sticky-top">
                @include('inc.subscribe_v2')

                {{-- START b-aside-news --}}
                <div class="row b-aside-news">
                    @if ($popular->count())
                        @foreach ($popular as $item)
                            <div class="col-md-4 col-lg-12 order-{{ $loop->last ? 4 : $loop->iteration }}">
                                <div class="b-aside-news__item {{ $loop->last ? 'pb-0 border-bottom-0' : '' }}">
                                    <figure class="b-aside-news__img-wrap">
                                        <a href="{{ route('news.show', $item->alias) }}">
                                            <img class="b-aside-news__img" src="{{ ImageService::optimize($item->image, 392, 174) }}" alt="{{ $item->title }}">
                                        </a>
                                    </figure>
                                    <div class="b-aside-news__date">{{ $item->themeDate($item->created_at) }}</div>
                                    <div class="b-aside-news__title">
                                        <a href="{{ route('news.show', $item->alias) }}">{{ $item->title }}</a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                    {{-- <a href="#" class="col-12 order-last order-lg-3 d-none d-md-block">
                        <img class="w-100" src="{{ asset('default/img/promotion/392x117.png') }}" alt="">
                    </a> --}}
                </div>
                {{-- END --}}
            </div>

        </div>
    </div>

    @component('components.page_description')
        @slot('description', $category->description)
    @endcomponent
@endsection

@section('script')
    <script src="{{ asset('default/libs/masonry.min.js') }}" defer></script>
    <script type="module">
       function masonryInit() {
            new Masonry(".js-news-list", {
                itemSelector: ".js-news-list > div",
                columnWidth: ".b-news-item",
            });
        }

        masonryInit();

        // js-show-more
        $(".js-show-more").click(function () {
            let $newsList = $(".js-news-list"),
                $loader = $(this).find(".spinner-grow"),
                pageNumber = $newsList.data("next-page");

            if (pageNumber) {
                $.ajax({
                    url: pageNumber,
                    method: "GET",
                    beforeSend: function (request) {
                        $loader.show();
                    },
                    success: function (data) {
                        $loader.hide();
                        $newsList.append(data.news).data("next-page", data.next_page);

                        masonryInit();

                        if (data.next_page == null) {
                            $(".js-show-more").hide();
                        }
                    },
                });
            }
        });
    </script>
@endsection
