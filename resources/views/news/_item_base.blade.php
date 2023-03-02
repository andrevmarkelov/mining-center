<div class="b-news-base-item {{ $class ?? '' }}">
    <div class="b-news-base-item__date">{{ $item->themeDate($item->created_at) }}</div>
    <div class="b-news-base-item__title">
        <a href="{{ route('news.show', $item->alias) }}">{{ $item->title }}</a>
    </div>
</div>
