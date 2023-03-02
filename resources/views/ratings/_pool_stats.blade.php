<div class="row">
    @isset($latest_block->block_height)
        <div class="col-md-6 col-lg order-lg-1">
            <div class="b-ps-block-info">
                <div class="b-ps-block-info__heading">@lang('pool_stats.last_block')</div>
                <div class="b-ps-block-info__value">{{ number_format($latest_block->block_height, 0, '', ' ') }}</div>
                <div class="b-ps-block-info__heading">@lang('pool_stats.last_block_time')</div>
                <div class="b-ps-block-info__count-down" data-count-down="{{ $latest_block->time->format('d M Y H:i') }}">
                    <div><strong>00</strong> @lang('pool_stats.hours')</div>
                    <div><strong>00</strong> @lang('pool_stats.minutes')</div>
                    <div><strong>00</strong> @lang('pool_stats.seconds')</div>
                </div>
            </div>
        </div>
    @endisset

    <div class="col-md-6 col-lg order-lg-3 d-none d-md-block">
        @if (!empty($coin->cost_by_exchange))
            <div class="b-exchange-data">
                <table class="table">
                    <tbody>
                        @foreach ($coin->cost_by_exchange as $name => $item)
                            <tr>
                                <td>{{ $name }}</td>
                                <td>
                                    <strong class="text-{{ $item['price'] >= $item['old'] ? 'success' : 'danger' }}">
                                        ${{ crypto_number_format($item['price']) }}
                                    </strong>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    @if (!empty($coin->chart_data))
        <div class="col-12 col-lg-5 order-lg-2 d-none d-md-block">
            <div class="b-coin-chart">
                <div class="b-coin-chart__text">
                    @php
                        $chart_data = $coin->chart_data;

                        $first = reset($chart_data);
                        $last = end($chart_data);

                        $changes = $last - $first;
                        $percentages = round(100 - ($first * 100) / $last, 2);
                    @endphp
                    <div class="b-coin-chart__text-heading">{{ mb_strtoupper($coin->code) }} / USD @lang('pool_stats.last_24_hours')
                    </div>
                    <div class="b-coin-chart__text-value">${{ crypto_number_format($last, ' ', true) }}</div>
                    @if ($changes != 0)
                        <div class="d-flex justify-content-between text-{{ $changes > 0 ? 'success' : 'danger' }}">
                            <div class="b-coin-chart__text-changes">
                                {{ $changes > 0 ? '+' : '' }}{{ $percentages }}%
                            </div>
                            <div class="b-coin-chart__text-changes">
                                {{ $changes > 0 ? '+' : '' }}{{ crypto_number_format($changes, ' ', true) }}
                            </div>
                        </div>
                    @endif
                </div>
                <canvas id="chart-line" class="w-100" height="277"></canvas>
            </div>
        </div>
    @endif
</div>

<script src="/default/libs/chartjs/Chart.bundle.min.js"></script>
<script src="/default/libs/chartjs/chartjs-plugin-piechart-outlabels.js"></script>

@isset($latest_block->block_height)
    <script>
        function myConvertNumeric($numeric) {
            return `${$numeric}`.padStart(2, '0');
        }

        /* countDown */
        function countDown(time, e) {
            let countDownDate = new Date(time).getTime(),
                x = setInterval(() => {
                    let now = new Date().getTime(),
                        distance = now - countDownDate;

                    let days = Math.floor(distance / (1000 * 60 * 60 * 24)),
                        hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)),
                        minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60)),
                        seconds = (Math.floor((distance % (1000 * 60)) / 1000));

                    e.innerHTML = `
                    <div class="${!days ? 'd-none' : ''}"><strong>${myConvertNumeric(days)}</strong> days</div>
                    <div><strong>${myConvertNumeric(hours)}</strong> @lang('pool_stats.hours')</div>
                    <div><strong>${myConvertNumeric(minutes)}</strong> @lang('pool_stats.minutes')</div>
                    <div><strong>${myConvertNumeric(seconds)}</strong> @lang('pool_stats.seconds')</div>`;

                    if (distance < 0) {
                        clearInterval(x);
                        e.innerHTML = 'Завершено!';
                    }
                }, 1000);
        }

        document.querySelectorAll('[data-count-down]').forEach(e => {
            countDown(e.dataset.countDown, e);
        });
    </script>
