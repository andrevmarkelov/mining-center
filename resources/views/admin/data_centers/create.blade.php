@extends('layouts.admin')

@section('content')

    @component('admin.components.breadcrumb')
        @slot('title', 'Добавить дата центр')
        @slot('items', [[
            'href' => route('admin.data_centers.index'),
            'name' => 'Дата центры'
            ]])
    @endcomponent

    <div class="content">
        <div class="container-fluid">

            <form action="{{ route('admin.data_centers.store') }}" method="post" class="js-ajax-form">
                @csrf
                <div class="row">
                    <div class="col-12 col-xl-9">
                        @include('admin.data_centers._form')
                    </div>
                    @include('admin.inc.aside_right')
                </div>
            </form>

        </div>
    </div>
@endsection
