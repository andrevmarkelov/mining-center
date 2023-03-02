<div class="b-news-short-item {{ $class ?? '' }}">
    <a href="{{ route('news.show', $item->alias) }}">{{ $item->title }}</a>
</div>
