<ul class="list-inline {{ $class ?? 'b-footer__social-network' }}">
    @if ($twitter = setting('twitter'))
        <li class="list-inline-item">
            <a target="_blank" rel="nofollow" href="{{ $twitter }}">
                <img src="{{ asset('default/img/icons/twitter.svg') }}" alt="twitter">
            </a>
        </li>
    @endif
    @if ($discord = setting('discord'))
        <li class="list-inline-item">
            <a target="_blank" rel="nofollow" href="{{ $discord }}">
                <img src="{{ asset('default/img/icons/discord.svg') }}" alt="discord">
            </a>
        </li>
    @endif
    @if ($telegram = setting('telegram'))
        <li class="list-inline-item">
            <a target="_blank" rel="nofollow" href="https://t.me/{{ $telegram }}">
                <img src="{{ asset('default/img/icons/telegram.svg') }}" alt="telegram">
            </a>
        </li>
    @endif
    @if ($youtube = setting('youtube'))
        <li class="list-inline-item">
            <a target="_blank" rel="nofollow" href="{{ $youtube }}">
                <img src="{{ asset('default/img/icons/youtube.svg') }}" alt="youtube">
            </a>
        </li>
    @endif
    @if ($facebook = setting('facebook'))
        <li class="list-inline-item">
            <a target="_blank" rel="nofollow" href="{{ $facebook }}">
                <img src="{{ asset('default/img/icons/facebook.svg') }}" alt="facebook">
            </a>
        </li>
    @endif
    @if ($instagram = setting('instagram'))
        <li class="list-inline-item">
            <a target="_blank" rel="nofollow" href="{{ $instagram }}">
                <img src="{{ asset('default/img/icons/instagram.svg') }}" alt="instagram">
            </a>
        </li>
    @endif
    <li class="list-inline-item">
        <a href="{{ route('rss_feed') }}">
            <img width="12" height="12" src="{{ asset('default/img/icons/rss-feed.svg') }}" alt="rss">
        </a>
    </li>
</ul>
