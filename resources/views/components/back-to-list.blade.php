@props(['url'])
<a href="{{ $url }}" class="fs-6 text-capitalize text-sm hidden-label-parent" title="Back
Home">
    <i class="fas fa-list"></i>
    <span class="hidden-label">{{ __('support::messages.back_home') }}</span>
</a>