@extends('layouts.admin')

@section('content')

    @component('admin.components.breadcrumb')
        @slot('title', 'Добавить Wiki')
        @slot('items', [[
            'href' => route('admin.wiki.index'),
            'name' => 'Wiki'
            ]])
    @endcomponent

    <div class="content">
        <div class="container-fluid">

            <form action="{{ route('admin.wiki.store') }}" method="post" class="js-ajax-form">
                @csrf
                <div class="row">
                    <div class="col-12 col-xl-9">
                        @include('admin.wiki._form')
                    </div>
                    @include('admin.inc.aside_right')
                </div>
            </form>

        </div>
    </div>
@endsection
