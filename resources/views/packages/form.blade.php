<x-layouts.app title="Plans">
    <x-page-header :title="__($package->exists ? 'Edit plan' : 'New plan')" />

    @php
        $rows = old('features', $package->features->map(fn ($f) => $f->only(['name', 'value']))->all());
        $rows = array_merge($rows, [['name' => '', 'value' => ''], ['name' => '', 'value' => '']]);
    @endphp

    <form method="POST" action="{{ $package->exists ? route('packages.update', $package) : route('packages.store') }}" class="space-y-6">
        @csrf
        @if ($package->exists) @method('PUT') @endif

        <x-card :title="__('Plan')">
            <div class="grid gap-4 sm:grid-cols-2">
                <x-input name="name" :label="__('Name')" :value="$package->name" />
                <x-input name="price" :label="__('Price (Rp)')" type="number" :value="$package->price" />
                <x-input name="duration_months" :label="__('Length (months)')" type="number" :value="$package->duration_months" />
                <x-input name="max_devices" :label="__('Devices at once')" type="number" :value="$package->max_devices" />
                <x-select name="max_quality" :label="__('Max quality')" :options="['480p' => '480p', '720p' => '720p', '1080p' => '1080p']" :value="$package->max_quality" />
                <div class="flex flex-wrap gap-6">
                    <x-checkbox name="ads_free" :label="__('No ads')" :checked="$package->ads_free" />
                    <x-checkbox name="watch_on_tv" :label="__('Watch on TV')" :checked="$package->watch_on_tv" />
                    <x-checkbox name="is_active" :label="__('Active')" :checked="$package->is_active" />
                </div>
            </div>
        </x-card>

        <x-card :title="__('Plan card lines')" :description="__('Extra lines shown on the plan card in the app, like production\'s package_detail. Leave the name empty to remove a line.')">
            <div class="space-y-2">
                @foreach ($rows as $i => $row)
                    <div class="grid gap-2 sm:grid-cols-2">
                        <input name="features[{{ $i }}][name]" value="{{ $row['name'] }}" placeholder="{{ __('e.g. All content') }}" class="rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm">
                        <input name="features[{{ $i }}][value]" value="{{ $row['value'] }}" placeholder="{{ __('e.g. Yes') }}" class="rounded-lg border border-slate-300 px-3 py-2 text-sm shadow-sm">
                    </div>
                @endforeach
            </div>
        </x-card>

        <x-form-actions :cancel="route('packages.index')" />
    </form>
</x-layouts.app>
