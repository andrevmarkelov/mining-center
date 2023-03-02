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
            <input name="title" value="{{ old('title', $rating->title ?? '') }}" type="text" id="input-title" class="form-control">
        </div>
        <div class="form-group">
            <label for="input-link">Ссылка*</label>
            <input name="link" value="{{ old('link', $rating->link ?? '') }}" type="url" id="input-link" class="form-control">
        </div>
        <div class="form-group">
            <label for="input-ref_link">Реферальна ссылка</label>
            <input name="ref_link" value="{{ old('ref_link', $rating->ref_link ?? '') }}" type="url" id="input-ref_link" class="form-control">
        </div>
        <div class="form-group">
            <label for="input-review_link">Cсылка на обзор</label>
            <input name="review_link" value="{{ old('review_link', $rating->review_link ?? '') }}" type="text" id="input-review_link" class="form-control">
        </div>
        <div class="form-group">
            <label for="input-coin">Относиться к монетам*</label>
            <select name="coins[]" id="input-coin" class="form-control js-select2" multiple data-placeholder="Выбрать значение">
                @foreach($coins as $coin_id => $coin)
                    <option value="{{ $coin_id }}" @if(old('coins', isset($rating->coins) ? $rating->coins->contains($coin_id) : '')) selected @endif>{{ $coin }}</option>
                @endforeach
            </select>
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
                    'value' => $rating->image ?? null,
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
            <label for="input-status">Статус</label>
            <div>
                <input name="status" value="0" type="hidden" checked>
                <input name="status" value="1" type="checkbox" id="input-status" data-bootstrap-switch
                       @if(old('status', $rating->status ?? true))
                       checked
                    @endif
                >
            </div>
        </div>

    </div>
</div>

