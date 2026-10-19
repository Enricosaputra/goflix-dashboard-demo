@props(['name', 'label', 'options', 'value' => null, 'blank' => null])

{{-- $options is a value => label array --}}
<div {{ $attributes->only('class') }}>
    <label for="{{ $name }}" class="mb-1.5 block text-sm font-medium text-slate-700">{{ $label }}</label>
    <select id="{{ $name }}" name="{{ $name }}" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900/10">
        @if ($blank !== null)
            <option value="">{{ $blank }}</option>
        @endif
        @foreach ($options as $optionValue => $optionLabel)
            <option value="{{ $optionValue }}" @selected((string) old($name, $value) === (string) $optionValue)>{{ $optionLabel }}</option>
        @endforeach
    </select>
    @error($name) <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
</div>
