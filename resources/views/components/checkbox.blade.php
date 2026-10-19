@props(['name', 'label', 'checked' => false])

<label class="flex items-center gap-2 pt-7 text-sm font-medium text-slate-700">
    <input type="checkbox" name="{{ $name }}" value="1" @checked(old($name, $checked)) class="h-4 w-4 rounded border-slate-300">
    {{ $label }}
</label>
