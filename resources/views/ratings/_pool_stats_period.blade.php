@if ($pool_stats->count())
    {{-- START b-ps-data --}}
    <div class="b-ps-data">
        <div class="row justify-content-between">
            <div class="col-lg-6 b-ps-data__col-left">
                <table class="table b-fixed-column js-pool-stats-table">
                    <thead>
                        <tr>
                            <th>
                                <span class="d-md-none">#</span>
                                <span class="d-none d-md-block">@lang('pool_stats.ranking')</span>
                            </th>
                            <th>@lang('pool_stats.pools')</th>
                            <th class="text-right">@lang('pool_stats.hashrate')</th>
                            <th class="text-right">@lang('pool_stats.share')</th>
                            <th class="text-right">@lang('pool_stats.blocks_mined')</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td></td>
                            <th><strong style="vertical-align: middle;">@lang('pool_stats.network')</strong></th>
                            <td class="text-right">{{ RatingService::hashRateUnit($pool_stats->sum('hashrate')) }}</td>
                            <td class="text-right">100.00%</td>
                            <td class="text-right">{{ $total_block_count = $pool_stats->sum('block_count') }}</td>
                        </tr>
                        @foreach ($pool_stats as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <th>
                                    @php
                                        $image = '/default/img/temporary/pool-stats.svg';

                                        foreach (['svg', 'jpg', 'jpeg', 'png', 'pjpeg'] as $key => $ext) {
                                            if (file_exists(storage_path("app/public/photos/shares/pool_stats/$item->miner.$ext"))) {
                                                $image = "/storage/photos/shares/pool_stats/$item->miner.$ext";
                                                break;
                                            }
                                        }
                                    @endphp
                                    <div class="d-flex align-items-center">
                                        <div class="b-ps-data__icon">
                                            <img loading="lazy" width="16" height="16" src="{{ ImageService::optimize($image, 16, 16) }}" alt="{{ $item->miner }}">
                                        </div>
                                        {{ str_replace('Foundry USA Pool', 'Foundry', $item->miner) }}
                                    </div>
                                </th>
                                <td class="text-right">{{ RatingService::hashRateUnit($item->hashrate) }}</td>
                                <td class="text-right">
                                    {{ number_format(($item->block_count * 100) / $total_block_count, 2, '.', '') }}%
                                </td>
                                <td class="text-right">{{ $item->block_count }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-lg-6 b-ps-data__col-right order-first order-lg-last">
                <div class="b-ps-data__nav">
                    @php
                        function activePeriod($period)
                        {
                            return request()->input('period') == $period ? 'active' : '';
                        }
                    @endphp
                    <nav class="nav b-ps-data__nav-inner js-pool-stats-period-nav">
                        <button type="button" data-period="24"
                            class="nav-link {{ !request()->input('period') ? 'active' : activePeriod('24') }}">
                            24 @lang('pool_stats.nav_hours')
                        </button>
                        <button type="button" data-period="72" class="nav-link {{ activePeriod('72') }}">
                            3 @lang('pool_stats.nav_days')
                        </button>
                        <button type="button" data-period="168" class="nav-link {{ activePeriod('168') }}">
                            1 @lang('pool_stats.nav_week')
                        </button>
                        <button type="button" data-period="720" class="nav-link {{ activePeriod('720') }}">
                            1 @lang('pool_stats.nav_month')
                        </button>
                        <button type="button" data-period="8760" class="nav-link {{ activePeriod('8760') }}">
                            1 @lang('pool_stats.nav_year')
                        </button>
                    </nav>
                </div>
                <div>
                    <canvas id="chart-diagram" height="205"></canvas>
                </div>
            </div>
        </div>
    </div>
    {{-- END b-ps-data --}}

    @php
        $diagram_pool_stats = $pool_stats->where('block_count', '>', ($total_block_count * 3) / 100);
    @endphp

    <script>
        /* chart-diagram */
        const ctxDiagram = document.getElementById("chart-diagram").getContext("2d");

        const dataDiagram = {
            labels: [{!! "'" . str_replace('Foundry USA Pool', 'Foundry', $diagram_pool_stats->pluck('miner')->implode("', '")) . "'" !!}],
            datasets: [{
                data: [{{ $diagram_pool_stats->pluck('block_count')->implode(', ') }}],
                backgroundColor: [
                    "#8CA9D3",
                    "#199ADE",
                    "#7383C1",
                    "#222B45",
                    "#E4E9F2",
                    "#3455C3",
                    "#4484E6",
                    "#C5CEE0",
                    "#7A59B6",
                    "#625ECE",
                ],
                borderWidth: false,
            }],
        };

        let isMobile = false;

        if (window.matchMedia("(max-width: 767px)").matches) {
            isMobile = true;
        }

        new Chart(ctxDiagram, {
            type: "doughnut",
            data: dataDiagram,
            options: {
                responsive: true,
                // maintainAspectRatio: false,
                cutoutPercentage: 35,
                animation: {
                    duration: 0,
                },
                legend: {
                    display: false,
                },
                layout: {
                    padding: {
                        top: isMobile ? 35 : 65,
                        bottom: isMobile ? 25 : 45,
                    },
                },
                tooltips: {
                    callbacks: {
                        label: function(tooltipItem, data) {
                            let index = tooltipItem.index,
                                label = data.labels[index],
                                value = data.datasets[0].data[index];

                            return `${label}: ${value} blocks`;
                        }
                    }
                },
                plugins: {
                    outlabels: {
                        // text: (data) => {
                        //     return ` ${data.labels[data.dataIndex]} ${Math.floor(data.percent * 100 + 0.5)}% `;
                        // },
                        text: '%l %p',
                        color: "#222B45",
                        stretch: isMobile ? 15 : 40,
                        lineWidth: 1,
                        font: {
                            resizable: true,
                            minSize: isMobile ? 9 : 12,
                            weight: "bold",
                            family: "Open Sans"
                        },
                        padding: {
                            top: 0,
                            bottom: 0,
                            left: 3,
                            right: 3,
                        },
                        backgroundColor: 'white',
                    },
                },
            },
        });

        /* js-pool-stats-table */
        const $poolStatsTable = $(".js-pool-stats-table");

        if ($poolStatsTable.length) {
            $poolStatsTable.DataTable({
                columnDefs: [{
                    orderable: false,
                    targets: [0, 1, 2, 3, 4],
                }],
                searching: false,
                paginate: false,
                // bLengthChange: false,
                // pagingType: 'simple',
                info: false,
                language: {
                    url: "/admin/libs/datatables/i18n/{{ app()->getLocale() }}.json",
                },

                scrollX: true,
                scrollCollapse: true,
                fixedColumns: {
                    left: 2,
                },
            });
        }
    </script>
@endif
