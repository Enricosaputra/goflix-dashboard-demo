<x-layouts.app title="Coupons">
    <x-page-header :title="__('Coupons')" :description="__('Discounts for plans and rentals. A single-use coupon works once per user.')">
        <x-button :href="route('coupons.create')">{{ __('New coupon') }}</x-button>
    </x-page-header>

    <x-search />

    <x-table :headings="['Code', '>Discount', 'Valid', '>Used', 'Status']">
        @forelse ($coupons as $coupon)
            <tr>
                <td class="px-4 py-3">
                    <span class="block font-mono font-medium">{{ $coupon->code }}</span>
                    @if ($coupon->single_use) <span class="block text-xs text-ink-500">{{ __('once per user') }}</span> @endif
                </td>
                <td class="whitespace-nowrap px-4 py-3 text-right tabular-nums">{{ $coupon->label() }}</td>
                <td class="whitespace-nowrap px-4 py-3 text-ink-600">{{ $coupon->starts_on->format('Y-m-d') }} - {{ $coupon->ends_on->format('Y-m-d') }}</td>
                <td class="px-4 py-3 text-right tabular-nums">{{ $coupon->subscriptions_count + $coupon->rentals_count }}</td>
                <td class="px-4 py-3">
                    @if ($coupon->is_active && $coupon->ends_on->lt(today()))
                        <x-badge>{{ __('expired') }}</x-badge>
                    @else
                        <x-active-badge :active="$coupon->is_active" />
                    @endif
                </td>
                <td class="px-4 py-3">
                    <x-row-actions :edit="route('coupons.edit', $coupon)" :destroy="route('coupons.destroy', $coupon)" :confirm="__('Delete :name?', ['name' => $coupon->code])" />
                </td>
            </tr>
        @empty
            <x-empty-row span="6" />
        @endforelse
    </x-table>

    <div class="mt-4">{{ $coupons->links() }}</div>
</x-layouts.app>
