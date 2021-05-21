<?php /** @var \Illuminate\Contracts\Pagination\Paginator $items */ ?>

@props(['items', 'appends' => null])

<?php
    if (isset($appends)) {
        $items->appends($appends);
    }
?>

<div class="paginator">
    <p>
        {!! __('Showing') !!}
        <span>{{ $items->firstItem() }}</span>
        {!! __('to') !!}
        <span>{{ $items->lastItem() }}</span>
        {!! __('of') !!}
        <span>{{ $items->total() }}</span>
        {!! __('results') !!}
    </p>

    {{ $items->links('vendor.pagination.default') }}
</div>
