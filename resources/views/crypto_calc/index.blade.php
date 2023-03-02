@extends('layouts.app')

@section('content')

    @component('components.breadcrumb')
        @slot('title', $page_title)
        @slot('subtitle', $page_subtitle)
        @if (Route::is('crypto_calc.coin'))
            @slot('title_active', $coin->title)
            @slot('items', [
                [
                'href' => route('crypto_calc'),
                'name' => __('common.crypto_calc'),
                ],
            ])
        @else
            @slot('title_active', __('common.crypto_calc'))
        @endif
    @endcomponent

    @if ($coins->count())
        <div class="b-equipment">
            <nav class="b-equipment__nav-wrap">
                <ul class="nav b-equipment__nav js-priority-nav">
                    <li><a class="nav-link @routeActive('crypto_calc')" href="{{ route('crypto_calc') }}">@lang('firmwares.all')</a></li>
                    @foreach ($coins as $item)
                        <li>
                            <a class="nav-link @if (request()->route()->alias == $item->alias) active @endif"
                                href="{{ route('crypto_calc.coin', $item->alias) }}">
                                <img width="16" height="16"
                                    src="{{ ImageService::optimize($item->image, 16, 16) }}" alt="{{ $item->title }}">
                                {{ $item->code }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            @isset($coin)
                @include('crypto_calc._coin', [
                    'coin' => $coin,
                    'hashrate_base' => $coin->code == 'zec' && $coin->whattomine_unit == 'h/s' ? 100000 : 100,
                ])
            @endisset

			<div class="b-heading text-center mt-5">@lang('crypto_calc.sub_heading')</div>
            <div class="row align-items-end justify-content-center js-calc-device-filter">
                <div class="col-6 col-lg-3 form-group">
                    <label for="input-electricity2">@lang('crypto_calc.electricity_cost')</label>
                    <div class="input-group">
                        <input type="number" step="0.1" min="0" value="{{ app()->getLocale() == 'ru' ? '4' : '0.1' }}" class="form-control" id="input-electricity2">
                        <label for="input-electricity2" class="input-group-prepend">
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
                    <label for="input-commission2">@lang('crypto_calc.pool_fee')</label>
                    <div class="input-group">
                        <input type="number" min="0" max="100" value="0" class="form-control" id="input-commission2">
                        <label for="input-commission2" class="input-group-prepend">
                            <div class="input-group-text">%</div>
                        </label>
                    </div>
                </div>
            </div>

            <div class="b-equipment__box">
                <table class="table table-striped b-fixed-column js-calc-device">
                    <thead>
                        <tr>
                            <th>@lang('equipments.miner')</th>
                            <th>@lang('equipments.power')</th>
                            <th>@lang('equipments.hashrate')</th>
                            <th>@lang('equipments.coins')</th>
                            <th>@lang('crypto_calc.expenses')</th>
                            <th>@lang('crypto_calc.income')<br>@lang('crypto_calc.per_day')</th>
                            <th>@lang('crypto_calc.income')<br>@lang('crypto_calc.per_month')</th>
                            <th class="text-center">@lang('equipments.buy')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($equipments as $item)
                            <tr class="js-equipment-row"
                                data-crypto-profit="{{ $item->profit_data['estimated_rewards'] ?? 0 }}"
                            >
                                <td><a href="{{ route('equipments.show', $item->alias) }}"
                                        class="text-truncate b-equipment__title">{{ $item->title }}</a></td>
                                <td class="text-nowrap">{{ $item->power }}</td>
                                <td class="text-nowrap">
                                    <strong class="text-primary mr-1">{{ $item->hashrate }}</strong>
                                    @if ($unit = $item->coin->whattomine_unit)
                                        <span>{{ $unit }}</sp>
                                    @endif
                                </td>
                                <td class="text-uppercase"><strong>{{ $item->coin->code }}</strong></td>
                                @php
                                    /** @var object $item */
                                    $revenue = parse_float($item->profit_data['revenue'] ?? '0');
                                @endphp
                                <td></td>
                                <td>{{ $revenue }}</td>
                                <td>{{ $revenue * 30 }}</td>
                                <td class="text-center">
                                    <a href="{{ route('equipments.show', $item->alias) }}" class="btn btn-outline-primary btn-sm">
                                        <img width="17" height="17" src="{{ asset('default/img/icons/arrow-right-long.svg') }}" alt="detailed">
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center">@lang('common.no_results')</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    @if (method_exists($equipments, 'hasPages') && $equipments->hasPages())
        <div class="mt-5" style="overflow: auto;">
            {{ $equipments->links() }}
        </div>
    @endif

    @include('inc.promotion_x')

    @component('components.page_description')
        @if (Route::is('crypto_calc.coin'))
            @slot('description', $page_description)
        @endif
    @endcomponent

@endsection

@section('script')
    @parent

    <script type="module">
        /* js-calc-device */
        const $calcDevice = $(".js-calc-device");

        if ($calcDevice.length) {
            /* js-currency-rate */
            $(".js-currency-rate .dropdown-item").on("click", function() {
                $calcDevice.DataTable().rows().invalidate("data").draw(false);
            });

            /* js-calc-device-filter */
            $(".js-calc-device-filter input").on("input click", function () {
                delay(function () {
                    $calcDevice.DataTable().rows().invalidate("data").draw(false);
                }, 500);
            });

            function getElectricityCost(value) {
                return $("#input-electricity2").val() / getCurrencyRate() * (value / 1000) * 24;
            }

            function getCryptoProfit(index) {
                return $calcDevice.find("tbody tr").eq(index).data("crypto-profit");
            }

            $.fn.dataTable.ext.order["dom-strong"] = function (settings, col) {
                return this.api()
                    .column(col, { order: "index" })
                    .nodes()
                    .map(function (td, i) {
                        return parseFloat($("strong", td).text());
                    });
            };

            $calcDevice.DataTable({
                columns: [
                    null,
                    { orderDataType: "dom-strong", type: "numeric" },
                    null,
                    null,
                    null,
                    { orderDataType: "dom-strong", type: "numeric" },
                    { orderDataType: "dom-strong", type: "numeric" },
                    null,
                ],
                columnDefs: [
                    {
                        targets: 1,
                        render: function (data, type, row) {
                            return `<strong class="text-primary mr-1">${data}</strong><span>W</span>`;
                        },
                    },
                    {
                        targets: 2,
                        orderable: false,
                    },
                    @isset($coin)
                    {
                        targets: 3,
                        visible: false
                    },
                    @endisset
                    {
                        targets: 4,
                        render: function (data, type, row) {
                            let electricity = getElectricityCost(row[1]);

                            if (electricity <= 0) return '-';

                            return showPrice(electricity * getCurrencyRate(), getCurrencyName()) + ` @lang('crypto_calc.per_day')<br>` +
                                   showPrice(electricity * 30 * getCurrencyRate(), getCurrencyName()) + ` @lang('crypto_calc.per_month')`;
                        },
                    },
                    {
                        targets: 5,
                        render: function (data, type, row, meta) {
                            if (data <= 0) return '-';

                            let electricity = getElectricityCost(row[1]),
                                cryptoProfit = getCryptoProfit(meta.row),
                                cryptoCode = $(row[3]).text().toUpperCase(),
                                colVal = data - electricity - (data * $("#input-commission2").val()) / 100;

                            return showPrice(colVal * getCurrencyRate(), getCurrencyName()) + `<div class="text-secondary">${cryptoProfit} ${cryptoCode}</div>`;
                        },
                    },
                    {
                        targets: 6,
                        render: function (data, type, row, meta) {
                            if (data <= 0) return '-';

                            let electricity = getElectricityCost(row[1]) * 30,
                                cryptoProfit = getCryptoProfit(meta.row),
                                cryptoCode = $(row[3]).text().toUpperCase(),
                                colVal = data - electricity - (data * $("#input-commission2").val()) / 100;

                            return showPrice(colVal * getCurrencyRate(), getCurrencyName()) + `<div class="text-secondary">${(cryptoProfit * 30).toFixed(6)} ${cryptoCode}</div>`;
                        },
                    },
                    {
                        targets: 7,
                        orderable: false,
                    },
                ],
                order: [[5, "desc"]],
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
    </script>
@endsection
