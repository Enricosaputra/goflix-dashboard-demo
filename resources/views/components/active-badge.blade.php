@props(['active'])

<x-badge :tone="$active ? 'green' : 'slate'">{{ $active ? __('active') : __('inactive') }}</x-badge>
