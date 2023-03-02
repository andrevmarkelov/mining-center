@extends('layouts.admin')

@section('content')

    @component('admin.components.breadcrumb')
        @slot('title', 'Права')
    @endcomponent

    <div class="content">
        <div class="container-fluid">

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Список прав</h3>
                    <div class="card-tools">
                        @can('permission_create')
                            <a href="{{ route('admin.permissions.create') }}"><i class="fas fa-plus"></i> Добавить</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    @if($permissions->count())
                        <table class="table table-bordered table-hover js-data-table">
                            <thead>
                            <tr>
                                <th style="width: 1%;">#</th>
                                <th>Название</th>
                                <th style="width: 1%;" class="text-right">Действие</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($permissions as $key => $item)
                                <tr>
                                    <td class="text-center">{{ $item->id }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td class="text-nowrap text-right">
                                        @include('admin.components.action', [
                                            'route' => 'permissions',
                                            'can' => 'permission',
                                            'id' => $item->id,
                                        ])
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @else
                        <div class="alert alert-light mb-0">В данном блоке нет информации</div>
                    @endif
                </div>
            </div>

        </div>
    </div>
@endsection
