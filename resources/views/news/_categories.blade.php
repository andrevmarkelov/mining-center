@php
    $img_width = [
        'reviews' => 'width="14" height="13"',
        'reports' => 'width="14" height="13"',
        'legal' => 'width="13" height="17"',
        'events' => 'width="17" height="13"',
        'people' => 'width="13" height="13"',
    ];
@endphp
<nav class="nav b-news-category">
    <a class="nav-link order-first" href="{{ route('news') }}">
        <img width="13" height="18" src="{{ asset('default/img/icons/news.svg') }}" alt="@lang('common.news')">
        <span>@lang('common.news')</span>
    </a>
    @foreach (setting('news_categories', []) as $key => $category_id)
        @if (!empty(($item = $news_categories->find($category_id))))
            @php
                $add_order = $loop->iteration;

                if ($key == 'reviews') {
                    $add_order = '2';
                }
                if ($key == 'reports') {
                    $add_order = '1';
                }

                $order = "order-lg-$loop->iteration order-$add_order";
            @endphp
            <span class="b-news-category__divider {{ $order }} {{ $loop->iteration % 3 == 0 ? 'd-none d-lg-block' : '' }}"></span>
            <a class="nav-link text-truncate {{ $order }} {{ $loop->iteration % 2 == 0 ? 'b-news-category__bigger-width' : '' }}"
                href="{{ route('news.category', $item->alias) }}">
                <img {!! $img_width[$key] !!} src="{{ asset('default/img/icons/' . $key . '.svg') }}"
                    alt="{{ $item->title }}">
                <span>@lang('news.' . $key)</span>
            </a>
        @endif
    @endforeach
</nav>
