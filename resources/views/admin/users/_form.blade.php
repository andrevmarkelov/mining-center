<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Информация</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
        </div>
    </div>
    <div class="card-body">

        <div class="form-group">
            <label for="input-email">Почта*</label>
            <input name="email" value="{{ old('email', $user->email) }}" id="input-email" type="email" class="form-control">
        </div>
        <div class="form-group">
            <label for="input-name">Название*</label>
            <input name="name" value="{{ old('name', $user->name) }}" id="input-name" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="input-password">Новый пароль</label>
            <input name="password" value="{{ old('password') }}" id="input-password" type="text" class="form-control">
        </div>

    </div>
</div>
<div class="card card-dark">
    <div class="card-header">
        <h3 class="card-title">Роли пользователя</h3>
    </div>
    <div class="card-body">

        <div class="form-group">
            @foreach($roles as $role)
                <div class="custom-control custom-checkbox">
                    <input name="roles[]"
                           value="{{ $role->id }}"
                           id="checkbox-roles{{ $role->id }}"
                           class="custom-control-input" type="checkbox"
                           @if($user->roles->pluck('id')->contains($role->id)) checked @endif
                    >
                    <label for="checkbox-roles{{ $role->id }}" class="custom-control-label">{{ ucfirst($role->name) }}</label>
                </div>
            @endforeach
        </div>
        {{-- <div class="form-group">
            <label for="input-status">Статус</label>
            <div>
                <input name="status" value="0" type="hidden" checked>
                <input name="status" value="1" type="checkbox" id="input-status" data-bootstrap-switch
                       @if(old('status', $user->status ?? true)) checked @endif
                >
            </div>
        </div> --}}

    </div>
</div>
