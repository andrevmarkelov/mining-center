@extends('layouts.admin')

@section('content')

    @component('admin.components.breadcrumb')
        @slot('title', 'Добавить пул')
        @slot('items', [[
            'href' => route('admin.ratings.index'),
            'name' => 'Пулы'
            ]])
    @endcomponent

    <div class="content">
        <div class="container-fluid">

            <form action="{{ route('admin.ratings.store') }}" method="post" class="js-ajax-form">
                @csrf
                <div class="row">
                    <div class="col-12 col-xl-9">
                        @include('admin.ratings._form')
                    </div>
                    @include('admin.inc.aside_right')
                </div>
            </form>

        </div>
    </div>
@endsection
