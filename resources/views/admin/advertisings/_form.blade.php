<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Информация</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
        </div>
    </div>
    <div class="card-body">

        <div class="form-group">
            <label for="input-type">Тип баннера*</label>
            @php
                $advertising_types = config('app_data.advertising_types');
            @endphp
            <select name="type" id="input-type" size="{{ count($advertising_types) }}" class="form-control">
                @foreach ($advertising_types as $type_id => $item)
                    <option value="{{ $type_id }}" @if (old('type', $advertising->type ?? '') == $type_id) selected @endif>
                        {{ $item }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="input-link">Ссылка*</label>
            <input name="link" value="{{ old('link', $advertising->link ?? '') }}" type="url" id="input-link"
                class="form-control">
        </div>
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input name="nofollow" value="0" type="hidden" checked>
                <input name="nofollow" value="1" class="custom-control-input" type="checkbox" id="checkbox-nofollow"
                    @if(old('nofollow', $advertising->nofollow ?? ''))
                       checked
                    @endif
                >
                <label for="checkbox-nofollow" class="custom-control-label">Запретить индексацию</label>
            </div>
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
                    'title' => 'Загрузить баннер',
                    'value' => $advertising->image ?? null,
                ])

            </div>
        </div>
    </div>
</div>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Настройки</h3>
    </div>
    <div class="card-body">

        <div class="form-group">
            <label>Язык для показа</label>
            @foreach ($langs = LaravelLocalization::getSupportedLocales() as $key => $item)
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="language[]"
                            @if (isset($advertising) && $advertising->getMeta('language_' . $key) ?? '') checked @endif
                            value="{{ $key }}">{{ $item['native'] }}
                    </label>
                </div>
            @endforeach
        </div>
        <div class="form-group">
            <label>Разделы для показа</label>
            @foreach (config('app_data.advertising_positions') as $item)
                <div class="form-check">
                    <label class="form-check-label">
                        <input type="checkbox" class="form-check-input" name="position[]"
                            @if (isset($advertising) && $advertising->getMeta('position_' . $item) ?? '') checked @endif
                            value="{{ $item }}">{{ ucfirst($item) }}
                    </label>
                </div>
            @endforeach
        </div>
        <div class="form-group">
            <label for="input-status">Статус</label>
            <div>
                <input name="status" value="0" type="hidden" checked>
                <input name="status" value="1" type="checkbox" id="input-status" data-bootstrap-switch
                    @if (old('status', $advertising->status ?? true)) checked @endif>
            </div>
        </div>

    </div>
</div>
