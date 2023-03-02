@extends('layouts.admin')

@section('content')

    @component('admin.components.breadcrumb')
        @slot('title', 'Добавить правило')
        @slot('items', [[
            'href' => route('admin.permissions.index'),
            'name' => 'Права'
            ]])
    @endcomponent

    <div class="content">
        <div class="container-fluid">

            <form action="{{ route('admin.permissions.store') }}" method="post">
                @csrf
                <div class="row">
                    <div class="col-12 col-xl-9">
                        @include('admin.permissions._form')
                    </div>
                    @include('admin.inc.aside_right')
                </div>
            </form>

        </div>
    </div>
@endsection
