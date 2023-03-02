<div class="b-subscribe js-full-width">
    <div class="container">
        <form id="subscribe-form" method="post" action="{{ route('subscribe.send') }}" class="b-subscribe__form">
            <img width="36" height="39" class="b-subscribe__img" src="{{ asset('default/img/favicon.svg') }}"
                alt="logo">
            <div class="b-subscribe__heading">@lang('subscribe.heading')</div>
            @csrf
            <div class="row justify-content-center">
                <div class="col-md">
                    <input type="text" name="email" placeholder="@lang('subscribe.email')" class="form-control">
                    {!! NoCaptcha::renderJs() !!}
                </div>
                <div class="col-auto">
                    <button class="btn btn-light flex-grow-1 g-recaptcha" data-callback="subscribeForm"
                        data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}">@lang('subscribe.btn')</button>
                </div>
            </div>
        </form>
    </div>
</div>
