@extends('layouts.app', ['body_white' => true])

@section('content')

    @include('news._list_latest')

    @include('news._categories')

    @if (app()->getLocale() == 'ru')
        <a class="b-promotion-x" target="_blank" rel="nofollow"
            href="https://ecos.am/en/cloud-mining?ref=MRSC7xhE&__cf_chl_tk=xZafRVgxpGUyi7u0YJIBU.LrVSmIz.udfksa8SiQ06o-1671612993-0-gaNycGzNCJE">
            <video style="max-width: 100%;" autoplay loop muted>
                <source src="/default/video/prom1.mp4" type="video/mp4">
            </video>
        </a>
    @else
        @include('inc.promotion_x')
    @endif

    {{-- START Pool Stats --}}
    <div class="b-pool-stats js-full-width">
        <div class="container">
            @if ($coins->count())
                <div class="b-heading b-pool-stats__heading">@lang('pool_stats.heading')</div>
                <ul class="nav b-pool-stats__nav js-pool-stats-nav">
                    @foreach ($coins as $item)
                        <button type="button" data-code="{{ $item->code }}"
                            class="nav-link {{ $loop->index == 0 ? 'active' : '' }}">
                            <img width="16" height="16" src="{{ ImageService::optimize($item->image, 16, 16) }}"
                                alt="{{ $item->title }}">
                            {{ $item->code }}
                        </button>
                    @endforeach
                </ul>

                <picture class="js-pool-stats-loader">
                    <source media="(max-width: 767px)" srcset="{{ ImageService::optimize('default/img/bg/pool-stats-mobile.jpg') }}">
                    <source media="(max-width: 991px)" srcset="{{ ImageService::optimize('default/img/bg/pool-stats-tablet.jpg') }}">
                    <img class="w-100" src="{{ ImageService::optimize('default/img/bg/pool-stats.jpg') }}" alt="">
                </picture>

                <div class="js-pool-stats-box"></div>
            @endif
        </div>
    </div>
    {{-- END Pool Stats --}}

    <div class="js-subscribe-place"></div>

    @if (app()->getLocale() == 'ru')
        @include('inc.promotion_x')
    @endif

    {{-- START Data centers --}}
    <div class="b-heading">@lang('common.data_centers')</div>
    @php
        $data_centers = [
            [
                'title' => [
                    'ru' => 'Топ майнинг дата-центров в вашем регионе',
                    'en' => 'Best mining centers in Georgia',
                ],
                'image' => 'default/img/temporary/data-center1.jpg',
            ],
            [
                'title' => [
                    'ru' => 'Размещение в майнинг дата-центрах от 3 р/кВт',
                    'en' => 'European mining facilities powered by renewable energy',
                ],
                'image' => 'default/img/temporary/data-center2.jpg',
            ],
            [
                'title' => [
                    'ru' => 'Дата-центры с различными типами энергии',
                    'en' => 'Best Bitcoin data centers in Canada',
                ],
                'image' => 'default/img/temporary/data-center3.jpg',
            ],
            [
                'title' => [
                    'ru' => 'Легальные и верифицированные майнинг отели',
                    'en' => 'USA hostings for your mining equipment',
                ],
                'image' => 'default/img/temporary/data-center4.jpg',
            ],
        ];
    @endphp
    <div class="row {{ app()->getLocale() == 'en' ? 'b-home-custom-row' : '' }} js-home-carousel">
        @foreach ($data_centers as $item)
            @if ($loop->iteration == 3 && app()->getLocale() == 'en')
                <div class="col" data-col="col">
                    <a rel="nofollow" target="_blank"
                        href="https://ecos.am/en/cloud-mining?ref=MRSC7xhE&__cf_chl_tk=xZafRVgxpGUyi7u0YJIBU.LrVSmIz.udfksa8SiQ06o-1671612993-0-gaNycGzNCJE">
                        <img class="b-data-center-item__img" src="{{ asset('default/img/promotion/240х400.gif') }}"
                            alt="">
                    </a>
                </div>
            @else
                @php $title = $item['title'][app()->getLocale()] @endphp
                <div class="col-3" data-col="col-3">
                    <a href="{{ route('data_centers') }}" class="b-data-center-item">
                        <img src="{{ ImageService::optimize($item['image'], 320, 400) }}" class="b-data-center-item__img"
                            alt="{{ $title }}">
                        <div class="b-data-center-item__title">{{ $title }}</div>
                    </a>
                </div>
            @endif
        @endforeach
    </div>
    {{-- END Data centers --}}

    @include('news._list_reviews')

    @include('news._categories')

    @include('news._list_people')

    {{-- START Investment --}}
    @if ($news_investment->count())
        <div class="b-heading">@lang('news.investment')</div>
        <div class="row">
            <div class="col-lg-4">
                @include('news._item_main', [
                    'item' => $news_investment->first(),
                    'class' => 'mb-4 mb-lg-2',
                ])
                @if ($item_2 = $news_investment->get(1))
                    @include('news._item_short', [
                        'item' => $item_2,
                        'class' => 'd-none d-lg-block',
                    ])
                @endif
            </div>

            @if ($news_investment->count() > 2)
                <div class="col-lg-4 d-none d-lg-block">
                    @foreach ($news_investment->slice(2, 5) as $item)
                        @include('news._item_short')
                    @endforeach
                </div>

                <!-- START Duplicate for mobile style -->
                <div class="col-12 d-lg-none">
                    <div class="row">
                        @foreach ($news_investment->slice(1, 6)->chunk(3) as $tree)
                            <div class="col {{ $loop->last ? 'd-none d-md-block' : '' }}">
                                @foreach ($tree as $item)
                                    @include('news._item_short')
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                    <div class="text-center mt-3">
                        <a href="{{ route('news') }}" class="btn btn-outline-primary px-4">@lang('home.view_all_news')</a>
                    </div>
                </div>
                <!-- END -->
            @endif

            @if ($stock_market = setting('stock_market'))
                <div class="col-lg-4">
                    <div class="b-investment-data">
                        <table class="table">
                            @foreach ($stock_market as $key => $item)
                                <tr>
                                    <td><strong>{{ $key }}</strong></td>
                                    <td>{{ $item['value'] }}</td>
                                    <td>
                                        @if ($item['change'] == 0)
                                            -
                                        @else
                                            <strong class="text-{{ $item['change'] > 0 ? 'success' : 'danger' }}">
                                                {{ ($item['change'] > 0 ? '+' : '') . round($item['change'], 3) }}
                                            </strong>
                                        @endif

                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            @endif
        </div>
    @endif
    {{-- END Investment --}}

    {{-- START Events --}}
    @if ($news_events->count())
        <div class="b-heading">@lang('news.events_at_month')</div>
        <div class="row b-home-custom-row* js-home-carousel">
            @foreach ($news_events as $item)
                {{-- @if ($loop->iteration == 3)
                    <div class="col" data-col="col">
                        <a href="#">
                            <img class="b-event-item__img" src="{{ asset('default/img/promotion/240x400.png') }}"
                                alt="">
                        </a>
                    </div>
                @endif --}}
                <div class="col-3" data-col="col-3">
                    <a href="{{ route('news.show', $item->alias) }}" class="b-event-item">
                        <img class="b-event-item__img" src="{{ ImageService::optimize($item->image, 320, 400) }}"
                            alt="{{ $item->title }}">
                        <div class="b-news-main-item__title">{{ $item->title }}</div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
    {{-- END Events --}}

    @if ($equipments->count())
        <div class="b-heading small*">@lang('equipments.home_miners')</div>
        <div class="b-equipment">
            <div class="b-equipment__box" style="padding: 20px*;">
                <table class="table b-fixed-column js-data-table">
                    <thead>
                        <tr>
                            <th>@lang('equipments.miner')</th>
                            <th>@lang('equipments.hashrate')</th>
                            <th>@lang('equipments.power')</th>
                            <th>@lang('equipments.rewards')</th>
                            <th>@lang('equipments.produced')</th>
                            <th>@lang('equipments.revenue')</th>
                            <th>@lang('equipments.daily_cost')</th>
                            <th>@lang('equipments.profits')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($equipments as $item)
                            <tr>
                                <td><a href="{{ route('equipments.show', $item->alias) }}"
                                        class="text-truncate b-equipment__title">{{ $item->translate('en')->title }}</a>
                                </td>
                                <td class="text-nowrap">
                                    <strong class="text-primary mr-1">{{ $item->hashrate }}</strong>
                                    @if ($unit = $item->coin->whattomine_unit)
                                        <span>{{ $unit }}</span>
                                    @endif
                                </td>
                                <td class="text-nowrap">
                                    <strong class="text-primary mr-1">{{ $item->power }}</strong><span>W</span>
                                </td>
                                <td data-order="{{ $item->profit_data['estimated_rewards'] ?? '0' }}">
                                    @if (!empty($item->profit_data['estimated_rewards']))
                                        {{ round($item->profit_data['estimated_rewards'], 3) }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td data-order="{{ $item->profit_data['btc_revenue'] ?? '0' }}">
                                    @if (!empty($item->profit_data['btc_revenue']))
                                        <strong
                                            class="text-primary mr-1">{{ $item->profit_data['btc_revenue'] }}</strong><span>BTC</span>
                                    @else
                                        -
                                    @endif
                                </td>
                                @php
                                    /** @var object $item */
                                    $revenue = parse_float($item->profit_data['revenue'] ?? '0');
                                    $cost = parse_float($item->profit_data['cost'] ?? '0');
                                    $profit = parse_float($item->profit_data['profit'] ?? '0');
                                @endphp
                                <td data-order="{{ $revenue }}">
                                    @if ($revenue)
                                        $<strong class="text-primary ml-1">{{ $revenue }}</strong>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td data-order="{{ $cost }}">
                                    @if ($cost)
                                        $<strong class="text-primary ml-1">{{ $cost }}</strong>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td data-order="{{ $profit }}">
                                    @if ($profit)
                                        <div
                                            class="b-equipment__electric-cost {{ $profit < 0 ? 'bg-danger' : '' }}">
                                            $ <span class="ml-1">{{ $profit }}</span>
                                        </div>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="text-center mt-3">
                    <a href="{{ route('equipments') }}"
                        class="btn btn-outline-primary px-4">@lang('equipments.see_all')</a>
                </div>
            </div>
        </div>
    @endif

    @component('components.page_description')
    @endcomponent

    <div class="js-ads-place"></div>

@endsection

@section('script')
    @parent

    <script type="module">
		/* js-pool-stats-nav */
        const $poolStatsNav = $(".js-pool-stats-nav");

        $poolStatsNav.find(".nav-link").click(function(){
            let code = $(this).data('code');

            $poolStatsNav.find(".nav-link").removeClass('active');
            $(this).addClass('active');

            $.ajax({
                url: `{{ route('pool_stats') }}`,
                method: 'GET',
                data: { code: code },
                beforeSend: function(request) {
                    $(".js-pool-stats-loader").show();
                },
                success: function(data) {
                    $(".js-pool-stats-loader").hide();
                    $(".js-pool-stats-box").html(data);
                }
            });
        });

        /* js-pool-stats-period-nav */
        const $poolStatsPeriodNav = $(".js-pool-stats-period-nav");

        $(document).on("click", ".js-pool-stats-period-nav .nav-link", function () {
            let code = $poolStatsNav.find(".active").data("code"),
                period = $(this).data("period");

            $.ajax({
                url: `{{ route('pool_stats_period') }}`,
                method: "GET",
                data: {
                    code: code,
                    period: period,
                },
                success: function (data) {
                    $(".js-pool-stats-period-box").html(data);
                },
            });
        });

        /* js-data-table */
        const $dataTable = $(".js-data-table");

        if ($dataTable.length) {
            $dataTable.DataTable({
                order: [[7, "desc"]],
                searching: false,
                paging: false,
                info: false,
                language: {
                    url: "/admin/libs/datatables/i18n/ru.json",
                },

                scrollX: true,
                scrollCollapse: true,
                fixedColumns: true,
            });
        }

        let initialized = false;

        $(window).bind("scroll", function() {
            if ($(this).scrollTop() > 300 && initialized == false) {
                $poolStatsNav.find(".nav-link:first").trigger('click');

                $.get("{{ route('subscribe') }}", function (data) {
                    $(".js-subscribe-place").replaceWith(data);

                    fullWidth();
                });

                /* $(".js-ads-place").replaceWith(`
                    <iframe class="d-none d-md-block" data-aa="2086741" loading="lazy" src="//ad.a-ads.com/2086741?size=728x90" style="width: 728px; height: 90px; border: 0px; padding: 0; overflow: hidden; margin: 40px auto 0;" />
                    <iframe class="d-md-none" data-aa="2094691" loading="lazy" src="//ad.a-ads.com/2094691?size=336x280" style="width: 336px; height: 280px; border: 0px; padding: 0; overflow: hidden; display: block; margin: 40px auto 0;" />
                `); */

                initialized = true;
            }
        });
    </script>
@endsection
