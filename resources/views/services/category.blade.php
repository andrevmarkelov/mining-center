@extends('layouts.app')

@section('content')
    @component('components.breadcrumb')
        @if (!Route::is('services'))
            @slot('title', $page_title)
            @slot('subtitle', $page_subtitle)
            @slot('title_active', $category->title)
            @slot('items', [[
                'href' => route('services'),
                'name' => __('common.services'),
            ]])
        @else
            @slot('title_active', __('common.services'))
        @endif

        <a href="{{ route('services') }}" class="btn btn-primary px-5">@lang('data_centers.close_map')</a>
    @endcomponent

    <div id="page-map" class="b-data-center-map js-full-width"></div>

    @isset($services)
        <div class="b-data-center-right">
            <div class="b-data-center-right__inner">
                <div class="b-data-center-right__title">
                    {{ $category->title }}
                    <a href="{{ route('services.country', $category->country->alias) }}">
                        <img width="26" height="26" src="{{ asset('default/img/icons/close.svg') }}" alt="">
                    </a>
                </div>
                <p class="b-data-center-right__desc">@lang('services.items_available') {{ $services->count() }}</p>
                @if ($services->count())
                    <div class="row">
                        @foreach ($services as $item)
                            <div class="col-md-6 col-xl-12 mb-3">
                                @include('services._item')
                            </div>
                        @endforeach
                    </div>
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
