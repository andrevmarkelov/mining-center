<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Галерея</h3>
        <div class="card-tools">
            <a href="javascript:void(0);" class="b-gallery-picker-action">
                <i class="fas fa-plus"></i> Додати
                <input type="file" multiple accept="image/jpeg, image/jpg, image/png, image/gif" class="js-gallery-picker-action">
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="b-gallery-picker">
            <div class="b-gallery-picker__empty js-gallery-picker-empty" @isset($object->gallery) style="display: none;" @endisset>
                <p>Нет изображений</p>
            </div>
            <div class="b-gallery-picker__list row js-gallery-picker-list">
                @isset($object->gallery)
                    @foreach (collect($object->gallery)->reverse() as $item)
                        <div class="col-md-4 js-gallery-picker-item">
                            <div class="b-gallery-picker-item">
                                <div class="b-gallery-picker-item__remove js-ajax-gallery-remove" data-url="{{ route('admin.destroy_gallery', md5($item->id)) }}">
                                    <i class="far fa-trash-alt"></i>
                                </div>
                                <img class="b-gallery-picker-item__img" src="{{ ImageService::baseThumb($item->getUrl()) }}" alt="">
                            </div>
                        </div>
                    @endforeach
                @endisset
            </div>
        </div>
    </div>
</div>

<script type="html/tpl" id="gallery-picker">
    <div class="col-md-4 js-gallery-picker-item" data-index="{index}" style="display: none;">
        <div class="b-gallery-picker-item">
            <div class="b-gallery-picker-item__remove js-gallery-picker-remove">
                <i class="far fa-trash-alt"></i>
            </div>
            <input type="file" name="gallery[]" class="d-none">
            <img class="b-gallery-picker-item__img" src="{image}" alt="">
        </div>
    </div>
</script>
