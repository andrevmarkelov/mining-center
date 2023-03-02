<div class="b-heading text-center">
    @lang('crypto_calc.heading') {{ $add_to_heading ?? '' }}
    @if (isset($equipment->add_title) && $equipment->add_title)
        <div class="h4 my-2">{{ $equipment->add_title }}</div>
    @endif
</div>
<div class="row align-items-end justify-content-center js-calc-period-filter">
    <div class="col-6 col-lg-3 form-group">
        <label for="input-electricity">@lang('crypto_calc.electricity_cost')</label>
        <div class="input-group">
            <input type="number" step="0.1" min="0" value="{{ app()->getLocale() == 'ru' ? '4' : '0.1' }}" class="form-control"
                id="input-electricity">
            <label for="input-electricity" class="input-group-prepend">
                <div class="input-group-text pr-2">@lang('crypto_calc.electricity_unit')</div>
            </label>
            @if (!empty($currency_rate = setting('currency_rate')))
                <div class="input-group-append js-currency-rate">
                    <button class="btn btn-outline-secondary {{ app()->getLocale() == 'ru' ? 'btn-sm dropdown-toggle' : 'px-3' }}" type="button"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">{{ app()->getLocale() == 'ru' ? '₽' : '$' }}</button>
                    @if (app()->getLocale() == 'ru')
                        <div class="dropdown-menu" style="min-width: auto;">
                            <div class="dropdown-item" data-rate="{{ $currency_rate['USD'] }}">$</div>
                            <div class="dropdown-item active" data-rate="{{ $currency_rate['RUB'] }}">₽</div>
                        </div>
                    @endif
                </div>
            @endif
        </div>
    </div>
    <div class="col-6 col-lg-3 form-group">
        <label for="input-hashrate">@lang('equipments.hashrate')</label>
        <div class="input-group">
            <input type="number" min="0" value="{{ $hashrate_base }}" class="form-control"
                id="input-hashrate">
            <label for="input-hashrate" class="input-group-prepend">
                <div class="input-group-text">{{ $coin->whattomine_unit }}</div>
            </label>
        </div>
    </div>
    <div class="col-6 col-lg-3 form-group">
        <label for="input-consumption">@lang('crypto_calc.consumption')</label>
        <div class="input-group">
            <input type="number" min="0" value="{{ $consumption_base ?? 0 }}" class="form-control"
                id="input-consumption">
            <label for="input-consumption" class="input-group-prepend">
                <div class="input-group-text">@lang('crypto_calc.consumption_unit')</div>
            </label>
        </div>
    </div>
    <div class="col-6 col-lg-3 form-group">
        <label for="input-commission">@lang('crypto_calc.pool_fee')</label>
        <div class="input-group">
            <input type="number" min="0" max="100" value="0" class="form-control"
                id="input-commission">
            <label for="input-commission" class="input-group-prepend">
                <div class="input-group-text">%</div>
            </label>
        </div>
    </div>
    @if(isset($coins_one_algorithm) && count($coins_one_algorithm) > 1)
        <div class="col-md-6 col-lg-4 col-xl-3 form-group">
            <label>@lang('equipments.coins')</label>
            <div class="btn-group btn-block js-coin-revenue">
                <button class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                    {{ $coin->title }}
                </button>
                <div class="dropdown-menu w-100 p-0">
                    @foreach ($coins_one_algorithm as $item)
                        @php
                            $crypto_revenue = parse_float($item->profit_per_unit['estimated_rewards'] ?? '0');
                            $revenue = parse_float($item->profit_per_unit['revenue'] ?? '0');
                        @endphp
                        <button class="dropdown-item px-3 py-2 d-flex align-items-center border-bottom"
                            data-revenue='{!! json_encode([
                                $crypto_revenue / 10000 / 24,
                                $revenue / 10000 / 24,
                                $item->code,
                                $item->title,
                            ]) !!}'>
                            <img width="32" height="32"
                                src="{{ ImageService::optimize($item->image, 32, 32) }}" alt="{{ $item->title }}">
                            <div class="pl-2">
                                {{ $item->title }}
                                <small class="text-secondary d-block text-uppercase">{{ $item->code }}</small>
                            </div>
                        </button>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>

