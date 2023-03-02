@extends('layouts.app')

@section('content')

    @component('components.breadcrumb')
        @slot('title', $coin->meta_h1 ?: $coin->title)
        @slot('subtitle', $coin->subtitle)
        @slot('title_active', __('common.ratings') . ' - ' . $coin->title)
    @endcomponent

    {{-- <button class="btn btn-primary px-5 mb-4" data-toggle="modal" data-target="#modal-add-pool">@lang('ratings.add_pool')</button>

    <div class="modal fade b-theme-modal" id="modal-add-pool">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="b-theme-modal__close" data-dismiss="modal">
                    <img src="{{ asset('default/img/icons/close.svg') }}" alt="">
                </div>
                <div class="modal-body">
                    <form action="{{ route('ratings.send') }}" method="post" class="b-theme-form js-ajax-form">
                        @csrf
                        <div class="b-heading mt-0">@lang('ratings.add_pool')</div>
                        <div class="form-group">
                            <input placeholder="@lang('common.name')*" type="text" name="name" class="form-control"
                                id="input-name">
                        </div>
                        <div class="form-group">
                            <input placeholder="@lang('ratings.contact')*" type="text" name="contact" class="form-control"
                                id="input-contact">
                        </div>
                        <div class="form-group">
                            <input placeholder="@lang('ratings.pool')" type="text" name="pool" class="form-control"
                                id="input-pool">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-lg b-theme-form__btn">@lang('common.add')</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div> --}}

    <div class="b-pool-rating">
        {{-- <div class="b-pool-rating__heading">
            @if ($coin->image)
                <img src="{{ ImageService::optimize($coin->image, 35, 35) }}" alt="{{ $coin->title }}">
            @endif
            {{ $coin->title }} (<span class="text-uppercase">{{ $coin->code }}</span>)
        </div> --}}
        <table class="table table-striped b-fixed-column js-data-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th class="text-uppercase">{{ $coin->algorithm->title }}</th>
                    <th>@lang('ratings.pool_fee')</th>
                    <th class="text-right">@lang('ratings.hashrate')</th>
                    <th class="text-right">@lang('ratings.blocks_last') 100</th>
                    <th class="text-right">@lang('ratings.last_found')</th>
                </tr>
            </thead>
            <tbody>
                @if ($coin->ratings->count())
                    @foreach ($coin->ratings as $rating)
                        <tr>
                            <td>{{ $loop->index + 1 }}</td>
                            <td data-order="{{ $rating->title }}">
                                <div class="d-flex align-items-center">
                                    <div class="b-pool-rating__icon">
                                        @if ($rating->image)
                                            <img src="{{ ImageService::optimize($rating->image, 48, 48) }}"
                                                alt="{{ $rating->title }}">
                                        @endif
                                    </div>
                                    <a target="_blank" rel="nofollow" href="{{ $rating->ref_link ?: $rating->link }}"
                                        class="b-pool-rating__title">{{ $rating->title }}</a>
                                </div>
                            </td>
                            <td>
                                @php
                                    /** @var object $rating */
                                    $pool_data = json_decode($rating->pivot->pool_data, true);
                                @endphp
                                @if ($pool_data && !empty($pool_data['feetype']))
                                    @php $feetype = array_reverse(explode('|', $pool_data['feetype'])); @endphp
                                    <ul class="list-unstyled b-pool-rating__fee">
                                        @foreach ($feetype as $key => $item)
                                            @php
                                                $item = explode('%', $item);
                                                if ($key == 0) {
                                                    $color = 'info';
                                                } elseif ($key == 1) {
                                                    $color = 'secondary';
                                                } else {
                                                    $color = 'primary';
                                                }
                                            @endphp
                                            <li>
                                                <strong class="text-{{ $color }}">{{ $item[0] }}</strong>
                                                @if (isset($item[1]) || isset($rating->pool_data['fee']))
                                                    <span>{{ $item[1] ?? $rating->pool_data['fee'] }} %</span>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                @else
                                    -
                                @endif
                            </td>
                            <td data-order="{{ $pool_data['hashrate'] ?? '0' }}" class="text-right">
                                @if (!empty($pool_data['hashrate']) && $pool_data['hashrate'] > 0)
                                    {{ RatingService::hashRateUnit($pool_data['hashrate']) }}
                                @else
                                    -
                                @endif
                            </td>
                            <td data-order="{{ $pool_data['luck'] ?? '-' }}" class="text-right">
                                {{ $pool_data['blocks_count'] ?? '-' }}

                                @if (!empty($pool_data['luck']))
                                    <br><strong
                                        class="b-pool-rating__changed bg-{{ RatingService::blocksColor($pool_data['luck']) }}">
                                        {{ round($pool_data['luck'], 2) }}
                                    </strong>
                                @endif
                            </td>
                            <td data-order="{{ $pool_data['lastblocktime'] ?? '-' }}" class="text-right">
                                {{ $pool_data['lastblock'] ?? '-' }}

                                @if (!empty($pool_data['lastblocktime']))
                                    <br><strong
                                        class="b-pool-rating__changed bg-{{ RatingService::timeIntervalColor($pool_data['lastblocktime']) }}">
                                        {{ RatingService::timeInterval($pool_data['lastblocktime']) }}
                                    </strong>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    @include('inc.promotion_x')

    @component('components.page_description')
        @slot('description', $coin->description)
    @endcomponent

@endsection

@section('script')
    @parent

    <script type="module">
        const $dataTable = $('.js-data-table');

        if ($dataTable.length) {
            $dataTable.DataTable({
                searching: false,
                paging: false,
                info: false,
                language: {
                    url: '/admin/libs/datatables/i18n/ru.json'
                },

                scrollX: true,
                scrollCollapse: true,
                fixedColumns: {
                    left: 2
                }
            });
        }
    </script>
@endsection
