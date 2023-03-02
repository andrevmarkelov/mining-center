<div class="b-wiki-category__item">
    <a class="b-wiki-category__img-wrap" href="{{ route('wiki.show', $item->alias) }}">
        <img class="w-100" src="{{ $item->thumb }}" alt="{{ $item->title }}">
    </a>
    <div class="b-wiki-category__text">
        <div class="b-wiki-category__date">{{ date_format($item->created_at, 'd.m.Y') }}</div>
        <div class="b-wiki-category__title">
            <a href="{{ route('wiki.show', $item->alias) }}">{{ $item->title }}</a>
        </div>
    </div>
</div>
