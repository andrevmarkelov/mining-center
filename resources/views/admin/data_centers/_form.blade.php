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
                            value="{{ old($key . '.title', $data_center->title ?? '') }}"
                            id="{{ $key }}_input-title"
                            type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="{{ $key }}_input-description">Описание ({{ $key }})</label>
                        <textarea
                            name="{{ $key }}[description]"
                            id="{{ $key }}_input-description"
                            class="form-control js-summernote">{{ old($key . '.description', $data_center->description ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="{{ $key }}_input-add_description">Характеристики ({{ $key }})</label>
                        <textarea
                            name="{{ $key }}[add_description]"
                            id="{{ $key }}_input-add_description"
                            data-height="200"
                            class="form-control js-summernote">{{ old($key . '.add_description', $data_center->add_description ?? '') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="{{ $key }}_input-meta_title">Title ({{ $key }})</label>
                        <input
                            name="{{ $key }}[meta_title]"
                            value="{{ $data_center->meta_title ?? '' }}"
                            id="{{ $key }}_input-meta_title"
                            type="text" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="{{ $key }}_input-meta_description">Description ({{ $key }})</label>
                        <textarea
                            name="{{ $key }}[meta_description]"
                            id="{{ $key }}_input-meta_description"
                            class="form-control">{{ $data_center->meta_description ?? '' }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="{{ $key }}_input-action_text">Aкция на странице ({{ $key }})</label>
                        <textarea
                            name="{{ $key }}[action_text]"
                            id="{{ $key }}_input-action_text"
                            class="form-control">{{ $data_center->action_text ?? '' }}</textarea>
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

                @include('admin.components.image', ['value' => $data_center->image ?? null])

            </div>
        </div>
    </div>
</div>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Контакты</h3>
    </div>
    <div class="card-body">

        @php
            /** @var object $data_center */
            $contacts = isset($data_center) ? $data_center->getMeta('contacts') : [];
        @endphp
        <div class="form-group">
            <label for="input-email">Email</label>
            <input name="contacts[email]" value="{{ old('email', $contacts['email'] ?? '') }}" type="email" placeholder="companyname@gmail.com" id="input-email" class="form-control">
        </div>
        <div class="form-group">
            <label for="input-telegram">Telegram</label>
            <input name="contacts[telegram]" value="{{ old('telegram', $contacts['telegram'] ?? '') }}" type="text" placeholder="@companyname" id="input-telegram" class="form-control">
        </div>
        <div class="form-group">
            <label for="input-whatsapp">Whatsapp</label>
            <input name="contacts[whatsapp]" value="{{ old('whatsapp', $contacts['whatsapp'] ?? '') }}" type="text" placeholder="@companyname" id="input-whatsapp" class="form-control">
        </div>
        <div class="form-group">
            <label for="input-phone">Телефон</label>
            <input name="contacts[phone]" value="{{ old('phone', $contacts['phone'] ?? '') }}" type="text" id="input-phone" class="form-control">
        </div>
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input name="show_contacts" value="0" type="hidden" checked>
                <input name="show_contacts" value="1" class="custom-control-input" type="checkbox" id="checkbox-show_contacts"
                    @if(old('show_contacts', isset($data_center) ? $data_center->getMeta('show_contacts') : true))
                        checked
                    @endif
                >
                <label for="checkbox-show_contacts" class="custom-control-label">Показывать контакты</label>
            </div>
        </div>

    </div>
</div>
<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Настройки</h3>
    </div>
    <div class="card-body">

        {{-- <div class="form-group">
            <label for="input-country">Относиться к странам*</label>
            <select name="countries[]" id="input-country" class="form-control js-select2" multiple data-placeholder="Выбрать значение">
                @foreach($countries as $country_id => $country)
                    <option value="{{ $country_id }}" @if(old('countries', isset($data_center->countries) ? $data_center->countries->contains($country_id) : '')) selected @endif>{{ $country }}</option>
                @endforeach
            </select>
        </div> --}}
        <div class="form-group">
            <label for="input-city">Относиться к городам*</label>
            <select name="cities[]" id="input-city" class="form-control js-select2" multiple data-placeholder="Выбрать значение">
                @foreach($cities as $city_id => $city)
                    <option value="{{ $city_id }}" @if(old('countries', isset($data_center->cities) ? $data_center->cities->contains($city_id) : '')) selected @endif>{{ $city }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="input-power_type">Тип энергетики*</label>
            <select name="power_type" id="input-power_type" class="form-control" size="{{ count(__('common.power_type')) }}">
                @foreach(__('common.power_type') as $key => $power_type)
                    <option value="{{ $key }}" @if((old('power_type', $data_center->power_type ?? '')) == $key) selected @endif>{{ $power_type }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input name="is_partner" value="0" type="hidden" checked>
                <input name="is_partner" value="1" class="custom-control-input" type="checkbox" id="checkbox-is_partner"
                    @if(old('is_partner', isset($data_center) ? $data_center->getMeta('is_partner') : ''))
                        checked
                    @endif
                >
                <label for="checkbox-is_partner" class="custom-control-label">Партнер компании</label>
            </div>
        </div>
        <div class="form-group">
            <label for="input-alias">Url</label>
            <input name="alias" value="{{ old('alias', $data_center->alias ?? '') }}" type="text" id="input-alias" class="form-control">
            <small class="form-text text-muted">Можно не указывать, генерируется автоматически с главного языка.</small>
        </div>
        <div class="form-group">
            <label for="input-sort_order">Сортировка</label>
            <input name="sort_order"
                   value="{{ old('sort_order', $data_center->sort_order ?? '0') }}" id="input-sort_order" type="number" class="form-control">
            <small class="form-text text-muted">Чем выше значение, тем выше в списке будет отображаться.</small>
        </div>
        <div class="form-group">
            <label for="input-status">Статус</label>
            <div>
                <input name="status" value="0" type="hidden" checked>
                <input name="status" value="1" type="checkbox" id="input-status" data-bootstrap-switch
                       @if(old('status', $data_center->status ?? true))
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
                       @if(old('sitemap', $data_center->sitemap ?? true))
                       checked
                    @endif
                >
            </div>
        </div>

    </div>
</div>