@endisset

@if (!empty($coin->chart_data))
    <script>
        /* chart-line */
        Chart.defaults.LineWithLine = Chart.defaults.line;
        Chart.controllers.LineWithLine = Chart.controllers.line.extend({
            draw: function (ease) {
                Chart.controllers.line.prototype.draw.call(this, ease);

                if (this.chart.tooltip._active && this.chart.tooltip._active.length) {
                    var activePoint = this.chart.tooltip._active[0],
                        ctx = this.chart.ctx,
                        x = activePoint.tooltipPosition().x,
                        topY = this.chart.scales["y-axis-0"].top,
                        bottomY = this.chart.scales["y-axis-0"].bottom;

                    // Draw line
                    ctx.save();
                    ctx.beginPath();
                    ctx.moveTo(x, topY);
                    ctx.lineTo(x, bottomY);
                    ctx.lineWidth = 2;
                    ctx.strokeStyle = "{{ $changes > 0 ? '#34C85A' : '#cb444a' }}";
                    ctx.stroke();
                    ctx.restore();
                }
            },
        });

        Chart.defaults.global.defaultFontFamily = 'Open Sans';
        Chart.defaults.global.defaultFontSize = 12;
        Chart.defaults.global.defaultFontColor = '#000';

        const ctxLine = document.getElementById("chart-line").getContext("2d");

        let gradient = ctxLine.createLinearGradient(0, 0, 0, 400);
            gradient.addColorStop(0, "{{ $changes > 0 ? 'rgba(52, 200, 90, 0.3)' : 'rgba(203, 68, 74, 0.3)' }}");
            gradient.addColorStop(0.5, "rgba(255, 255, 255, 0.3)");

        const dataLine = {
            labels: [{!! "'" . implode("', '", array_keys($chart_data)) . "'" !!}],
            datasets: [
                {
                    data: [{{ implode(', ', $chart_data) }}],
                    lineTension: 0,
                    borderWidth: 4,
                    borderColor: "{{ $changes > 0 ? '#34C85A' : '#cb444a' }}",
                    backgroundColor: gradient,
                    // pointRadius: 0,
                    pointBorderWidth: 4,
                    pointBorderColor: "transparent",
                    pointBackgroundColor: "transparent",
                    pointHoverRadius: 10,
                    pointHoverBorderColor: "{{ $changes > 0 ? 'rgba(52, 200, 90, 0.3)' : 'rgba(203, 68, 74, 0.3)' }}",
                    pointStyle: "rectRounded",
                },
            ],
        };

        new Chart(ctxLine, {
            type: "LineWithLine",
            data: dataLine,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 0,
                },
                legend: {
                    display: false,
                },
                tooltips: {
                    callbacks: {
                        title: function (tooltipItem, data) {
                            return "$ " + data["datasets"][0]["data"][tooltipItem[0]["index"]];
                        },
                        label: function (tooltipItem, data) {
                            return data["labels"][tooltipItem["index"]];
                        },
                    },
                    intersect: false,
                    backgroundColor: "#FFF",
                    titleFontSize: 16,
                    titleFontColor: "#3455c3",
                    bodyFontColor: "#000",
                    bodyFontSize: 14,
                    displayColors: false,
                },
                scales: {
                    xAxes: [
                        {
                            gridLines: {
                                display: false,
                                drawBorder: false,
                            },
                            ticks: {
                                display: false,
                            },
                        },
                    ],
                    yAxes: [
                        {
                            gridLines: {
                                display: true,
                                color: "#E4E9F2",
                                drawBorder: false,
                            },
                            ticks: {
                                // display: false,
                                maxTicksLimit: 6,
                            },
                        },
                    ],
                },
            },
        });
    </script>
@endif

<div class="js-pool-stats-period-box">
    @include('ratings._pool_stats_period')
</div>
