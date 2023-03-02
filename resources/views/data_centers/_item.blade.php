<div class="b-data-center__item">
    <a href="{{ route('data_centers.show', $item->alias) }}" class="b-data-center__img-wrap">
        <picture>
            <source media="(min-width: 768px)" srcset="{{ $item->thumb }}">
            <img src="{{ ImageService::optimize($item->image, 463, 320) }}" alt="{{ $item->title }}">
        </picture>
    </a>

    <div class="b-data-center__right-col">
        <div class="b-data-center__title">
            <a href="{{ route('data_centers.show', $item->alias) }}">
                {{ $item->translate('en')->title }}
                @if ($item->getMeta('is_partner'))
                    <img src="{{ asset('default/img/icons/bookmark.svg') }}" width="22"
                        height="22" data-toggle="tooltip" data-placement="top" title="@lang('data_centers.is_partner')"
                        style="margin-top: -4px;" alt="partner">
                @endif
            </a>
        </div>

        <ul class="list-unstyled b-data-center__info">
            @if ($item->cities->count())
                @foreach ($item->cities->groupBy('country_id') as $cities)
                    <li class="list-inline-item">
                        <img src="{{ asset('default/img/icons/location.svg') }}" alt="location">
                        <div>
                            @foreach ($cities as $city)
                                <a class="text-dark" href="{{ route('data_centers.city', $city->alias) }}">{{ $city->title }}</a>@if (!$loop->last),&nbsp;@endif
                            @endforeach
                        </div>
                    </li>
                @endforeach
            @endif
            <li class="list-inline-item">
                <img src="{{ asset('default/img/icons/' . ($item->power_type == 0 ? 'type' : 'hydropower') . '.svg') }}"
                    alt="power type">
                {{ __('common.power_type')[$item->power_type] }}
            </li>
        </ul>

        @if (strip_tags($item->add_description))
            <div class="b-data-center__attr">{!! $item->add_description !!}</div>
        @endif
    </div>
</div>
