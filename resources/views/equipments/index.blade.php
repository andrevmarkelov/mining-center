@extends('layouts.app')

@section('content')

    @component('components.breadcrumb')
        @slot('title', $page_title)
        @slot('subtitle', $page_subtitle)
        @if (Route::is('equipments.coin'))
            @slot('title_active', $coin->title)
            @slot('items', [
                [
                'href' => route('equipments'),
                'name' => __('common.equipments'),
                ],
            ])
        @else
            @slot('title_active', __('common.equipments'))
        @endif
    @endcomponent

    @if ($coins->count())
        <div class="b-equipment">
            <nav class="b-equipment__nav-wrap">
                <ul class="nav b-equipment__nav js-priority-nav">
                    <li><a class="nav-link @routeActive('equipments')" href="{{ route('equipments') }}">@lang('firmwares.all')</a></li>
                    @foreach ($coins as $item)
                        <li>
                            <a class="nav-link @if (request()->route()->alias == $item->alias) active @endif"
                                href="{{ route('equipments.coin', $item->alias) }}">
                                <img width="16" height="16"
                                    src="{{ ImageService::optimize($item->image, 16, 16) }}" alt="{{ $item->title }}">
                                {{ $item->code }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </nav>

            <form class="row align-items-end justify-content-center" style="margin: -15px 0 15px;">
                @if ($manufacturers->count())
                    <div class="col-sm-6 col-lg-4 col-xl-3 form-group">
                        <label for="input-manufacturer">@lang('equipments.manufacturer')</label>
                        <select name="manufacturer" class="form-control js-select2" id="input-manufacturer">
                            <option value="">@lang('common.choose_value')</option>
                            @foreach ($manufacturers as $title)
                                <option @if (request()->input('manufacturer') == $title) selected @endif value="{{ $title }}">{{ ucfirst($title) }}</option>
                            @endforeach
                        </select>
                    </div>
                @endif
                @isset($coin)
                    <div class="col-sm-6 col-lg-4 col-xl-3 form-group">
                        <label for="input-hashrate">@lang('equipments.hashrate') ({{ $coin->whattomine_unit }})</label>
                        <div class="px-4 py-3">
                            <input type="hidden" name="hashrate_from"
                                value="{{ request()->input('hashrate_from', $equipments_total->min('hashrate')) }}">
                            <input type="hidden" name="hashrate_to"
                                value="{{ request()->input('hashrate_to', $equipments_total->max('hashrate')) }}">
                            <div id="input-hashrate"></div>
                        </div>
                    </div>
                @else
                    @if ($algorithms->count())
                        <div class="col-sm-6 col-lg-4 col-xl-3 form-group">
                            <label for="input-algorithm">@lang('equipments.algorithm')</label>
                            <select name="algorithm" class="form-control js-select2" id="input-algorithm">
                                <option value="">@lang('common.choose_value')</option>
                                @foreach ($algorithms as $title)
                                    <option @if (request()->input('algorithm') == $title) selected @endif value="{{ $title }}">{{ ucfirst($title) }}</option>
                                @endforeach
                            </select>
                        </div>
                    @endif
                @endisset
                <div class="col-sm-6 col-lg-4 col-xl-3 form-group">
                    <label for="input-power">@lang('crypto_calc.consumption') (@lang('crypto_calc.consumption_unit'))</label>
                    <div class="px-4 py-3">
                        <input type="hidden" name="power_from"
                            value="{{ request()->input('power_from', $equipments_total->min('power')) }}">
                        <input type="hidden" name="power_to"
                            value="{{ request()->input('power_to', $equipments_total->max('power')) }}">
                        <div id="input-power"></div>
                    </div>
                </div>
                <div class="col-sm-6 col-lg-4 col-xl-3 form-group mt-2 mt-lg-0">
                    <div class="btn-group btn-block">
                        <button type="submit" class="btn btn-primary btn-block">@lang('equipments.apply')</button>
                        @if (!empty(request()->all()))
                            <a href="{{ url()->current() }}" class="btn btn-outline-primary py-0"
                                style="font-size: 25px; border-left-color: white;">×</a>
                        @endif
                    </div>
                </div>
            </form>

            <div class="b-equipment__box">
                <table class="table table-striped b-fixed-column js-data-table">
                    <thead>
                        <tr>
                            <th width="1">#</th>
                            <th>@lang('equipments.miner')</th>
                            <th>@lang('equipments.coins')</th>
                            <th>@lang('equipments.hashrate')</th>
                            <th>@lang('equipments.power')</th>
                            <th>@lang('equipments.rewards')</th>
                            <th>@lang('equipments.produced')</th>
                            <th>@lang('equipments.revenue')<br>0.1/kWh</th>
                            <th>@lang('equipments.daily_cost')</th>
                            <th>@lang('equipments.profits')</th>
                            <th class="text-center">@lang('equipments.buy')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($equipments as $item)
                            <tr class="js-equipment-row" data-id="{{ $item->id }}">
                                <td>{{ $loop->iteration }}</td>
                                <td><a href="{{ route('equipments.show', $item->alias) }}"
                                        class="text-truncate b-equipment__title">{{ $item->title }}</a></td>
                                <td class="text-uppercase"><strong>{{ $item->coin->code }}</strong></td>
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
                                        $<strong
                                            class="text-{{ $profit > 0 ? 'primary' : 'danger' }} ml-1">{{ $profit }}</strong>
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('equipments.show', $item->alias) }}" class="btn btn-outline-primary btn-sm">
                                        <span class="d-inline-block mr-1" style="width: 17px;">
                                            <img width="17" height="17" src="{{ asset('default/img/icons/arrow-right-long.svg') }}" alt="detailed">
                                        </span>
                                        @lang('equipments.buy')
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center">@lang('common.no_results')</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @include('inc.equipment_order')
    @endif

    @if (method_exists($equipments, 'hasPages') && $equipments->hasPages())
        <div class="mt-5" style="overflow: auto;">
            {{ $equipments->links() }}
        </div>
    @endif

    @include('inc.promotion_x')

    <form id="equipment-form" action="{{ route('equipments.send') }}" method="post" class="b-theme-form">
        @csrf
        <div class="b-heading text-center mx-auto" style="max-width: 840px;">@lang('equipments.form_heading')</div>
        <p class="form-group b-theme-form__text">@lang('equipments.form_note')</p>
        <div class="b-theme-form__inner">
            <div class="form-group">
                <input placeholder="@lang('common.name')*" type="text" name="name" class="form-control"
                    id="input-name<?= $name = uniqid() ?>">
            </div>
            <div class="form-group">
                <input placeholder="@lang('common.email')*" type="email" name="email" class="form-control"
                    id="input-email<?= $email = uniqid() ?>">
            </div>
            <div class="form-group">
                <input placeholder="Telegram / Whatsapp" type="text" name="telegram" class="form-control"
                    id="input-telegram<?= $telegram = uniqid() ?>">
            </div>
            <div class="form-group">
                <textarea placeholder="@lang('common.comment')" name="comment" class="form-control"
                    id="input-comment<?= $comment = uniqid() ?>"></textarea>
            </div>
            <div class="text-center">
                {!! NoCaptcha::renderJs() !!}
                <button class="btn btn-primary btn-lg b-theme-form__btn g-recaptcha"
                    data-callback="equipmentForm"
                    data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"
                >@lang('common.send')</button>
            </div>
        </div>
    </form>

    <div class="modal fade b-theme-modal" id="modal-equipment-quick-view">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="b-theme-modal__close" data-dismiss="modal">
                    <img src="{{ asset('default/img/icons/close.svg') }}" alt="">
                </div>
                <div class="modal-body px-4 js-quick-view-content"></div>
            </div>
        </div>
    </div>

    @component('components.page_description')
        @if (Route::is('equipments.coin'))
            @slot('description', $page_description)
        @endif
    @endcomponent