<div class="b-equipment__box">
    <table class="table table-striped b-fixed-column js-calc-period">
        <thead>
            <tr>
                <th>@lang('crypto_calc.period')</th>
                <th>@lang('crypto_calc.reward')</th>
                <th>@lang('crypto_calc.income')</th>
                <th>@lang('crypto_calc.expenses')</th>
                <th>@lang('crypto_calc.profit')</th>
            </tr>
        </thead>
        <tbody>
            @foreach ([1, 24, 168, 720] as $item)
                <tr data-period="{{ $item }}">
                    <td>{{ $item }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

@php
    $crypto_revenue = parse_float($coin->profit_per_unit['estimated_rewards'] ?? '0');
    $revenue = parse_float($coin->profit_per_unit['revenue'] ?? '0');
@endphp

@section('script')
    @parent

    <script type="module">
        /* js-calc-period */
        const $calcPeriod = $(".js-calc-period");

        calcPeriod({{ $crypto_revenue / 10000 / 24 }}, {{ $revenue / 10000 / 24 }}, '{{ $coin->code }}');

        /* js-currency-rate */
        $(".js-currency-rate .dropdown-item").on("click", function() {
            $calcPeriod.DataTable().rows().invalidate("data").draw(false);
        });

        /* js-calc-period-filter */
        $(".js-calc-period-filter input, .js-currency-rate .dropdown-item").on("input click", function () {
            delay(function () {
                $calcPeriod.DataTable().rows().invalidate("data").draw(false);
            }, 500);
        });

        function getPerid(index) {
            return $calcPeriod.find("tbody tr").eq(index).data("period");
        }

        function calcPeriod(incomePerUnit, cashIncomePerUnit, coinCode) {
            if ($calcPeriod.length) {
                $calcPeriod.DataTable({
                    columnDefs: [
                        {
                            targets: 0,
                            render: function (data, type) {
                                switch (data) {
                                    case "1":
                                        return "1 @lang('crypto_calc.hour')";
                                        break;
                                    case "24":
                                        return "1 @lang('crypto_calc.day')";
                                        break;
                                    case "168":
                                        return "1 @lang('crypto_calc.week')";
                                        break;
                                    case "720":
                                        return "1 @lang('crypto_calc.month')";
                                        break;
                                }

                                return data;
                            },
                        },
                        {
                            data: 0,
                            targets: 1,
                            render: function (data, type, row, meta) {
                                let hashrate = $("#input-hashrate").val();
                                let cryptoIncome = getPerid(meta.row) * incomePerUnit * hashrate;

                                if (!cryptoIncome) return '-';
                                return cryptoIncome.toFixed(10) + " " + coinCode.toUpperCase();
                            },
                        },
                        {
                            data: 0,
                            targets: 2,
                            render: function (data, type, row, meta) {
                                let hashrate = $("#input-hashrate").val();
                                let cashIncome = getPerid(meta.row) * cashIncomePerUnit * hashrate;

                                return showPrice(cashIncome * getCurrencyRate(), getCurrencyName());
                            },
                        },
                        {
                            data: 0,
                            targets: 3,
                            render: function (data, type, row, meta) {
                                let electricity = $("#input-electricity").val() / getCurrencyRate(),
                                    consumption = $("#input-consumption").val();

                                let costs = getPerid(meta.row) * electricity * (consumption / 1000);

                                if (!costs) return '-';

                                return `${getCurrencyName()} <strong class="text-danger">${(costs * getCurrencyRate()).toFixed(2)}</strong>`;
                            },
                        },
                        {
                            data: 0,
                            targets: 4,
                            render: function (data, type, row, meta) {
                                let commission = $("#input-commission").val(),
                                    electricity = $("#input-electricity").val() / getCurrencyRate(),
                                    hashrate = $("#input-hashrate").val(),
                                    consumption = $("#input-consumption").val();

                                let cashIncome = getPerid(meta.row) * cashIncomePerUnit * hashrate,
                                    costs = getPerid(meta.row) * electricity * (consumption / 1000),
                                    cashProfit = cashIncome - costs - (cashIncome * commission) / 100;

                                return showPrice(cashProfit * getCurrencyRate(), getCurrencyName());
                            },
                        },
                    ],

                    ordering: false,
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
        }

        /* js-coin-revenue */
        $(".js-coin-revenue .dropdown-item").on('click', function() {
            let revenueData = $(this).data('revenue');

            $calcPeriod.DataTable().destroy();

            $(this).parents('.btn-group').find('.dropdown-toggle').text(revenueData[3]);

            calcPeriod(revenueData[0], revenueData[1], revenueData[2]);
        });
    </script>
@endsection
