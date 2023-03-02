<div class="b-social-media">
    <div class="b-social-media__heading">{{ app()->getLocale() == 'ru' ? 'Поделиться:' : 'Share to:' }}</div>
    <ul class="list-inline">
        <li class="list-inline-item">
            <a href="https://www.facebook.com/sharer.php?u={{ urlencode(url()->current()) }}" target="_blank"
                rel="nofollow noopener">
                <img src="{{ asset('default/img/icons/icons8-facebook.svg') }}" alt="">
            </a>
        </li>
        <li class="list-inline-item">
            <a href="https://twitter.com/intent/tweet?url={{ urlencode(url()->current()) }}&text=&via=" target="_blank"
                rel="nofollow noopener">
                <img src="{{ asset('default/img/icons/icons8-twitter.svg') }}" alt="">
            </a>
        </li>
        @if (app()->getLocale() == 'ru')
            <li class="list-inline-item">
                <a href="https://vk.com/share.php?url={{ urlencode(url()->current()) }}" target="_blank"
                    rel="nofollow noopener">
                    <img src="{{ asset('default/img/icons/icons8-vk.svg') }}" alt="">
                </a>
            </li>
        @endif
        <li class="list-inline-item">
            <a href="https://www.linkedin.com/shareArticle?url={{ urlencode(url()->current()) }}&title=" target="_blank"
                rel="nofollow noopener">
                <img src="{{ asset('default/img/icons/icons8-linkedin.svg') }}" alt="">
            </a>
        </li>
    </ul>
</div>
