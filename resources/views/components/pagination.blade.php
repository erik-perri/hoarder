<?php /** @var \Illuminate\Contracts\Pagination\Paginator $items */ ?>

@props(['items'])

<div>
    {{ $items->links() }}
</div>
