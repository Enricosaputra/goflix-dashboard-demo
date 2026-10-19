<x-layouts.app title="Plans">
    <x-page-header :title="__('Plans')" :description="__('Subscription plans the app sells. Buying again while subscribed adds the time on top.')">
        <x-button :href="route('packages.create')">{{ __('New plan') }}</x-button>
    </x-page-header>

    <x-table :headings="['Plan', '>Price', 'Includes', '>Sold', '>Revenue', 'Status']">
        @forelse ($packages as $package)
            <tr>
                <td class="px-4 py-3">
                    <span class="block font-medium">{{ $package->name }}</span>
                    <span class="block text-xs text-slate-500">{{ trans_choice(':count month|:count months', $package->duration_months) }}</span>
                </td>
                <td class="whitespace-nowrap px-4 py-3 text-right tabular-nums">{{ rupiah($package->price) }}</td>
                <td class="px-4 py-3 text-xs text-slate-600">
                    {{ $package->max_quality }} · {{ trans_choice(':count device|:count devices', $package->max_devices) }}
                    @if ($package->watch_on_tv) · {{ __('TV') }} @endif
                    @if ($package->ads_free) · {{ __('no ads') }} @endif
                    @if ($package->features->isNotEmpty())
                        <span class="block text-slate-500">{{ $package->features->map(fn ($f) => $f->value !== '' ? "{$f->name}: {$f->value}" : $f->name)->implode(' · ') }}</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-right tabular-nums">{{ $package->subscriptions_count }}</td>
                <td class="whitespace-nowrap px-4 py-3 text-right tabular-nums">{{ rupiah($package->revenue ?? 0) }}</td>
                <td class="px-4 py-3"><x-active-badge :active="$package->is_active" /></td>
                <td class="px-4 py-3">
                    <x-row-actions :edit="route('packages.edit', $package)" :destroy="route('packages.destroy', $package)" :confirm="__('Delete :name?', ['name' => $package->name])" />
                </td>
            </tr>
        @empty
            <x-empty-row span="7" />
        @endforelse
    </x-table>

    <div class="mt-4">{{ $packages->links() }}</div>
</x-layouts.app>
