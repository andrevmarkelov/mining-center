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
            <input name="title" value="{{ old('title', $algorithm->title ?? '') }}" type="text" id="input-title" class="form-control">
        </div>

    </div>
</div>

