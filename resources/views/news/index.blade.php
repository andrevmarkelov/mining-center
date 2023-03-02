@extends('layouts.app')

@section('content')

    @component('components.breadcrumb')
        @slot('title_active', __('common.news'))
    @endcomponent

    @include('news._list_latest')

    @include('news._categories')

    @include('inc.promotion_x')

    @include('news._list_reviews')

    @include('news._list_people')

    {{-- START Investment --}}
    @include('news._list_reviews', [
        'heading' => __('news.investment'),
        'news_reviews' => $news_investment
    ])
    {{-- END Investment --}}

    @component('components.page_description')
    @endcomponent

@endsection
