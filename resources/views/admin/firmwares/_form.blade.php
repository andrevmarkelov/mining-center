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
                        <label for="{{ $key }}_input-title">H1* ({{ $key }})</label>
                        <input
                            name="{{ $key }}[title]"
                            value="{{ old($key . '.title', $firmware->title ?? '') }}"
                            id="{{ $key }}_input-title"
                            type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="{{ $key }}_input-description">Описание ({{ $key }})</label>
                        <textarea
                            name="{{ $key }}[description]"
                            id="{{ $key }}_input-description"
                            class="form-control js-summernote">{{ old($key . '.description', $firmware->description ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="{{ $key }}_input-add_description">Характеристики ({{ $key }})</label>
                        <textarea
                            name="{{ $key }}[add_description]"
                            id="{{ $key }}_input-add_description"
                            data-height="200"
                            class="form-control js-summernote">{{ old($key . '.add_description', $firmware->add_description ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="{{ $key }}_input-meta_title">Title ({{ $key }})</label>
                        <input
                            name="{{ $key }}[meta_title]"
                            value="{{ $firmware->meta_title ?? '' }}"
                            id="{{ $key }}_input-meta_title"
                            type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="{{ $key }}_input-meta_description">Description ({{ $key }})</label>
                        <textarea
                            name="{{ $key }}[meta_description]"
                            id="{{ $key }}_input-meta_description"
                            class="form-control">{{ $firmware->meta_description ?? '' }}</textarea>
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

                @include('admin.components.image', ['value' => $firmware->image ?? null])

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
            <label for="input-category_id">Категория*</label>
            <select name="firmware_category_id" id="input-category_id" class="form-control js-select2">
                <option value="">Выбрать значение</option>
                @foreach($categories as $category_id => $category)
                    <option value="{{ $category_id }}" @if((old('firmware_category_id', $firmware->firmware_category_id ?? '')) == $category_id) selected @endif>{{ $category }}</option>
                @endforeach
            </select>
        </div>

        @include('admin.components.upload_file', ['object' => $firmware->attach ?? null])

        <div class="form-group">
            <label for="input-alias">Url</label>
            <input name="alias" value="{{ old('alias', $firmware->alias ?? '') }}" type="text" id="input-alias" class="form-control">
            <small class="form-text text-muted">Можно не указывать, генерируется автоматически с главного языка.</small>
        </div>
        <div class="form-group">
            <label for="input-sort_order">Сортировка</label>
            <input name="sort_order"
                   value="{{ old('sort_order',  $firmware->sort_order ?? '0') }}" id="input-sort_order" type="number" class="form-control">
            <small class="form-text text-muted">Чем выше значение, тем выше в списке будет отображаться.</small>
        </div>
        <div class="form-group">
            <label for="input-status">Статус</label>
            <div>
                <input name="status" value="0" type="hidden" checked>
                <input name="status" value="1" type="checkbox" id="input-status" data-bootstrap-switch
                       @if(old('status', $firmware->status ?? true))
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
                       @if(old('sitemap', $firmware->sitemap ?? true))
                       checked
                    @endif
                >
            </div>
        </div>

    </div>
</div>

