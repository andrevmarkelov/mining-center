<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Информация</h3>
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
            @php $current_lang = app()->getLocale(); @endphp
            @foreach($langs as $key => $item)
                {{ app()->setLocale($key) }}
                <div class="tab-pane {{ key($langs) == $key ? 'active' : '' }}" id="tab-{{ $key }}">
                    <div class="form-group">
                        <label for="{{ $key }}_input-title">Название* ({{ $key }})</label>
                        <input
                            name="{{ $key }}[title]"
                            value="{{ old($key . '.title', $country->title ?? '') }}"
                            id="{{ $key }}_input-title"
                            type="text" class="form-control">
                    </div>
                </div>
            @endforeach
            {{ app()->setLocale($current_lang) }}
        </div>

    </div>
</div>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Настройки</h3>
    </div>
    <div class="card-body">

        <div class="form-group">
            <label for="input-alias">Url</label>
            <input name="alias" value="{{ old('alias', $country->alias ?? '') }}" type="text" id="input-alias" class="form-control">
            <small class="form-text text-muted">Можно не указывать, генерируется автоматически с главного языка.</small>
        </div>
        <div class="form-row">
            <div class="col-12">
                <label>Расположение на карте</label>
            </div>
            <div class="form-group col">
                <input name="latitude" placeholder="Долгота" value="{{ old('latitude', $country->latitude ?? '') }}" type="text" class="form-control">
            </div>
            <div class="form-group col">
                <input name="longitude" placeholder="Широта" value="{{ old('longitude', $country->longitude ?? '') }}" type="text" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label for="input-status">Статус</label>
            <div>
                <input name="status" value="0" type="hidden" checked>
                <input name="status" value="1" type="checkbox" id="input-status" data-bootstrap-switch
                       @if(old('status', $country->status ?? true))
                       checked
                    @endif
                >
            </div>
        </div>

    </div>
</div>

