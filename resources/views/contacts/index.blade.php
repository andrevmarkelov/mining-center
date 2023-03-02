@extends('layouts.app')

@section('content')
    @component('components.breadcrumb')
        @slot('title_active', __('common.contacts'))
    @endcomponent

    <div class="row justify-content-between">
        <div class="col-md-6 col-lg-5">
            <form id="contact-form" action="{{ route('contacts.send') }}" method="post" class="b-theme-form">
                @csrf
                <div class="form-group">
                    <input placeholder="@lang('common.name')*" type="text" name="name" class="form-control" id="input-name">
                </div>
                <div class="form-group">
                    <input placeholder="@lang('common.email')*" type="email" name="email" class="form-control"
                        id="input-email">
                </div>
                <div class="form-group">
                    <input placeholder="Telegram / Whatsapp" type="text" name="telegram" class="form-control"
                        id="input-telegram">
                </div>
                <div class="form-group">
                    <textarea placeholder="@lang('common.comment')" name="comment" class="form-control" id="input-comment"></textarea>
                </div>
                @if ($telegram = setting('telegram'))
                    <p class="b-theme-form__text">
                        @lang('contacts.follow_telegram')<br>
                        <a href="https://t.me/{{ $telegram }}">t.me/{{ $telegram }}</a>
                    </p>
                @endif
                <div class="text-center">
                    {!! NoCaptcha::renderJs() !!}
                    <button class="btn btn-primary btn-lg b-theme-form__btn g-recaptcha"
                        data-callback="contactForm"
                        data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"
                    >@lang('common.send')</button>
                </div>
            </form>
        </div>
        <div class="col-md-6">
            <div class="b-contacts-info">
                <a href="{{ route('home') }}">
                    <img src="{{ asset('default/img/logo.svg') }}" alt="{{ config('app.name') }}">
                </a>
                <dl>
                    @if ($email = setting('email'))
                        <dt>@lang('common.email')</dt>
                        <dd>
                            <a href="mailto:{{ $email }}">{{ $email }}</a>
                        </dd>
                    @endif
                    @php
                        $phone = setting('phone');
                    @endphp
                    @if (!empty($phone[0]) || !empty($phone[1]))
                        <dt>@lang('common.phone')</dt>
                        <dd>
                            @if (!empty($phone[0]))
                                <a href="tel:{{ preg_replace('/\s+/', '', $phone[0]) }}">{{ $phone[0] }}</a>
                            @endif
                            @if (!empty($phone[1]))
                                <br><a href="tel:{{ preg_replace('/\s+/', '', $phone[1]) }}">{{ $phone[1] }}</a>
                            @endif
                        </dd>
                    @endif
                    @if ($address = setting(app()->getLocale() . '.address'))
                        <dt>@lang('common.address')</dt>
                        <dd>{{ $address }}</dd>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    @include('inc.promotion_x')

    @component('components.page_description')
    @endcomponent
@endsection
