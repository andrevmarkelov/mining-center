<div class="b-image-picker js-image-picker">
    <div class="b-image-picker__remove js-image-picker-remove" @isset($value) style="display: block" @endisset>
        <i class="far fa-trash-alt"></i>
    </div>
    <img class="b-image-picker__img" src="{{ isset($value) ? ImageService::baseThumb($value) : '' }}" @isset($value) style="display: block" @endisset alt="">
    <div class="b-image-picker__overlay">
        <h4>{{ $title ?? 'Загрузить главное фото' }}</h4>
        <div class="btn btn-outline-primary btn-sm btn-flat">Выберите файл</div>
    </div>
    @if($value)
        <input type="hidden" name="delete_{{ $name ?? 'image' }}">
    @endif
    <input type="file" name="{{ $name ?? 'image' }}" accept="image/jpeg, image/jpg, image/png, image/gif{{ $add_img_ext ?? '' }}" class="js-image-picker-action">
</div>




