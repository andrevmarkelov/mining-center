@extends('layouts.admin')

@section('content')

    @component('admin.components.breadcrumb')
        @slot('title', 'Пользователи')
    @endcomponent

    <div class="content">
        <div class="container-fluid">

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Список пользователей</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover js-custom-data-table">
                        <thead>
                        <tr>
                            <th class="text-center" style="width: 1%;">Изображение</th>
                            <th>Название</th>
                            <th>Контакты</th>
                            <th>Роли</th>
                            <th>Создан</th>
                            {{-- <th>Активирован</th> --}}
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
            ajax: "{!! route('admin.users.index', request()->getQueryString()) !!}",
            columns: [
                {
                    data: 'thumb',
                    className: 'text-center',
                    render: function(data) {
                        return '<img style="width: 40px;" src="' + data + '" class="img-circle" alt="">';
                    },
                    orderable: false,
                },
                { data: 'name' },
                { data: 'contacts', orderable: false, },
                { data: 'roles', orderable: false, html: true },
                { data: 'created_at', className: 'text-center', orderable: false, },
                // {
                //     data: 'email_verified_at',
                //     searchable: false,
                //     className: 'text-center',
                //     render: function(data, type, full, meta) {
                //         return `<div class="custom-control custom-switch custom-switch-on-primary d-inline-block">
                //                 <input ${data ? `checked` : ''} type="checkbox" class="custom-control-input" id="change-status${full.id}">
                //                 <label class="custom-control-label" for="change-status${full.id}"></label>
                //             </div>`;
                //     },
                //     orderable: false,
                // },
                { data: 'action', orderable: false, className: 'text-nowrap text-right', },
            ]
        });

        $('.js-custom-data-table').DataTable(dTSettings);
    </script>
@endsection
