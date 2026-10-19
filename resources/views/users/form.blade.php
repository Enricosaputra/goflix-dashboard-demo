<x-layouts.app title="Users">
    <x-page-header :title="__($user->exists ? 'Edit user' : 'New user')" :description="__('The subscription end date is set by payments, not here.')" />

    <x-card>
        <form method="POST" action="{{ $user->exists ? route('users.update', $user) : route('users.store') }}" class="space-y-4">
            @csrf
            @if ($user->exists) @method('PUT') @endif

            <div class="grid gap-4 sm:grid-cols-2">
                <x-input name="name" :label="__('Name')" :value="$user->name" />
                <x-input name="email" :label="__('Email')" :value="$user->email" />
                <x-select name="login_type" :label="__('Sign-in')" :options="collect($types)->mapWithKeys(fn ($t) => [$t->value => $t->label()])" :value="$user->login_type?->value" />
                <x-checkbox name="is_active" :label="__('Account active')" :checked="$user->is_active" />
            </div>

            <x-form-actions :cancel="$user->exists ? route('users.show', $user) : route('users.index')" />
        </form>
    </x-card>
</x-layouts.app>
