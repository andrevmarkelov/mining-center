@extends('layouts.app')

@section('content')
    <div class="container">

        <div class="row justify-content-center">
            <div class="col-lg-8">

                {{-- @component('components.breadcrumb')
                    @slot('title', __('errors.404_title'))
                @endcomponent --}}

                <div class="text-center">
                    <div style="font-size: 180px; line-height: 1;" class="font-weight-bolder mb-2">
                        4<span class="text-primary">0</span>4
                    </div>
                    <h1>@lang('errors.404_title')!</h1>
                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <p>@lang('errors.404_text')</p>
                            <a href="{{ route('home') }}" class="btn btn-primary btn-lg">@lang('common.home')</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
@endsection
