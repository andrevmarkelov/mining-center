{{-- START Reviews --}}
@if ($news_reviews->count())
    <div class="b-heading">{{ $heading ?? __('news.reviews') }}</div>
    <div class="row">
        <div class="col-lg-4">
            @include('news._item_main', [
                'item' => $news_reviews->first(),
                'class' => 'mb-4 mb-lg-2',
            ])
            @if ($item_2 = $news_reviews->get(1))
                @include('news._item_short', [
                    'item' => $item_2,
                    'class' => 'd-none d-lg-block',
                ])
            @endif
        </div>

        @if ($news_reviews->count() > 2)
            <div class="col-lg-8">
                <div class="row d-none d-lg-flex js-identical-height">
                    @foreach ($news_reviews->slice(2, 12)->chunk(5) as $tree)
                        <div class="col-lg">
                            @foreach ($tree as $item)
                                @include('news._item_short')
                            @endforeach
                        </div>
                    @endforeach
                </div>

                <div class="d-lg-none">
                    {{-- START Duplicate for mobile style --}}
                    <div class="row js-identical-height">
                        @foreach ($news_reviews->slice(1, 6)->chunk(3) as $tree)
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
            </div>
        @endif
    </div>
@endif
{{-- END Reviews --}}
