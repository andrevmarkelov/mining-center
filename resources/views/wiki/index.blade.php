@extends('layouts.app')

@section('content')

    @component('components.breadcrumb')
        @if (Route::is('wiki.category'))
            @slot('title', $category->meta_h1 ?: $category->title)
            @slot('subtitle', $category->subtitle)
            @slot('title_active', $category->title)
            @slot('items', [
                [
                'href' => route('wiki'),
                'name' => 'WIKI',
                ],
            ])
        @else
            @slot('title_active', 'WIKI')
        @endif
    @endcomponent

    <div class="b-wiki" id="wiki">
        <div class="row">
            @if ($categories->count())
            <div class="col-lg-6">
                <nav class="b-equipment__nav-wrap">
                    <ul class="nav b-equipment__nav js-priority-nav">
                        <li>
                            <a class="nav-link @routeActive('wiki')" href="{{ route('wiki') }}#wiki">@lang('wiki.all')</a>
                        </li>
                        @foreach ($categories as $item)
                            <li>
                                <a class="nav-link @if (request()->route()->alias == $item->alias) active @endif"
                                    href="{{ route('wiki.category', $item->alias) }}#wiki">{{ $item->title }}</a>
                            </li>
                        @endforeach
                    </ul>
                </nav>
            </div>
            @endif
            <div class="col-lg-6">
                <form method="GET" action="{{ route('wiki') }}#wiki" class="b-wiki__search">
                    <div class="input-group">
                        <div class="input-group-append">
                            <button class="btn" type="submit">
                                <img src="{{ asset('default/img/icons/search.svg') }}" alt="search">
                            </button>
                        </div>
                        <input type=search name=search value="{{ request()->input('search') }}"
                            placeholder="@lang('wiki.search')" class="form-control" id="input-search" autocomplete="off">
                    </div>
                </form>
            </div>
        </div>
        <div class="row b-firmware-category">
            @forelse($wiki_list as $item)
                <div class="col-lg-6">
                    @include('wiki._item')
                </div>
            @empty
                <div class="col">
                    <div class="alert text-left">@lang('common.no_results')</div>
                </div>
            @endforelse

            @if ($wiki_list->hasPages())
                <div class="col mt-4" style="overflow: auto;">
                    {{ $wiki_list->links() }}
                </div>
            @endif
        </div>
    </div>

    @include('inc.promotion_x')

    @component('components.page_description')
        @if (Route::is('wiki.category'))
            @slot('description', $category->description)
        @endif
    @endcomponent

@endsection
