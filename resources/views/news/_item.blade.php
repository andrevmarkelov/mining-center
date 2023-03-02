<div class="b-news-item">
    <div class="b-news-item__date">{{ $item->themeDate($item->created_at) }}</div>
    <div class="b-news-item__title">
        <a href="{{ route('news.show', $item->alias) }}">{{ $item->title }}</a>
    </div>
    <div class="b-news-item__desc"></div>
</div>
