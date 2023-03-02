<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Информация</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
        </div>
    </div>
    <div class="card-body">

        <div class="form-group">
            <label for="input-title">Название*</label>
            <input name="title" value="{{ old('title', $firmware_category->title ?? '') }}" type="text" id="input-title" class="form-control">
        </div>

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
                        <label for="{{ $key }}_input-meta_h1">H1 ({{ $key }})</label>
                        <input
                            name="{{ $key }}[meta_h1]"
                            value="{{ $firmware_category->meta_h1 ?? '' }}"
                            id="{{ $key }}_input-meta_h1"
                            type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="{{ $key }}_input-subtitle">Подзаголовок ({{ $key }})</label>
                        <textarea
                            name="{{ $key }}[subtitle]"
                            id="{{ $key }}_input-subtitle"
                            class="form-control js-summernote"
                            data-height="85">{{ $firmware_category->subtitle ?? '' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="{{ $key }}_input-description">Описание ({{ $key }})</label>
                        <textarea
                            name="{{ $key }}[description]"
                            id="{{ $key }}_input-description"
                            class="form-control js-summernote">{{ old($key . '.description', $firmware_category->description ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="{{ $key }}_input-meta_title">Title ({{ $key }})</label>
                        <input
                            name="{{ $key }}[meta_title]"
                            value="{{ $firmware_category->meta_title ?? '' }}"
                            id="{{ $key }}_input-meta_title"
                            type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="{{ $key }}_input-meta_description">Description ({{ $key }})</label>
                        <textarea
                            name="{{ $key }}[meta_description]"
                            id="{{ $key }}_input-meta_description"
                            class="form-control">{{ $firmware_category->meta_description ?? '' }}</textarea>
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

                @include('admin.components.image', [
                    'title' => 'Загрузить иконку',
                    'value' => $firmware_category->image ?? null
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
            <label for="input-alias">Url</label>
            <input name="alias" value="{{ old('alias', $firmware_category->alias ?? '') }}" type="text" id="input-alias" class="form-control">
            <small class="form-text text-muted">Можно не указывать, генерируется автоматически с главного языка.</small>
        </div>
        <div class="form-group">
            <label for="input-status">Статус</label>
            <div>
                <input name="status" value="0" type="hidden" checked>
                <input name="status" value="1" type="checkbox" id="input-status" data-bootstrap-switch
                       @if(old('status', $firmware_category->status ?? true))
                       checked
                    @endif
                >
            </div>
        </div>
        <div class="form-group">
            <label for="input-sitemap">Отображать в sitemap</label>
            <div>
                <input name="sitemap" value="0" type="hidden" checked>
                <input name="sitemap" value="1" type="checkbox" id="input-sitemap" data-bootstrap-switch
                       @if(old('sitemap', $firmware_category->sitemap ?? true))
                       checked
                    @endif
                >
            </div>
        </div>

    </div>
</div>

