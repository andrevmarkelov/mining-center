<div class="b-subscribe-v2">
    <form id="subscribe-form" method="post" action="{{ route('subscribe.send') }}">
        <div class="b-subscribe-v2__heading">@lang('subscribe.heading_add1')</div>
        @include('inc.social-network', ['class' => 'b-subscribe-v2__social-network'])

        <hr style="margin: 23px 0;">

        <div class="b-subscribe-v2__heading">@lang('subscribe.heading_add2')</div>
        @csrf
        <input type="text" name="email" placeholder="@lang('subscribe.email')" class="form-control">
        <button class="btn btn-primary g-recaptcha" data-callback="subscribeForm"
            data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}">@lang('subscribe.btn')</button>
        {!! NoCaptcha::renderJs() !!}
    </form>
</div>
