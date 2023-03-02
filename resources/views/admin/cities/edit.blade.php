@extends('layouts.admin')

@section('content')

    @component('admin.components.breadcrumb')
        @slot('title', 'Редактировать город')
        @slot('items', [[
            'href' => route('admin.cities.index'),
            'name' => 'Города'
            ]])
    @endcomponent

    <div class="content">
        <div class="container-fluid">

            <form action="{{ route('admin.cities.update', $city->id) }}" method="post" class="js-ajax-form">
                @csrf
                @method('put')
                <div class="row">
                    <div class="col-12 col-xl-9">
                        @include('admin.cities._form')
                    </div>
                    @include('admin.inc.aside_right')
                </div>
            </form>

        </div>
    </div>
@endsection
