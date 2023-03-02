@extends('layouts.admin')

@section('content')

    @component('admin.components.breadcrumb')
        @slot('title', 'Добавить рекламу')
        @slot('items', [[
            'href' => route('admin.advertisings.index'),
            'name' => 'Реклама'
            ]])
    @endcomponent

    <div class="content">
        <div class="container-fluid">

            <form action="{{ route('admin.advertisings.store') }}" method="post" class="js-ajax-form">
                @csrf
                <div class="row">
                    <div class="col-12 col-xl-9">
                        @include('admin.advertisings._form')
                    </div>
                    @include('admin.inc.aside_right')
                </div>
            </form>

        </div>
    </div>
@endsection
