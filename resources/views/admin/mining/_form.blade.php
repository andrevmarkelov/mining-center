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
                            value="{{ old($key . '.title', $mining->title ?? '') }}"
                            id="{{ $key }}_input-title"
                            type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="{{ $key }}_input-description">Описание ({{ $key }})</label>
                        <textarea
                            name="{{ $key }}[description]"
                            id="{{ $key }}_input-description"
                            class="form-control js-summernote">{{ old($key . '.description', $mining->description ?? '') }}</textarea>
                    </div>
                </div>
            @endforeach
            {{ app()->setLocale($current_lang) }}
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

                @include('admin.components.image', ['value' => $mining->image ?? null])

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
            <label for="input-link">Ссылка</label>
            <input name="link" value="{{ old('link', $mining->link ?? '') }}" type="url" id="input-link" class="form-control">
        </div>
        <div class="form-group">
            <label for="input-status">Статус</label>
            <div>
                <input name="status" value="0" type="hidden" checked>
                <input name="status" value="1" type="checkbox" id="input-status" data-bootstrap-switch
                       @if(old('status', $mining->status ?? true))
                       checked
                    @endif
                >
            </div>
        </div>

    </div>
</div>

