@extends('layouts.admin')

@section('content')

    @component('admin.components.breadcrumb')
        @slot('title', 'Алгоритмы')
    @endcomponent

    <div class="content">
        <div class="container-fluid">

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Список алгоритмов</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.algorithms.create') }}"><i class="fas fa-plus"></i> Добавить</a>
                    </div>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-hover js-custom-data-table">
                        <thead>
                        <tr>
                            <th>Название</th>
                            <th>Создано</th>
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
            ajax: "{!! route('admin.algorithms.index', request()->getQueryString()) !!}",
            columns: [
                { data: 'title', },
                { data: 'created_at', className: 'text-center', },
                { data: 'action', orderable: false, className: 'text-nowrap text-right', },
            ]
        });

        $('.js-custom-data-table').DataTable(dTSettings);
    </script>
@endsection
