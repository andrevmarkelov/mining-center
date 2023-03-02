@php
    $current_lang = app()->getLocale();
    $active_lang = request()->input('lang', $current_lang);
@endphp

<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Информация</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
        </div>
    </div>
    <div class="card-body">

        <ul class="nav nav-tabs" role="tablist">
            @foreach (LaravelLocalization::getSupportedLocales() as $key => $item)
                <li class="nav-item">
                    <a class="nav-link {{ $active_lang == $key ? 'active' : '' }}"
                        href="{{ url()->current() . '?lang=' . $key }}">{{ $item['native'] }}</a>
                </li>
            @endforeach
        </ul>
        <div class="tab-content pt-3">
            {{ app()->setLocale($active_lang) }}
            <div class="form-group">
                <label for="{{ $active_lang }}_input-title">H1 ({{ $active_lang }})</label>
                <input name="{{ $active_lang }}[title]" value="{{ old($active_lang . '.title', $news->title ?? '') }}"
                    id="{{ $active_lang }}_input-title" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label for="{{ $active_lang }}_input-description">Описание ({{ $active_lang }})</label>
                <textarea
                    name="{{ $active_lang }}[description]"
                    id="{{ $active_lang }}_input-description"
                    class="form-control" hidden>{{ old($active_lang . '.description', $news->description ?? '') }}</textarea>
            </div>
            <div class="form-group">
                <label for="{{ $active_lang }}_input-meta_title">Title ({{ $active_lang }})</label>
                <input name="{{ $active_lang }}[meta_title]" value="{{ $news->meta_title ?? '' }}"
                    id="{{ $active_lang }}_input-meta_title" type="text" class="form-control">
            </div>
            <div class="form-group">
                <label for="{{ $active_lang }}_input-meta_description">Description ({{ $active_lang }})</label>
                <textarea name="{{ $active_lang }}[meta_description]" id="{{ $active_lang }}_input-meta_description"
                    class="form-control">{{ $news->meta_description ?? '' }}</textarea>
            </div>
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

                @include('admin.components.image', ['value' => $news->image ?? null])

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
            <label for="input-category">Категории</label>
            <select name="categories[]" id="input-category" class="form-control js-select2" multiple
                data-placeholder="Выбрать значение">
                @foreach ($categories as $category_id => $category)
                    <option value="{{ $category_id }}" @if (old('categories', isset($news->categories) ? $news->categories->contains($category_id) : '')) selected @endif>
                        {{ $category }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="input-publish_from">Опубликовать с даты</label>
            <input name="publish_from" value="{{ old('publish_from', $news->publish_from ?? '') }}"
                id="input-publish_from" type="datetime-local" class="form-control">
        </div>
        <div class="form-group">
            <label for="input-alias">Url</label>
            <input name="alias" value="{{ old('alias', $news->alias ?? '') }}" type="text" id="input-alias"
                class="form-control">
            <small class="form-text text-muted">Можно не указывать, генерируется автоматически с главного языка.</small>
        </div>
        <div class="form-group">
            <label for="input-sort_order">Сортировка</label>
            <input name="sort_order" value="{{ old('sort_order', $news->sort_order ?? '0') }}" id="input-sort_order"
                type="number" class="form-control">
            <small class="form-text text-muted">Чем выше значение, тем выше в списке будет отображаться.</small>
        </div>
        <div class="form-group">
            <label for="input-status">Статус</label>
            <div>
                <input name="status" value="0" type="hidden" checked>
                <input name="status" value="1" type="checkbox" id="input-status" data-bootstrap-switch
                    @if (old('status', $news->status ?? true)) checked @endif>
            </div>
        </div>
        <div class="form-group">
            <label for="input-sitemap">Отображать в sitemap</label>
            <div>
                <input name="sitemap" value="0" type="hidden" checked>
                <input name="sitemap" value="1" type="checkbox" id="input-sitemap" data-bootstrap-switch
                    @if (old('sitemap', $news->sitemap ?? true)) checked @endif>
            </div>
        </div>

    </div>
</div>

@section('script')
<script src="{{ asset('vendor/laraberg/js/react.production.min.js') }}"></script>
<script src="{{ asset('vendor/laraberg/js/react-dom.production.min.js') }}"></script>

<link href="{{ asset('vendor/laraberg/css/laraberg.css') }}" rel="stylesheet preload" as="style">
<script src="{{ asset('vendor/laraberg/js/laraberg.js') }}"></script>

<style>
    /* Custom style */
    .wp-block-embed {
        max-width: 800px;
    }
</style>
<script>
    const mediaUploaded = ({ filesList, onFileChange }) => {
        setTimeout(async () => {
            let formD = new FormData();

            Array.from(filesList).map((file) => {
                formD.append("upload", file);
            });

            const uploadedResponse = await $.ajax({
                method: "POST",
                url: `{{ env('APP_URL') }}/filemanager/upload/?type=file`,
                data: formD,
                processData: false,
                contentType: false,
                success: function (response) {
                    return response;
                },
                error: function (savePostErr) {
                    console.log({
                        savePostErr,
                    });
                },
            });

            const uploadedFiles = Array.from(filesList).map((file) => {
                return {
                    id: new Date().getTime(),
                    name: file.name,
                    url: uploadedResponse.url,
                };
            });

            onFileChange(uploadedFiles);
        }, 1000);
    };

    Laraberg.init("{{ $active_lang }}_input-description", {
        mediaUpload: mediaUploaded,
        alignWide: true,
        imageEditing: true,
        canLockBlocks: false,
        disableCustomColors: false,
        disableCustomGradients: false,
        disableCustomFontSizes: false,
        enableCustomLineHeight: true,
        enableCustomUnits: true,
        enableCustomSpacing: true,
        codeEditingEnabled: true,
        height: "auto",
        // gradients: [],
        // colors: [
        //     {
        //         name: "Primary",
        //         slug: "primary",
        //         color: "#3455c3",
        //     },
        // ],
        // fontSizes: [
        //     {
        //         name: "Noramal",
        //         slug: "noramal",
        //         size: "13",
        //     },
        // ],
    });

    class LaravelFilemanager extends Laraberg.wordpress.element.Component {
        constructor (props) {
            super(props)
            this.state = {
                media: []
            }
        }

        getMediaType = (path) => {
            const video = ['mp4', 'm4v', 'mov', 'wmv', 'avi', 'mpg', 'ogv', '3gp', '3g2']
            const audio = ['mp3', 'm4a', 'ogg', 'wav']
            const extension = path.split('.').slice(-1).pop()
            if (video.includes(extension)) {
                return 'video'
            } else if (audio.includes(extension)) {
                return 'audio'
            } else {
                return 'image'
            }
        }

        onSelect = (url, path) => {
            this.props.value = null
            const { multiple, onSelect } = this.props
            const media = {
                url: url,
                type: this.getMediaType(path)
            }

            if (multiple) { this.state.media.push(media) }
            onSelect(multiple ? this.state.media : media)
        }

        openModal = () => {
            let type = 'file'
            if (this.props.allowedTypes !== undefined && this.props.allowedTypes[0] === 'image') {
                type = 'image'
            }
            this.openLFM(type, this.onSelect)
        }

        openLFM = (type, cb) => {
            const routePrefix = '/filemanager/'
            window.open(routePrefix + '?type=' + type, 'FileManager', 'width=1500,height=1000')
            window.SetUrl = function (items) {
                if (items[0]) {
                    cb(items[0].url, items[0].name)
                }
            }
        }

        render () {
            const { render } = this.props
            return render({ open: this.openModal })
        }
    }

    elementRendered('.components-form-file-upload button', element => element.remove())

    function elementRendered (selector, callback) {
        const renderedElements = []
        const observer = new MutationObserver((mutations) => {
            const elements = document.querySelectorAll(selector)
            elements.forEach(element => {
                if (!renderedElements.includes(element)) {
                    renderedElements.push(element)
                    callback(element)
                }
            })
        })
        observer.observe(document.documentElement, { childList: true, subtree: true })
        return observer
    }

    // Использование хука editor.MediaUpload для добавления библиотеки
    Laraberg.wordpress.hooks.addFilter(
        "editor.MediaUpload",
        "core/edit-post/components/media-upload/replace-media-upload",
        () => LaravelFilemanager
    );

    // Удаление блока
    // Laraberg.wordpress.blocks.unregisterBlockType('core/gallery');
</script>
@endsection
