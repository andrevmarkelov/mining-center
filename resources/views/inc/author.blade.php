<ul class="list-inline b-author-block">
    @if ($object->user)
        <li class="list-inline-item">{{ $object->user->name }}</li>
    @endif
    @php
        if (app()->getLocale() == 'en') {
            $date = date_format($object->created_at, 'H:i M d, Y');
        } else {
            $date = $object->created_at->isoFormat('D MMMM, Y') . $object->created_at->format(' H:i');
        }
    @endphp
    <li class="list-inline-item">{{ $date }}</li>
    <li class="list-inline-item b-author-block__views">
        <img src="{{ asset('default/img/icons/eye.svg') }}" alt="">
        <span>{{ thousands_currency_format($object->view) }}</span>
    </li>
</ul>
