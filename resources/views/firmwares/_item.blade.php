<div class="b-firmware__item">
    <a href="{{ route('firmwares.show', [$item->category->alias, $item->alias]) }}" class="b-firmware__img-wrap">
        <img src="{{ $item->thumb }}" alt="{{ $item->title }}">
    </a>
    <div class="b-firmware__right-col">
        <div class="b-firmware__title">
            <a href="{{ route('firmwares.show', [$item->category->alias, $item->alias]) }}">{{ $item->title }}</a>
        </div>

        <noindex>
            <p class="b-firmware__text">{{ mb_strimwidth(strip_tags(str_replace(['</li>', '</p>'], ' ', $item->description)), 0, 250, '...') }}</p>
        </noindex>

        <div class="row b-firmware__btn">
            <div class="col-md-6">
                @if ($item->attach)
                    @if ($item->attach->count() > 1)
                        <div class="btn-group w-100">
                            <button class="btn btn-primary dropdown-toggle"
                                data-toggle="dropdown">@lang('firmwares.download')</button>
                            <div class="dropdown-menu dropdown-menu-right">
                                @foreach ($item->attach as $file)
                                    <a href="{{ $file->getUrl() }}" download
                                        class="dropdown-item">{{ $file->name }}</a>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <a href="{{ $item->attach->first()->getUrl() }}" download
                            class="btn btn-primary btn-block">@lang('firmwares.download')</a>
                    @endif
                @endif
            </div>
            <div class="col-md-6">
                <a href="{{ route('firmwares.show', [$item->category->alias, $item->alias]) }}"
                    class="btn btn-outline-primary btn-block">@lang('firmwares.details')</a>
            </div>
        </div>
    </div>
</div>
