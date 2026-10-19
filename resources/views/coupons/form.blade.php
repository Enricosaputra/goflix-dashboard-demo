<x-layouts.app title="Coupons">
    <x-page-header :title="__($coupon->exists ? 'Edit coupon' : 'New coupon')" />

    <x-card>
        <form method="POST" action="{{ $coupon->exists ? route('coupons.update', $coupon) : route('coupons.store') }}" class="space-y-4">
            @csrf
            @if ($coupon->exists) @method('PUT') @endif

            <div class="grid gap-4 sm:grid-cols-2">
                <x-input name="code" :label="__('Code')" :value="$coupon->code" />
                <x-select name="amount_type" :label="__('Type')" :options="collect($types)->mapWithKeys(fn ($t) => [$t->value => $t->label()])" :value="$coupon->amount_type?->value" />
                <x-input name="amount" :label="__('Amount')" type="number" :value="$coupon->amount" />
                <x-input name="starts_on" :label="__('Valid from')" type="date" :value="$coupon->starts_on?->format('Y-m-d')" />
                <x-input name="ends_on" :label="__('Valid until')" type="date" :value="$coupon->ends_on?->format('Y-m-d')" />
                <div class="flex gap-6">
                    <x-checkbox name="single_use" :label="__('Once per user')" :checked="$coupon->single_use" />
                    <x-checkbox name="is_active" :label="__('Active')" :checked="$coupon->is_active" />
                </div>
            </div>

            <x-form-actions :cancel="route('coupons.index')" />
        </form>
    </x-card>
</x-layouts.app>
