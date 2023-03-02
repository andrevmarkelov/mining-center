<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">Информация</h3>
        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="maximize"><i class="fas fa-expand"></i></button>
        </div>
    </div>
    <div class="card-body">

        <div class="form-group">
            <label for="input-name">Название*</label>
            <input name="name" value="{{ old('name', $role->name ?? '') }}" id="input-name" type="text" class="form-control">
        </div>
        <div class="form-group">
            <label for="input-permissions">
                Права*
                <span class="btn btn-primary btn-xs js-select-all">Выбрать все</span>
                <span class="btn btn-primary btn-xs js-deselect-all">Убрать все</span></label>
            </label>
            <select name="permissions[]" id="input-permissions" multiple class="form-control js-select2bs4">
                @foreach($permissions as $id => $title)
                    <option value="{{ $id }}"
                            @if(in_array($id, old('permissions', isset($role) ? $role->permissions->pluck('id')->toArray() : []))) selected @endif
                    >
                        {{ $title }}
                    </option>
                @endforeach
            </select>
        </div>

    </div>

</div>

@section('script')
    @parent

    <script>
        $('.js-select-all').click(function () {
            let $select2 = $(this).parent().siblings('.js-select2bs4');
                $select2.find('option').prop('selected', 'selected');
                $select2.trigger('change');
        });

        $('.js-deselect-all').click(function () {
            let $select2 = $(this).parent().siblings('.js-select2bs4');
                $select2.find('option').prop('selected', '');
                $select2.trigger('change');
        })
    </script>
@endsection
