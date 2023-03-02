<div class="b-widget-page">
    <div class="b-page-header text-center mb-0 js-full-width">
        <h1 class="b-page-header__title">{{ $page->title }}</h1>
        <div class="b-page-header__desc">{!! $page->subtitle !!}</div>
    </div>

    @if ($coins->count() && !empty(setting('currency_rate')))
        <div class="b-widget-page__form">

            <div class="row align-items-center form-group js-main-row">
                <div class="col-sm">
                    <div class="input-group">
                        <label class="input-group-prepend">
                            <div class="input-group-text pl-2 pr-0">
                                <img width="30" height="30" src="{{ $coins->first()->image }}" alt="{{ $coins->first()->title }}">
                            </div>
                        </label>
                        <select class="form-control js-primary-rate">
                            @php
                                $btc_rate = $coins->first()->profit_per_unit['exchange_rate'] ?? '0';
                            @endphp
                            @foreach ($coins as $item)
                                <option
                                    data-code="{{ strtoupper($item->code) }}"
                                    data-img="{{ $item->image }}"
                                    value="{{ (($loop->first || isset($item->profit_per_unit['not_mining'])) ? 1 : $btc_rate) * ($item->profit_per_unit['exchange_rate'] ?? '0') }}">
                                    {{ $item->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col col-sm-auto text-center py-3 p-sm-2 js-swap-rate" style="cursor: pointer;">
                    <img width="28" height="28" src="{{ asset('default/img/icons/swap.svg') }}" alt="swap">
                </div>
                <div class="col-sm">
                    <div class="input-group">
                        <label class="input-group-prepend">
                            <div class="input-group-text pl-2 pr-0">
                                <img width="30" height="30" src="/default/img/icons/currency/usd.svg" alt="usd">
                            </div>
                        </label>
                        <select class="form-control form-control-lg js-secondary-rate">
                            @foreach (array_reverse(setting('currency_rate', [])) as $key => $item)
                                <option value="{{ $item }}">{{ $key }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <input class="form-control form-control-lg js-amount" value="1" type="number" placeholder="Укажите сумму">
            </div>
            <div class="h1 js-result"></div>

        </div>
    @endif
</div>

@component('components.page_description')
    @slot('description', $page->description)
@endcomponent

@section('script')
    @parent

    <script type="module">
        function formatState(state) {
            if (!state.id) {
                return state.text;
            }

            let currentSrc = $(state.element).data("img");

            let $state = $(
                `<div class="d-flex">
                    <div style="width: 25px;"><img class="${!currentSrc ? "d-none" : ""
                    }" width="25" height="25" loading="lazy" src="${currentSrc}"></div>
                    <div class="pl-2">
                        ${state.text}<small class="text-secondary d-block">${$(state.element).data("code")}</small>
                    </div>
                </div>`
            );

            return $state;
        }

        const $primary = $(".js-primary-rate"),
            $secondary = $(".js-secondary-rate"),
            $amount = $(".js-amount"),
            $result = $(".js-result"),
            $swapRate = $(".js-swap-rate");

        $primary.select2({
            theme: "bootstrap4",
            containerCssClass: "select2-lg",
            templateResult: formatState,
        });

        $swapRate.on("click", function () {
            $(this).toggleClass("active");

            $(".js-main-row").toggleClass("flex-row-reverse");

            $primary.change();
        });

        $primary
            .add($secondary)
            .add($amount)
            .on("change input", function () {
                let primaryVal = $primary.val(),
                    secondaryVal = $secondary.val(),
                    amountVal = $amount.val(),
                    primaryCode = $primary.find("option:selected").data("code"),
                    secondaryCode = $secondary.find("option:selected").text(),
                    primarySrc = $primary.find("option:selected").data("img");

                let $primaryImg = $primary.parents(".input-group").find(".input-group-text img").hide();

                if (primarySrc) {
                    $primaryImg.show().attr("src", primarySrc);
                }

                $secondary
                    .parents(".input-group")
                    .find(".input-group-text img")
                    .attr("src", `/default/img/icons/currency/${secondaryCode.toLowerCase()}.svg`);

                if (amountVal && amountVal > 0) {
                    if ($swapRate.hasClass('active')) {
                        let result = 1 / secondaryVal / primaryVal * amountVal;

                        amountVal = new Intl.NumberFormat("de-DE", { style: "currency", currency: secondaryCode }).format(amountVal);

                        $result.html(`${amountVal} = <strong>${result.toFixed(10)} ${primaryCode}</strong>`);
                    } else {
                        let result = primaryVal * secondaryVal * amountVal;

                        result = new Intl.NumberFormat("de-DE", { style: "currency", currency: secondaryCode }).format(result);

                        $result.html(`${amountVal} ${primaryCode} = <strong>${result}</strong>`);
                    }
                }
            });

        $primary.change();
    </script>
@endsection
