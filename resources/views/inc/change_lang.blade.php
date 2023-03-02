@php
    $langs = LaravelLocalization::getSupportedLocales();
    $excluded_post = App\Services\ExcludedPostService::execute();
@endphp

@if($langs && count($langs) > 1 && !$excluded_post)
    <div class="btn-group dropdown-hovered b-change-lang">
        <button class="btn dropdown-toggle" data-toggle="dropdown">{{ app()->getLocale() }}</button>
        <div class="dropdown-menu">
            @foreach($langs as $key => $item)
                @if(app()->getLocale() != $key)
                    <a class="dropdown-item" href="{{ LaravelLocalization::getLocalizedURL($key) }}">{{ explode('_', $item['regional'])[0] }}</a>
                @endif
            @endforeach
        </div>
    </div>
@endif
