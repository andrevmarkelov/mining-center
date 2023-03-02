<a href="{{ route('news.show', $item->alias) }}" class="b-news-main-item {{ $class ?? '' }}">
    <img class="b-news-main-item__img" src="{{ ImageService::optimize($item->image, 406, 283) }}"
        alt="{{ $item->title }}">
    <div class="b-news-main-item__title">{{ $item->title }}</div>
</a>
