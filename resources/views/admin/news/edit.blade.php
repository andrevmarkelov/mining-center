@extends('layouts.admin')

@section('content')

    @component('admin.components.breadcrumb')
        @slot('title', 'Редактировать новость')
        @slot('items', [[
            'href' => route('admin.news.index'),
            'name' => 'Новости'
            ]])
    @endcomponent

    <div class="content">
        <div class="container-fluid">

            <form action="{{ route('admin.news.update', ['news' => $news->id, 'lang' => request()->input('lang')]) }}" method="post" class="js-ajax-form">
                @csrf
                @method('put')
                <div class="row">
                    <div class="col-12 col-xl-9">
                        @include('admin.news._form')
                    </div>
                    @include('admin.inc.aside_right', ['link_back' => route('admin.news.index')])
                </div>
            </form>

        </div>
    </div>
@endsection
