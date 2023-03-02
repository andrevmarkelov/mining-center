<div class="b-equipment-quick-view">
    <div class="b-equipment-quick-view__title">
        <a href="{{ route('equipments.show', $equipment->alias) }}">{{ $equipment->title }}</a>
    </div>
    <div class="d-flex align-items-start justify-content-center">
        <a href="{{ route('equipments.show', $equipment->alias) }}" class="b-equipment-quick-view__img-wrap">
            <img src="{{ $equipment->thumb }}" alt="{{ $equipment->title }}">
        </a>
        @if (strip_tags($equipment->add_description))
            <div class="b-equipment-quick-view__right-col">
                <div class="b-equipment-quick-view__attr">
                    {!! $equipment->add_description !!}
                </div>
            </div>
        @endif
    </div>
    @if (strip_tags($equipment->description))
        <div class="b-equipment-quick-view__text b-description">
            {!! $equipment->description !!}
        </div>
    @endif
    <div class="text-center mt-4 pt-2">
        <button class="btn btn-primary btn-lg" data-id="{{ $equipment->id }}" data-toggle="modal"
            data-target="#modal-equipment-order" data-dismiss="modal">@lang('common.find_cost')</button>
    </div>
</div>
