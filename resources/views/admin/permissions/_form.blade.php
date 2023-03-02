<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Информация</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
        </div>
    </div>
    <div class="card-body">

        <div class="row">
            <div class="col-6">
                <div class="form-group">
                    <label for="input-title">Название*</label>
                    <input name="title" value="{{ old('title', $permission->title ?? '') }}" id="input-title" type="text" class="form-control">
                </div>
            </div>
            <div class="col-6">
                <div class="form-group">
                    <label for="input-action">Действие*</label>
                    <select name="action" id="input-action" class="form-control">
                        @foreach(config('app_data.permission_types') as $item)
                            <option value="{{ $item }}" @if(old('action', $permission->action ?? '') == $item) selected @endif>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

    </div>

</div>
