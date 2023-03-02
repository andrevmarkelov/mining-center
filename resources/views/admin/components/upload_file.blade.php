<div class="form-group">
    <label for="input-attach">Прикрепить файл</label>
    <input type="file" name="attach[]" multiple id="input-attach" class="js-pond">
</div>

@section('script')
    @parent

    <script>
        const inputElement = document.querySelector('.js-pond');

        FilePond.create(inputElement);
        FilePond.registerPlugin(FilePondPluginFilePoster);
        FilePond.setOptions({
            labelIdle: 'Перетащите файлы или <span class="filepond--label-action">Выберите</span>',
            server: {
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                process: '{{ route('admin.upload_file') }}',
            },

            // Подгрузка загруженных файлов
            @if($object)
            files: [
                @foreach($object as $attach)
                {
                    source: ' ',
                    options: {
                        type: 'local',
                        file: {
                            name: '{{ $attach->file_name }}',
                            size: '{{ $attach->size }}',
                            type: '{{ $attach->mime_type }}',
                            destroy: '{{ route('admin.destroy_gallery', md5($attach->id)) }}'
                        },
                    },
                },
                @endforeach
            ],
            @endif
        });

        // Удаление файла
        @if($object)
        $('.js-pond').on('FilePond:removefile', function(e) {
            let url = e.detail.file.file.destroy;

            if (url) {
                $.ajax({
                    url: url,
                    method: 'DELETE',
                    dataType: 'json',
                    success: function(data) {
                        if  (data.success) {
                            toastr.success(data.success);
                        }
                    },
                    error: function(data) {
                        toastr.error('Send error');
                    }
                });
            }
        });
        @endif
    </script>
@endsection
