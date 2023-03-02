{{-- $page_description глобальна змінна з "App\View\Composers\PageComposer" --}}
@php $description = $description ?? $page_description ?? '' @endphp

@if (strip_tags($description) && !request()->has('page'))
    <div class="b-description b-page-description js-read-more-box">
        {!! $description !!}
    </div>
    @if (!Route::is('home'))
        <a href="#" class="dropdown-toggle b-read-more-btn js-read-more-btn" data-show-text="@lang('common.read_more_down')"
            data-hide-text="@lang('common.read_more_up')">@lang('common.read_more_down')</a>
    @endif

    @section('script')
        @parent

        <script type="module">
			const $readMoreBtn = $('.js-read-more-btn'),
				$readMoreBox = $('.js-read-more-box');

			if ($readMoreBtn.length) {
				if ($readMoreBox.height() > 300) {
					$readMoreBox.addClass('active');
				} else {
					$readMoreBtn.hide();
				}

				$readMoreBtn.click(function(e) {
					e.preventDefault();
					$readMoreBox.toggleClass('active');

					if ($readMoreBox.hasClass('active')) {
						$readMoreBtn.removeClass('active').text($readMoreBtn.data('show-text'));
					} else {
						$readMoreBtn.addClass('active').text($readMoreBtn.data('hide-text'));
					}
				});
			}
		</script>
    @endsection
@endif
