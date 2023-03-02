{{-- START News --}}
<div class="row">
    @if ($news_hot_topic)
        <div class="col-md-12 col-lg-40 mb-4 mb-lg-0">
            <a href="{{ route('news.show', $news_hot_topic->alias) }}" class="b-news-topic-item">
                <img not-lazy class="b-news-topic-item__img" src="{{ ImageService::optimize($news_hot_topic->image, 491, 408) }}"
                    alt="{{ $news_hot_topic->title }}">
                <div class="b-news-topic-item__label">
                    <img width="14" height="20" src="{{ asset('default/img/icons/hot-topic.svg') }}" alt="hot topic">
                    @lang('news.hot_topic')
                </div>
                <div class="b-news-topic-item__text">
                    <div class="b-news-topic-item__date text-uppercase">{{ $news_hot_topic->themeDate($news_hot_topic->created_at) }}</div>
                    <div class="b-news-topic-item__title">{{ $news_hot_topic->title }}</div>
                </div>
            </a>
        </div>
    @endif
    @if ($news->count())
        <div class="col col-lg-60 pl-lg-3">
            <div class="row js-identical-height">
                {{-- <div class="col order-lg-last">
                    <a href="#" class="b-news-base-item d-block p-0">
                        <img class="w-100 h-100" src="{{ asset('default/img/promotion/363x100.png') }}"
                            alt="">
                    </a>
                    @if ($item_5 = $news->get(4))
                        @include('news._item_base', ['item' => $item_5])
                    @endif
                    <a href="#" class="b-news-base-item d-block p-0">
                        <img class="w-100 h-100" src="{{ asset('default/img/promotion/363x100-green.png') }}"
                            alt="">
                    </a>
                    @if ($item_6 = $news->get(5))
                        @include('news._item_base', ['item' => $item_6])
                    @endif
                </div> --}}
                <div class="col">
                    @foreach ($news->slice(0, 4) as $item)
                        @include('news._item_base')
                    @endforeach
                </div>
                <div class="col d-none d-md-block">
                    @foreach ($news->slice(4, 8) as $item)
                        @include('news._item_base')
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

@if ($news->count())
    <div class="text-center d-md-none mt-3">
        <a href="{{ route('news') }}" class="btn btn-outline-primary px-4">@lang('home.view_all_news')</a>
    </div>
@endif
{{-- END News --}}
