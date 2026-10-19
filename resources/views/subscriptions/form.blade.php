<x-layouts.app title="Subscriptions">
    <x-page-header :title="__('Add subscription')" :description="__('For payments made outside the app, e.g. a bank transfer. Buying while subscribed adds the time after the current end date.')" />

    <x-card>
        <form method="POST" action="{{ route('subscriptions.store') }}" class="space-y-4">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <x-select name="user_id" :label="__('User')" :options="$users->mapWithKeys(fn ($u) => [$u->id => $u->name.' ('.$u->email.')'])" :value="request('user')" />
                <x-select name="package_id" :label="__('Plan')" :options="$packages->mapWithKeys(fn ($p) => [$p->id => $p->name.' - '.rupiah($p->price)])" />
                <x-select name="coupon_id" :label="__('Coupon')" :options="$coupons->mapWithKeys(fn ($c) => [$c->id => $c->code.' ('.$c->label().')'])" :blank="__('No coupon')" />
                <x-input name="payment_reference" :label="__('Payment reference')" :placeholder="__('e.g. transfer number')" />
            </div>
            <x-form-actions :cancel="request('user') ? route('users.show', request('user')) : route('subscriptions.index')" />
        </form>
    </x-card>
</x-layouts.app>
