{{-- START People --}}
@if ($news_people->count())
    <div class="b-heading">@lang('news.people')</div>
    @if ($items_0_3 = $news_people->slice(0, 3))
        <div class="row mb-2 js-mobile-carousel-md">
            @foreach ($items_0_3 as $item)
                <div class="col-lg-4" data-col="col-lg-4">
                    @include('news._item_main')
                </div>
            @endforeach
        </div>
    @endif

    @if ($items_3_6 = $news_people->slice(3, 6))
        <div class="row d-none d-lg-flex js-identical-height">
            @foreach ($items_3_6->chunk(2) as $tree)
                <div class="col-lg-4">
                    @foreach ($tree as $item)
                        @include('news._item_short')
                    @endforeach
                </div>
            @endforeach
        </div>

        <div class="d-lg-none">
            {{-- START Duplicate for mobile style --}}
            <div class="row js-identical-height">
                @foreach ($items_3_6->chunk(3) as $tree)
                    <div class="col {{ !$loop->first ? 'd-none d-md-block' : '' }}">
                        @foreach ($tree as $item)
                            @include('news._item_short')
                        @endforeach
                    </div>
                @endforeach
            </div>
            {{-- END --}}

            <div class="text-center mt-3">
                <a href="{{ route('news') }}" class="btn btn-outline-primary px-4">@lang('home.view_all_news')</a>
            </div>
        </div>
    @endif
@endif
{{-- END People --}}
