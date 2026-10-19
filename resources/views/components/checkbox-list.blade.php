@props(['name', 'label', 'options', 'selected' => []])

{{-- $options is a value => label array; posts $name[] --}}
@php $selected = collect(old($name, $selected))->map(fn ($v) => (string) $v)->all(); @endphp
<fieldset {{ $attributes->only('class') }}>
    <legend class="mb-1.5 text-sm font-medium text-slate-700">{{ $label }}</legend>
    <div class="flex flex-wrap gap-x-4 gap-y-2 rounded-lg border border-slate-300 bg-white p-3">
        @foreach ($options as $value => $text)
            <label class="flex items-center gap-1.5 text-sm">
                <input type="checkbox" name="{{ $name }}[]" value="{{ $value }}" @checked(in_array((string) $value, $selected, true)) class="h-4 w-4 rounded border-slate-300">
                {{ $text }}
            </label>
        @endforeach
    </div>
    @error($name) <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
</fieldset>
