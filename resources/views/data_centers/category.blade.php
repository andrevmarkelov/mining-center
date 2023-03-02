@extends('layouts.app')

@section('content')
    @component('components.breadcrumb')
        @if (!Route::is('data_centers'))
            @slot('title', $page_title)
            @slot('subtitle', $page_subtitle)
            @slot('title_active', $category->title)
            @slot('items', [[
                'href' => route('data_centers'),
                'name' => __('common.data_centers'),
            ]])
        @else
            @slot('title_active', __('common.data_centers'))
        @endif

        <a href="{{ route('data_centers') }}" class="btn btn-primary px-5">@lang('data_centers.close_map')</a>
    @endcomponent

    <div id="page-map" class="b-data-center-map js-full-width"></div>

    @isset($data_centers)
        <div class="b-data-center-right">
            <div class="b-data-center-right__inner">
                <div class="b-data-center-right__title">
                    {{ $category->title }}
                    <a href="{{ route('data_centers.country', $category->country->alias) }}">
                        <img width="26" height="26" src="{{ asset('default/img/icons/close.svg') }}" alt="">
                    </a>
                </div>
                <p class="b-data-center-right__desc">@lang('data_centers.items_available') {{ $data_centers->count() }}</p>
                @if ($data_centers->count())
                    @foreach ($data_centers as $item)
                        <div class="mb-3">
                            @include('data_centers._item')
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    @endisset

@endsection

@section('busCacheInline')
    <script>
        styles.push(
            '/default/libs/leaflet/leaflet.css'
        );
    </script>
@endsection

@section('script')
    <script src="{{ asset('default/libs/leaflet/leaflet.js') }}" defer></script>
    <script src="{{ asset('default/js/leaflet.js') }}" defer></script>
    <script type="module">
        $(function() {
            initMap('{!! json_encode($map_items) !!}');
        });
    </script>
@endsection
