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
                            value="{{ old($key . '.title', $service->title ?? '') }}"
                            id="{{ $key }}_input-title"
                            type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="{{ $key }}_input-description">Описание ({{ $key }})</label>
                        <textarea
                            name="{{ $key }}[description]"
                            id="{{ $key }}_input-description"
                            class="form-control js-summernote">{{ old($key . '.description', $service->description ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="{{ $key }}_input-meta_title">Title ({{ $key }})</label>
                        <input
                            name="{{ $key }}[meta_title]"
                            value="{{ $service->meta_title ?? '' }}"
                            id="{{ $key }}_input-meta_title"
                            type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="{{ $key }}_input-meta_description">Description ({{ $key }})</label>
                        <textarea
                            name="{{ $key }}[meta_description]"
                            id="{{ $key }}_input-meta_description"
                            class="form-control">{{ $service->meta_description ?? '' }}</textarea>
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

                @include('admin.components.image', ['value' => $service->image ?? null])

            </div>
        </div>
    </div>
</div>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Контакты</h3>
    </div>
    <div class="card-body">

        <div class="form-group">
            <label for="input-email">Email</label>
            <input name="contacts[email]" value="{{ old('email', $service['contacts']['email'] ?? '') }}" type="email" placeholder="companyname@gmail.com" id="input-email" class="form-control">
        </div>
        <div class="form-group">
            <label for="input-telegram">Telegram</label>
            <input name="contacts[telegram]" value="{{ old('telegram', $service['contacts']['telegram'] ?? '') }}" type="text" placeholder="@companyname" id="input-telegram" class="form-control">
        </div>
        <div class="form-group">
            <label for="input-whatsapp">Whatsapp</label>
            <input name="contacts[whatsapp]" value="{{ old('whatsapp', $service['contacts']['whatsapp'] ?? '') }}" type="text" placeholder="@companyname" id="input-whatsapp" class="form-control">
        </div>
        <div class="form-group">
            <label for="input-phone">Телефон</label>
            <input name="contacts[phone]" value="{{ old('phone', $service['contacts']['phone'] ?? '') }}" type="text" id="input-phone" class="form-control">
        </div>

    </div>
</div>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Настройки</h3>
    </div>
    <div class="card-body">

        <div class="form-group">
            <label for="input-city">Относиться к городам*</label>
            <select name="cities[]" id="input-city" class="form-control js-select2" multiple data-placeholder="Выбрать значение">
                @foreach($cities as $city_id => $city)
                    <option value="{{ $city_id }}" @if(old('countries', isset($service->cities) ? $service->cities->contains($city_id) : '')) selected @endif>{{ $city }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="input-equipment_type">Типы оборудования*</label>
            <select name="equipment_type" id="input-equipment_type" class="form-control" size="{{ count(__('common.equipment_type')) }}">
                @foreach(__('common.equipment_type') as $key => $equipment_type)
                    <option value="{{ $key }}" @if((old('equipment_type', $service->equipment_type ?? '')) == $key) selected @endif>{{ $equipment_type }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="input-alias">Url</label>
            <input name="alias" value="{{ old('alias', $service->alias ?? '') }}" type="text" id="input-alias" class="form-control">
            <small class="form-text text-muted">Можно не указывать, генерируется автоматически с главного языка.</small>
        </div>
        <div class="form-group">
            <label for="input-sort_order">Сортировка</label>
            <input name="sort_order"
                   value="{{ old('sort_order', $service->sort_order ?? '0') }}" id="input-sort_order" type="number" class="form-control">
            <small class="form-text text-muted">Чем выше значение, тем выше в списке будет отображаться.</small>
        </div>
        <div class="form-group">
            <label for="input-status">Статус</label>
            <div>
                <input name="status" value="0" type="hidden" checked>
                <input name="status" value="1" type="checkbox" id="input-status" data-bootstrap-switch
                       @if(old('status', $service->status ?? true))
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
                       @if(old('sitemap', $service->sitemap ?? true))
                       checked
                    @endif
                >
            </div>
        </div>

    </div>
</div>

