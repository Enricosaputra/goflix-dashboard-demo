@props(['cancel'])

<div class="flex gap-2">
    <x-button>{{ __('Save') }}</x-button>
    <x-button :href="$cancel" variant="outline">{{ __('Cancel') }}</x-button>
</div>
