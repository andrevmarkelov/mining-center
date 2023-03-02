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
            <input name="title" value="{{ old('title', $coin->title ?? '') }}" type="text" id="input-title" class="form-control">
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
                      'value' => $coin->image ?? null,
                      'add_img_ext' => ', image/svg+xml'
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
            <label for="input-algorithm_id">Алгоритм*</label>
            <select name="algorithm_id" id="input-algorithm_id" class="form-control js-select2">
                <option value="">Выбрать значение</option>
                @foreach($algorithms as $algorithm_id => $algorithm)
                    <option value="{{ $algorithm_id }}" @if((old('algorithm_id', $coin->algorithm_id ?? '')) == $algorithm_id) selected @endif>{{ $algorithm }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="input-code">Код монеты*</label>
            <input name="code" value="{{ old('code', $coin->code ?? '') }}" type="text" id="input-code" class="form-control">
            <small class="form-text text-muted">Например eth, btc, необходим для подтягивания данных по Api.</small>
        </div>
        <div class="form-group">
            <label for="input-whattomine_coin_id">Id монеты на сайте whattomine.com</label>
            <input name="whattomine_coin_id" value="{{ old('whattomine_coin_id', $coin->whattomine_coin_id ?? '') }}" type="text" id="input-whattomine_coin_id" class="form-control">
            <small class="form-text text-muted">Нужен для получение данных для раздела "Оборудование". По <a target="_blank" href="https://whattomine.com/calculators">ссылке</a> выбираем нужную монету, открывает ее и с урл копирует первое число к тире.</small>
        </div>
        <div class="form-group">
            <label for="input-alias">Url</label>
            <input name="alias" value="{{ old('alias', $coin->alias ?? '') }}" type="text" id="input-alias" class="form-control">
            <small class="form-text text-muted">Можно не указывать, генерируется автоматически с главного языка.</small>
        </div>
        <div class="form-group">
            <div class="custom-control custom-checkbox">
                <input name="show_home" value="0" type="hidden" checked>
                <input name="show_home" value="1" class="custom-control-input" type="checkbox" id="checkbox-show_home"
                       @if(old('show_home', $coin->show_home ?? ''))
                       checked
                    @endif
                >
                <label for="checkbox-show_home" class="custom-control-label">Показывать на главной</label>
            </div>
        </div>
        <div class="form-group">
            <label for="input-status">Статус</label>
            <div>
                <input name="status" value="0" type="hidden" checked>
                <input name="status" value="1" type="checkbox" id="input-status" data-bootstrap-switch
                       @if(old('status', $coin->status ?? true))
                       checked
                    @endif
                >
            </div>
        </div>

    </div>
</div>

