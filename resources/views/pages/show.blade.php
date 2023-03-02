@extends('layouts.app')

@section('content')
    @if ($page->id == '13')
        @include('pages._hashrate_converter')
    @elseif ($page->id == '14')
        @include('pages._crypto_converter')
    @else
        @component('components.breadcrumb')
            @slot('title', $page->title)
            @slot('subtitle', $page->subtitle)
        @endcomponent

        <div class="row">
            <div class="col-lg-11 col-xl-9">

                <div class="b-description">
                    {!! $page->description !!}
                </div>

            </div>
        </div>
    @endif
@endsection
