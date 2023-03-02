@extends('layouts.admin')

@section('content')

    @component('admin.components.breadcrumb')
        @slot('title', 'Редактировать сервис')
        @slot('items', [[
            'href' => route('admin.mining.index'),
            'name' => 'Майнинг'
            ]])
    @endcomponent

    <div class="content">
        <div class="container-fluid">

            <form action="{{ route('admin.mining.update', $mining->id) }}" method="post" class="js-ajax-form">
                @csrf
                @method('put')
                <div class="row">
                    <div class="col-12 col-xl-9">
                        @include('admin.mining._form')
                    </div>
                    @include('admin.inc.aside_right')
                </div>
            </form>

        </div>
    </div>
@endsection
