<div class="col-12 col-xl-3">

    <div class="sticky-top" style="top: 72px;">
        <div class="row">
            <div class="col-6 col-xl-12">
                <a href="{{ $link_back ?? '#' }}"
                    @unless ($link_back ?? false)
                    onclick="goBackWithRefresh(); return false;"
                    @endunless
                    class="btn btn-default btn-lg btn-block">Назад</a>
            </div>
            <div class="col-6 col-xl-12">
                <button type="submit" class="btn btn-success btn-lg btn-block mt-xl-3">
                    Сохранить
                </button>
            </div>
        </div>
    </div>

</div>
