@extends('layouts.admin')

@section('content')

    @component('admin.components.breadcrumb')
        @slot('title', 'Страницы')
    @endcomponent

    <div class="content">
        <div class="container-fluid">

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Список страниц</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.pages.create') }}"><i class="fas fa-plus"></i> Добавить</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover js-custom-data-table">
                        <thead>
                        <tr>
                            <th>Название</th>
                            <th>Тип</th>
                            <th>Создано</th>
                            <th>Статус</th>
                            <th>Действие</th>
                        </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>

@endsection

@section('script')
    @parent

    <script>
        dTSettings = Object.assign(dTSettings, {
            processing: true,
            serverSide: true,
            ajax: "{!! route('admin.pages.index', request()->getQueryString()) !!}",
            columns: [
                { data: 'title', orderable: false, },
                { data: 'type', },
                { data: 'created_at', className: 'text-center', },
                {
                    data: 'status',
                    searchable: false,
                    className: 'text-center',
                    render: function(data, type, full, meta) {
                        return `<div class="custom-control custom-switch custom-switch-on-primary d-inline-block">
                                <input ${data == 1 ? `checked` : ''} type="checkbox" class="custom-control-input" id="change-status${full.id}">
                                <label class="custom-control-label" for="change-status${full.id}"></label>
                            </div>`;
                    },
                    orderable: false,
                },
                { data: 'action', orderable: false, className: 'text-nowrap text-right', },
            ]
        });

        $('.js-custom-data-table').DataTable(dTSettings);
    </script>
@endsection
