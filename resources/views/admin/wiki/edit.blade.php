@extends('layouts.admin')

@section('content')

    @component('admin.components.breadcrumb')
        @slot('title', 'Редактировать Wiki')
        @slot('items', [[
            'href' => route('admin.wiki.index'),
            'name' => 'Wiki'
            ]])
    @endcomponent

    <div class="content">
        <div class="container-fluid">

            <form action="{{ route('admin.wiki.update', $wiki->id) }}" method="post" class="js-ajax-form">
                @csrf
                @method('put')
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
