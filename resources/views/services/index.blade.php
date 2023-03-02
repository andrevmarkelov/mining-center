@extends('layouts.app')

@section('content')

    @component('components.breadcrumb')
        @slot('title_active', __('common.services'))
    @endcomponent

    @if ($countries->count())
        <div class="row mb-4 mb-md-5 b-data-center-btn">
            <div class="col-12 col-md">
                <div class="btn-group mr-md-3">
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ asset('default/img/icons/filter.svg') }}" alt="filter">
                        @lang('services.region')
                    </button>
                    <div class="dropdown-menu">
                        @foreach ($countries as $country)
                            <a href="{{ route('services.country', $country->alias) }}"
                                class="dropdown-item font-weight-bold">{{ $country->title }}</a>
                            @foreach ($country->cities as $city)
                                <a href="{{ route('services.city', $city->alias) }}"
                                    class="dropdown-item ml-2">{{ $city->title }}</a>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-auto ml-auto">
                <a href="{{ route('services', ['show_map' => true]) }}" class="btn btn-outline-primary">
                    <img src="{{ asset('default/img/icons/location.svg') }}" alt="location">
                    @lang('services.view_map')
                </a>
            </div>
        </div>
    @endif

    @if ($services->count())
        <div class="row b-data-center">
            @foreach ($services as $item)
                <div class="col-md-6 col-xl-4">
                    @include('services._item')
                </div>
            @endforeach
        </div>
    @else
        <div class="h4">@lang('common.no_results')</div>
    @endif

    @if ($services->hasPages())
        <div class="mt-5" style="overflow: auto;">
            {{ $services->links() }}
        </div>
    @endif

    @include('inc.promotion_x')

    @component('components.page_description')
    @endcomponent

@endsection
