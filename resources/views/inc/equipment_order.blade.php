<div class="modal fade b-theme-modal" id="modal-equipment-order">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="b-theme-modal__close" data-dismiss="modal">
                <img src="{{ asset('default/img/icons/close.svg') }}" alt="">
            </div>
            <div class="modal-body">
                <form id="equipment-order-form" action="{{ route('equipments.send') }}" method="post" class="b-theme-form">
                    @csrf
                    <div class="b-heading text-center mt-0">@lang('equipments.find_cost')</div>
                    <div class="form-group">
                        <input placeholder="@lang('common.name')*" type="text" name="name" class="form-control" id="input-name">
                    </div>
                    <div class="form-group">
                        <input placeholder="@lang('common.email')*" type="email" name="email" class="form-control" id="input-email">
                    </div>
                    <div class="form-group">
                        <input placeholder="Telegram / Whatsapp" type="text" name="telegram" class="form-control" id="input-telegram">
                    </div>
                    <div class="form-group">
                        <textarea placeholder="@lang('common.comment')" name="comment" class="form-control" id="input-comment"></textarea>
                    </div>
                    <input type="hidden" name="miner_id">
                    <input type="hidden" name="form_type">
                    <div class="text-center">
                        {!! NoCaptcha::renderJs() !!}
                        <button class="btn btn-primary btn-lg b-theme-form__btn g-recaptcha"
                            data-callback="equipmentOrderForm"
                            data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"
                        >@lang('common.find_cost')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('script')
    @parent

    <script type="module">
        $(document).on('click', '[data-target="#modal-equipment-order"]', function() {
            $('[name="miner_id"]').val($(this).attr('data-id'));
            $('[name="form_type"]').val($(this).attr('data-form-type'));
        });
    </script>
@endsection
