@extends('layouts.admin')

@section('content')

    @component('admin.components.breadcrumb')
        @slot('title', 'Роли')
    @endcomponent

    <div class="content">
        <div class="container-fluid">

            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Список ролей</h3>
                    <div class="card-tools">
                        @can('role_create')
                            <a href="{{ route('admin.roles.create') }}"><i class="fas fa-plus"></i> Добавить</a>
                        @endcan
                    </div>
                </div>
                <div class="card-body">
                    @if($roles->count())
                        <table class="table table-bordered table-hover js-data-table">
                            <thead>
                            <tr>
                                <th style="width: 1%;">#</th>
                                <th>Название</th>
                                <th>Роли</th>
                                <th style="width: 1%;" class="text-right">Действие</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($roles as $key => $item)
                                <tr>
                                    <td class="text-center">{{ $item->id }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>
                                        {!! '<span class="badge badge-info">' . implode('</span> <span class="badge badge-info">', $item->permissions->pluck('title')->toArray()) . '</span>' !!}
                                    </td>
                                    <td class="text-nowrap text-right">
                                        @include('admin.components.action', [
                                            'route' => 'roles',
                                            'can' => 'role',
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
