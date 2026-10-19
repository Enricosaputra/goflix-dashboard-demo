<x-layouts.app title="Rentals">
    <x-page-header :title="__('Add rental')" :description="__('Only premium movies with a rent price. Users with an active subscription do not need one.')" />

    <x-card>
        <form method="POST" action="{{ route('rentals.store') }}" class="space-y-4">
            @csrf
            <div class="grid gap-4 sm:grid-cols-2">
                <x-select name="user_id" :label="__('User')" :options="$users->mapWithKeys(fn ($u) => [$u->id => $u->name.' ('.$u->email.')'])" :value="request('user')" />
                <x-select name="movie_id" :label="__('Movie')" :options="$movies->mapWithKeys(fn ($m) => [$m->id => $m->title.' - '.rupiah($m->rent_price).' / '.trans_choice(':count day|:count days', $m->rent_days)])" />
                <x-select name="coupon_id" :label="__('Coupon')" :options="$coupons->mapWithKeys(fn ($c) => [$c->id => $c->code.' ('.$c->label().')'])" :blank="__('No coupon')" />
            </div>
            <x-form-actions :cancel="request('user') ? route('users.show', request('user')) : route('rentals.index')" />
        </form>
    </x-card>
</x-layouts.app>
