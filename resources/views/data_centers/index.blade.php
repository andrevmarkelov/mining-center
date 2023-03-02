@extends('layouts.app')

@section('content')

    @component('components.breadcrumb')
        @slot('title_active', __('common.data_centers'))
    @endcomponent

    <div class="row mb-4 mb-md-5 b-data-center-btn">
        <div class="col-12 col-md">
            @if ($countries->count())
                <div class="btn-group mr-md-3">
                    <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                        <img src="{{ asset('default/img/icons/filter.svg') }}" alt="filter">
                        @lang('data_centers.region')
                    </button>
                    <div class="dropdown-menu">
                        @foreach ($countries as $country)
                            <a href="{{ route('data_centers.country', $country->alias) }}"
                                class="dropdown-item font-weight-bold">{{ $country->title }}</a>
                            @foreach ($country->cities as $city)
                                <a href="{{ route('data_centers.city', $city->alias) }}"
                                    class="dropdown-item ml-2">{{ $city->title }}</a>
                            @endforeach
                        @endforeach
                    </div>
                </div>
            @endif
            <button class="btn btn-outline-primary" data-toggle="modal" data-target="#modal-data-center">
                <img src="{{ asset('default/img/icons/add-data-center.svg') }}" alt="add">
                @lang('data_centers.add_object')
            </button>
        </div>
        @if ($countries->count())
            <div class="col-12 col-md-auto ml-auto">
                <a href="{{ route('data_centers', ['show_map' => true]) }}" class="btn btn-outline-primary">
                    <img src="{{ asset('default/img/icons/location.svg') }}" alt="location">
                    @lang('data_centers.view_map')
                </a>
            </div>
        @endif
    </div>

    <div class="modal fade b-theme-modal" id="modal-data-center">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="b-theme-modal__close" data-dismiss="modal">
                    <img src="{{ asset('default/img/icons/close.svg') }}" alt="">
                </div>
                <div class="modal-body">
                    <form id="data-center-form" action="{{ route('data_centers.send') }}" method="post"
                        class="b-theme-form">
                        @csrf
                        <div class="b-heading text-center mt-0">@lang('data_centers.add_object')</div>
                        <div class="form-group">
                            <input placeholder="@lang('data_centers.name')*" type="text" name="name" class="form-control"
                                id="input-name">
                        </div>
                        <div class="form-group">
                            <input placeholder="@lang('common.email')*" type="email" name="email" class="form-control"
                                id="input-email">
                        </div>
                        <div class="form-group">
                            <input placeholder="@lang('data_centers.country')*" type="text" name="location" class="form-control"
                                id="input-location">
                        </div>
                        <div class="form-group">
                            <textarea placeholder="@lang('data_centers.description')" name="text" class="form-control" id="input-text"></textarea>
                        </div>
                        <div class="text-center">
                            {!! NoCaptcha::renderJs() !!}
                            <button class="btn btn-primary btn-lg b-theme-form__btn g-recaptcha"
                                data-callback="dataCenterForm"
                                data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"
                            >@lang('data_centers.add')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if ($data_centers->count())
        <div class="row b-data-center">
            @foreach ($data_centers as $item)
                <div class="col-xl-6">
                    @include('data_centers._item')
                </div>
            @endforeach
        </div>
    @else
        <div class="h4">@lang('common.no_results')</div>
    @endif

    @if ($data_centers->hasPages())
        <div class="mt-5" style="overflow: auto;">
            {{ $data_centers->links() }}
        </div>
    @endif

    @include('inc.promotion_x')

    @component('components.page_description')
    @endcomponent

@endsection
