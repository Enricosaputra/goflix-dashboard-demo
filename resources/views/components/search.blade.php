<form method="GET" class="mb-4 flex flex-wrap gap-3">
    <input name="q" value="{{ request('q') }}" placeholder="{{ __('Search') }}" class="w-full max-w-xs rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm">
    {{ $slot }}
    <x-button variant="outline">{{ __('Search') }}</x-button>
</form>
