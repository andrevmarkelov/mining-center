<div class="b-service__item">
    <a href="{{ route('services.show', $item->alias) }}" class="b-service__img-wrap">
        <img class="w-100" src="{{ $item->thumb }}" alt="{{ $item->title }}">
    </a>

    <div class="b-service__right-col">
        <ul class="list-unstyled b-service__info">
            @if ($item->cities->count())
                @foreach ($item->cities->groupBy('country_id') as $cities)
                    <li class="list-inline-item">
                        <img src="{{ asset('default/img/icons/location.svg') }}" alt="location">
                        <div>
                            @foreach ($cities as $city)
                                <a class="text-dark" href="{{ route('services.city', $city->alias) }}">{{ $city->title }}</a>@if (!$loop->last),&nbsp;@endif
                            @endforeach
                        </div>
                    </li>
                @endforeach
            @endif
            <li class="list-inline-item">
                <img width="18" height="18"
                    src="{{ asset('default/img/icons/' . ($item->equipment_type == 0 ? 'asic' : 'gpu') . '.svg') }}"
                    alt="equipment type">
                {{ __('common.equipment_type')[$item->equipment_type] }}
            </li>
        </ul>

        <div class="b-service__title">
            <a href="{{ route('services.show', $item->alias) }}">{{ $item->title }}</a>
        </div>

        @if (strip_tags($item->description))
            <div class="b-service__text">{{ mb_strimwidth(strip_tags(str_replace(['</li>', '</p>'], ' ', $item->description)), 0, 180, '...') }}</div>
        @endif

        <a href="{{ route('services.show', $item->alias) }}" class="b-service__read-all">@lang('common.read_all')</a>
    </div>
</div>
