@extends('layouts.admin')

@section('content')

    @component('admin.components.breadcrumb')
        @slot('title', 'Добавить роль')
        @slot('items', [[
            'href' => route('admin.roles.index'),
            'name' => 'Роли'
            ]])
    @endcomponent

    <div class="content">
        <div class="container-fluid">

            <form action="{{ route('admin.roles.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-12 col-xl-9">
                        @include('admin.roles._form')
                    </div>
                    @include('admin.inc.aside_right')
                </div>
            </form>

        </div>
    </div>
@endsection
