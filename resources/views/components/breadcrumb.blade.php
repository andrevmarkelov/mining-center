{{-- $page_title, $page_subtitle глобальна змінна з "App\View\Composers\PageComposer" --}}
<div class="b-page-header @if (Route::is(['news.show', 'wiki.show'])) mb-0 @endif">
    @php
        $without_heading = Route::is('*.show') && !Route::is(['ratings.show']);
    @endphp

    <ol class="breadcrumb" itemscope itemtype="https://schema.org/BreadcrumbList">
        <li class="breadcrumb-item">
            <a href="{{ route('home') }}">@lang('common.home')</a>
        </li>
        @php $position = 1; @endphp
        @isset($items)
            @foreach ($items as $item)
                <li class="breadcrumb-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
                    <a href="{{ $item['href'] }}" itemprop="item">{{ $item['name'] }}
                        <meta itemprop="name" content="{{ $item['name'] }}">
                        <meta itemprop="position" content="{{ $position++ }}">
                    </a>
                </li>
            @endforeach
        @endisset
        @unless(isset($title_active) && $title_active === false)
            <li class="breadcrumb-item active" itemprop="itemListElement" itemscope
                itemtype="http://schema.org/ListItem">
                <a href="{{ url()->current() }}"
                    itemprop="item">{{ $title_active = $title_active ?? ($title ?? ($page_title ?? '')) }}
                    <meta itemprop="name" content="{{ $title_active }}">
                    <meta itemprop="position" content="{{ $position }}">
                </a>
            </li>
        @endunless
    </ol>

    @if (!$without_heading)
        @php $main_heading = isset($main_heading) ? 'div' : 'h1'; @endphp
        <div class="row">
            <div class="col-md-8 col-lg-7 col-xxl-6">
                <{{ $main_heading }} class="b-page-header__title">
                    @if (isset($title) && $title)
                        {!! $title !!}
                    @elseif(isset($page_title) && $page_title)
                        {!! $page_title !!}
                    @endif
                </{{ $main_heading }}>
                {{-- <p class="b-page-header__desc">
                    @if (isset($subtitle) && strip_tags($subtitle))
                        {!! strip_tags($subtitle, '<strong><b><em><i>') !!}
                    @elseif(isset($page_subtitle) && strip_tags($page_subtitle))
                        {!! strip_tags($page_subtitle, '<strong><b><em><i>') !!}
                    @endif
                </p> --}}

                {{ $slot }}
            </div>
        </div>
    @endif
</div>
