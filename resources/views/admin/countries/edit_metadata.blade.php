@extends('layouts.admin')

@section('content')

    @component('admin.components.breadcrumb')
        @slot('title', 'Метаданные ' . $country->title)
        @slot('items', [[
            'href' => route('admin.countries.index'),
            'name' => 'Страны'
            ]])
    @endcomponent

    <div class="content">
        <div class="container-fluid">

            <form action="{{ route('admin.country_metadata.update', ['country' => $country, 'type' => request('type')]) }}" method="post" class="js-ajax-form">
                @csrf
                @method('put')
                <div class="row">
                    <div class="col-12 col-xl-9">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Информация ({{  request('type') }})</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
                                </div>
                            </div>
                            <div class="card-body">

                                <ul class="nav nav-tabs" role="tablist">
                                    @foreach($langs = LaravelLocalization::getSupportedLocales() as $key => $item)
                                        <li class="nav-item">
                                            <a
                                                class="nav-link {{ key($langs) == $key ? 'active' : '' }}"
                                                href="#tab-{{ $key }}"
                                                data-toggle="tab"
                                            >{{ $item['native'] }}</a>
                                        </li>
                                    @endforeach
                                </ul>
                                <div class="tab-content pt-3">
                                    @foreach($langs as $key => $item)
                                        <div class="tab-pane {{ key($langs) == $key ? 'active' : '' }}" id="tab-{{ $key }}">
                                            <div class="form-group">
                                                <label for="{{ $key }}_input-meta_h1">H1 ({{ $key }})</label>
                                                <input
                                                    name="data[{{ $key }}][meta_h1]"
                                                    value="{{ $data[$key]['meta_h1'] }}"
                                                    id="{{ $key }}_input-meta_h1"
                                                    type="text" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="{{ $key }}_input-subtitle">Подзаголовок ({{ $key }})</label>
                                                <textarea
                                                    name="data[{{ $key }}][subtitle]"
                                                    id="{{ $key }}_input-subtitle"
                                                    class="form-control js-summernote"
                                                    data-height="85">{{ $data[$key]['subtitle'] }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="{{ $key }}_input-description">Описание ({{ $key }})</label>
                                                <textarea
                                                    name="data[{{ $key }}][description]"
                                                    id="{{ $key }}_input-description"
                                                    class="form-control js-summernote">{{ $data[$key]['description'] }}</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="{{ $key }}_input-meta_title">Title ({{ $key }})</label>
                                                <input
                                                    name="data[{{ $key }}][meta_title]"
                                                    value="{{ $data[$key]['meta_title'] }}"
                                                    id="{{ $key }}_input-meta_title"
                                                    type="text" class="form-control">
                                            </div>
                                            <div class="form-group">
                                                <label for="{{ $key }}_input-meta_description">Description ({{ $key }})</label>
                                                <textarea
                                                    name="data[{{ $key }}][meta_description]"
                                                    id="{{ $key }}_input-meta_description"
                                                    class="form-control">{{ $data[$key]['meta_description'] }}</textarea>
                                            </div>
                                        </div>
                                    @endforeach
                                    <div class="form-group">
                                        <label for="input-sitemap">Отображать в sitemap</label>
                                        <div>
                                            <input name="data[sitemap]" value="0" type="hidden" checked>
                                            <input name="data[sitemap]" value="1" type="checkbox" id="input-sitemap" data-bootstrap-switch
                                                @if($data['sitemap']) checked @endif
                                            >
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                    @include('admin.inc.aside_right')
                </div>
            </form>

        </div>
    </div>
@endsection
