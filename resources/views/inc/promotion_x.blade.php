@if (isset($pc_1) && $pc_1 && $pc_1->image)
    <div class="d-none d-md-block b-promotion-x">
        <a href="{{ $pc_1->link }}" @if ($pc_1->nofollow) rel="nofollow" @endif>
            <img class="img-fluid" src="{{ ImageService::optimize($pc_1->image) }}" alt="">
        </a>
    </div>
@endif

@if (isset($mobile_1) && $mobile_1 && $mobile_1->image)
    <div class="d-md-none b-promotion-x">
        <a href="{{ $mobile_1->link }}" @if ($mobile_1->nofollow) rel="nofollow" @endif>
            <img class="img-fluid" src="{{ ImageService::optimize($mobile_1->image) }}" alt="">
        </a>
    </div>
@endif
