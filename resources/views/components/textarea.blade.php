@props(['name', 'label', 'value' => null, 'rows' => 4])

<div {{ $attributes->only('class') }}>
    <label for="{{ $name }}" class="mb-1.5 block text-sm font-medium text-ink-700">{{ $label }}</label>
    <textarea id="{{ $name }}" name="{{ $name }}" rows="{{ $rows }}" class="w-full rounded-lg border border-ink-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-ink-900 focus:outline-none focus:ring-2 focus:ring-ink-900/10">{{ old($name, $value) }}</textarea>
    @error($name) <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
</div>
