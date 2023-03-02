@unless(Route::is(['*.country', '*.city']) || request()->has('show_map'))
    @if (isset($pc_2) && $pc_2 && $pc_2->image)
        <a href="{{ $pc_2->link }}" @if ($pc_2->nofollow) rel="nofollow" @endif
            class="b-promotion-y js-promotion-y">
            <img class="img-fluid sticky-top" src="{{ ImageService::optimize($pc_2->image) }}" alt="">
        </a>
    @endif
@endunless
