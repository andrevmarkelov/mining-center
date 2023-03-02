<div class="b-header__bottom">
    @if ($binance_link = setting('binance_link'))
        <a rel="nofollow" target="_blank" href="{{ $binance_link }}"
            class="btn btn-outline-dark btn-sm order-xl-last b-header__ref-link">
            <img width="80" height="16" src="{{ asset('default/img/binance.svg') }}" alt="binance">
        </a>
    @endif
    @if (isset($coins->first()->profit_per_unit['exchange_rate']))
        <div class="b-price-schedule">
            <a rel="nofollow" target="_blank" href="{{ setting('binance_link') }}" class="b-price-schedule__inner">
                @php
                    $btc_rate = $coins->first()->profit_per_unit['exchange_rate'] ?? 0;
                    $btc_rate24 = $coins->first()->profit_per_unit['exchange_rate24'] ?? 0;
                @endphp
                @for ($i = 0; $i < 4; $i++)
                    @foreach ($coins as $item)
                        @php
                            $to_btc = $item->code == 'btc' || isset($item->profit_per_unit['not_mining']) ? 1 : $btc_rate;
                            $to_btc24 = $item->code == 'btc' || isset($item->profit_per_unit['not_mining']) ? 1 : $btc_rate24;

                            $item_rate = $to_btc * ($item->profit_per_unit['exchange_rate'] ?? '1');
                            $item_rate24 = $to_btc24 * ($item->profit_per_unit['exchange_rate24'] ?? '0');
                            $change = round(100 - ($item_rate24 * 100) / $item_rate, 2);
                        @endphp
                        <div class="b-price-schedule__item">
                            @if ($item->image)
                                <figure class="b-price-schedule__img-wrap">
                                    <img width="20" height="20" src="{{ $item->image }}"
                                        alt="{{ $item->title }}">
                                </figure>
                            @endif
                            <strong class="b-price-schedule__value">${{ crypto_number_format($item_rate) }}</strong>
                            <span class="text-{{ $change > 0 ? 'success' : 'danger' }} b-price-schedule__change">{{ $change }}%</span>
                        </div>
                        <div class="b-price-schedule__divider">|</div>
                    @endforeach
                @endfor
            </a>
        </div>
    @endif
</div>
