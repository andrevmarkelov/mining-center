@extends('layouts.admin')

@section('content')

    @component('admin.components.breadcrumb')
        @slot('title', 'Редактировать профиль')
    @endcomponent

    <div class="content">
        <div class="container-fluid">

            <form action="{{ route('admin.profile.update', $user->id) }}" enctype="multipart/form-data" method="post">
                @csrf
                @method('put')
                <div class="row">
                    <div class="col-12 col-xl-9">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Информация</h3>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
                                </div>
                            </div>
                            <div class="card-body">

                                <div class="form-group">
                                    <label for="input-name">Почта*</label>
                                    <input
                                        value="{{ $user->email }}"
                                        id="input-email"
                                        type="email" class="form-control" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="input-name">Имя*</label>
                                    <input
                                        name="name"
                                        value="{{ old('name', $user->name) }}"
                                        id="input-name"
                                        type="text" class="form-control">
                                </div>

                            </div>
                        </div>
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Изображения</h3>
                            </div>
                            <div class="card-body">

                                <div class="row justify-content-center">
                                    <div class="col-md-6">

                                        @include('admin.components.image', [
                                             'title' => 'Загрузить аватар',
                                             'value' => $user->avatar,
                                             'name' => 'avatar'
                                        ])

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
