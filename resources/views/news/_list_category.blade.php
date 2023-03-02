@foreach ($news as $item)
    @if ($loop->first && $news->currentPage() == 1)
        <div class="b-news-first-item">
            <figure class="b-news-first-item__img-wrap">
                <a href="{{ route('news.show', $item->alias) }}">
                    <img class="b-news-first-item__img" src="{{ ImageService::optimize($item->image, 392, 299) }}" alt="{{ $item->title }}">
                </a>
            </figure>
            <div class="b-news-first-item__date">{{ $item->themeDate($item->created_at) }}</div>
            <div class="b-news-first-item__title">
                <a href="{{ route('news.show', $item->alias) }}">{{ $item->title }}</a>
            </div>
            <div class="b-news-first-item__desc">
                {{ mb_strimwidth(strip_tags($item->description), 0, 148, '...') }}
            </div>
        </div>
    @else
        <div class="b-news-item">
            <div class="b-news-item__date">{{ $item->themeDate($item->created_at) }}</div>
            <div class="b-news-item__title">
                <a href="{{ route('news.show', $item->alias) }}">{{ $item->title }}</a>
            </div>
            <div class="b-news-item__desc">
                {{ mb_strimwidth(strip_tags($item->description), 0, 148, '...') }}
            </div>
        </div>
    @endif
@endforeach