@endsection

@section('script')
    @parent

    <script type="module">
        // js-equipment-row
        const $equipmentRow = $('.js-equipment-row');

        $equipmentRow.on('click', function(e) {
            if ($(e.target).closest('a').length) return;

            $.ajax({
                url: '{{ route('equipments.quick_view') }}',
                method: 'GET',
                dataType: 'html',
                data: {
                    'id': $(this).data('id'),
                },
                success: function(html) {
                    $('.js-quick-view-content').html(html);

                    $('#modal-equipment-quick-view').modal('show');
                },
                error: function(error) {
                    alert('Send error');
                }
            });
        });

        // js-data-table
        const $dataTable = $(".js-data-table");

        if ($dataTable.find('tbody tr:first-child td').length > 1) {
            const $intDataTable = $dataTable.DataTable({
                columnDefs: [
                    {
                        orderable: false,
                        targets: [3],
                    },
                    {
                        orderable: false,
                        targets: [10],
                    },
                ],
                order: [[9, "desc"]],
                searching: false,
                paging: false,
                info: false,
                language: {
                    url: "/admin/libs/datatables/i18n/ru.json",
                },

                scrollX: true,
                scrollCollapse: true,
                fixedColumns: {
                    left: 2,
                }
            });

            $intDataTable.on("order.dt search.dt", function () {
                $intDataTable
                    .column(0, { search: "applied", order: "applied" })
                    .nodes()
                    .each(function (cell, i) {
                        cell.innerHTML = i + 1;
                    });
            }).draw();
        }

        @isset($coin)
        // input-hashrate
        const hashrateFrom = document.querySelector('[name="hashrate_from"]'),
            hashrateTo = document.querySelector('[name="hashrate_to"]');

        const sliderHashrate = noUiSlider.create(document.getElementById("input-hashrate"), {
            range: {
                min: {{ $equipments_total->min('hashrate') }},
                max: {{ $equipments_total->max('hashrate') }},
            },
            start: [parseInt(hashrateFrom.value), parseInt(hashrateTo.value)],
            connect: true,
            tooltips: true,
            pips: {
                mode: "steps",
                stepped: true,
                density: 4,
            },
        });

        sliderHashrate.on("change", function() {
            hashrateFrom.value = sliderHashrate.get()[0];
            hashrateTo.value = sliderHashrate.get()[1];
        });
        @endisset

        // input-power
        const powerFrom = document.querySelector('[name="power_from"]'),
            powerTo = document.querySelector('[name="power_to"]');

        const sliderPower = noUiSlider.create(document.getElementById("input-power"), {
            range: {
                min: {{ $equipments_total->min('power') }},
                max: {{ $equipments_total->max('power') }},
            },
            start: [parseInt(powerFrom.value), parseInt(powerTo.value)],
            connect: true,
            tooltips: true,
            pips: {
                mode: "steps",
                stepped: true,
                density: 4,
            },
        });

        sliderPower.on("change", function() {
            powerFrom.value = sliderPower.get()[0];
            powerTo.value = sliderPower.get()[1];
        });
    </script>
@endsection
