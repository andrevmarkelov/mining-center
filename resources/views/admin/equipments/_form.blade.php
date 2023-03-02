<div class="card card-outline card-outline-tabs mb-0">
    <div class="card-header px-0">
        <ul class="nav nav-tabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" href="#tab-info" data-toggle="tab">Информация</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#tab-image" data-toggle="tab">Фото</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#tab-options" data-toggle="tab">Параметры</a>
            </li>
        </ul>
    </div>
</div>

<div class="tab-content">
    <div class="tab-pane active" id="tab-info">

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
                                    value="{{ old($key . '.title', $equipment->title ?? '') }}"
                                    id="{{ $key }}_input-title"
                                    type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="{{ $key }}_input-add_title">Дополнительный заголовок ({{ $key }})</label>
                                <input
                                    name="{{ $key }}[add_title]"
                                    value="{{ old($key . '.add_title', $equipment->add_title ?? '') }}"
                                    id="{{ $key }}_input-add_title"
                                    type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="{{ $key }}_input-description">Описание ({{ $key }})</label>
                                <textarea
                                    name="{{ $key }}[description]"
                                    id="{{ $key }}_input-description"
                                    class="form-control js-summernote">{{ old($key . '.description', $equipment->description ?? '') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="{{ $key }}_input-add_description">Характеристики ({{ $key }})</label>
                                <textarea
                                    name="{{ $key }}[add_description]"
                                    id="{{ $key }}_input-add_description"
                                    data-height="200"
                                    class="form-control js-summernote">{{ old($key . '.add_description', $equipment->add_description ?? '') }}</textarea>
                            </div>
                            <div class="form-group">
                                <label for="{{ $key }}_input-meta_title">Title ({{ $key }})</label>
                                <input
                                    name="{{ $key }}[meta_title]"
                                    value="{{ $equipment->meta_title ?? '' }}"
                                    id="{{ $key }}_input-meta_title"
                                    type="text" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="{{ $key }}_input-meta_description">Description ({{ $key }})</label>
                                <textarea
                                    name="{{ $key }}[meta_description]"
                                    id="{{ $key }}_input-meta_description"
                                    class="form-control">{{ $equipment->meta_description ?? '' }}</textarea>
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
                    <label for="input-price">Цена</label>
                    <input name="price" value="{{ old('price', $equipment->price ?? '') }}" type="text" id="input-price" class="form-control">
                </div>
                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input name="available" value="0" type="hidden" checked>
                        <input name="available" value="1" class="custom-control-input" type="checkbox" id="checkbox-available"
                               @if(old('available', $coin->available ?? 'checked'))
                               checked
                            @endif
                        >
                        <label for="checkbox-available" class="custom-control-label">Есть в наличие</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="input-alias">Url</label>
                    <input name="alias" value="{{ old('alias', $equipment->alias ?? '') }}" type="text" id="input-alias" class="form-control">
                    <small class="form-text text-muted">Можно не указывать, генерируется автоматически с главного языка.</small>
                </div>
                <div class="form-group">
                    <label for="input-status">Статус</label>
                    <div>
                        <input name="status" value="0" type="hidden" checked>
                        <input name="status" value="1" type="checkbox" id="input-status" data-bootstrap-switch
                               @if(old('status', $equipment->status ?? true))
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
                               @if(old('sitemap', $equipment->sitemap ?? true))
                               checked
                            @endif
                        >
                    </div>
                </div>

            </div>
        </div>

    </div>
    <div class="tab-pane" id="tab-image">

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Изображения</h3>
            </div>
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-6">

                        @include('admin.components.image', ['value' => $equipment->image ?? null])

                    </div>
                </div>
            </div>
        </div>

        @include('admin.components.gallery', ['object' => $equipment ?? null])

    </div>
    <div class="tab-pane" id="tab-options">

        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Параметры для рассчета прибыли</h3>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label for="input-coin_id">Относиться к монете*</label>
                    <select name="coin_id" id="input-coin_id" class="form-control js-select2">
                        <option value="">Выбрать значение</option>
                        @foreach($coins as $coin)
                            <option data-hashrate-unit="{{ $coin->whattomine_unit }}" value="{{ $coin->id }}" @if((old('coin_id', $equipment->coin_id ?? '')) == $coin->id) selected @endif>{{ $coin->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="input-hashrate">Хешрейт*</label>
                    <div class="input-group">
                        <input name="hashrate" value="{{ old('hashrate', $equipment->hashrate ?? '') }}" type="text" id="input-hashrate" class="form-control">
                        <div class="input-group-append js-hashrate-unit" style="display: none;">
                            <span class="input-group-text"></span>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="input-power">Мощность*</label>
                    <div class="input-group">
                        <input name="power" value="{{ old('power', $equipment->power ?? '') }}" type="text" id="input-power" class="form-control">
                        <div class="input-group-append">
                            <span class="input-group-text">W</span>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Другие параметры</h3>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <label for="input-firmware_id">Прошивка оборудования</label>
                    <select name="firmware_id" id="input-firmware_id" class="form-control js-select2">
                        <option value="">Выбрать значение</option>
                        @foreach($firmwares as $firmware_id => $firmware)
                            <option value="{{ $firmware_id }}" @if((old('firmware_id', $equipment->firmware_id ?? '')) == $firmware_id) selected @endif>{{ $firmware }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="input-manufacturer_id">Производитель</label>
                    <select name="manufacturer_id" id="input-manufacturer_id" class="form-control js-select2">
                        <option value="">Выбрать значение</option>
                        @foreach($manufacturers as $manufacturer_id => $manufacturer)
                            <option value="{{ $manufacturer_id }}" @if((old('manufacturer_id', $equipment->manufacturer_id ?? '')) == $manufacturer_id) selected @endif>{{ $manufacturer }}</option>
                        @endforeach
                    </select>
                </div>

            </div>
        </div>

    </div>
</div>

@section('script')
    <script>
        $(function () {
            let inputCoinId = $('#input-coin_id'),
                hashrateUnit = $('.js-hashrate-unit');

            inputCoinId.change(function () {
                let currentUnit = $(this).find('option:selected').data('hashrate-unit');
                    hashrateUnit.hide();

                if (currentUnit) {
                    hashrateUnit.find('span').text(currentUnit);
                    hashrateUnit.show();
                }
            });

            inputCoinId.change();
        });
    </script>
@endsection
