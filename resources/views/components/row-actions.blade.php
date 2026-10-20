@props(['edit', 'destroy', 'confirm'])

<div class="flex items-center justify-end gap-3 whitespace-nowrap text-sm">
    <a href="{{ $edit }}" class="text-ink-500 hover:text-ink-900">{{ __('Edit') }}</a>
    <form method="POST" action="{{ $destroy }}" onsubmit="return confirm(@js($confirm))">
        @csrf
        @method('DELETE')
        <button class="text-ink-500 hover:text-red-600">{{ __('Delete') }}</button>
    </form>
</div>
