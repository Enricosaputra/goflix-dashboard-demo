<x-layouts.app title="Cast and crew">
    <x-page-header :title="__($person->exists ? 'Edit person' : 'New person')" />

    <x-card>
        <form method="POST" action="{{ $person->exists ? route('people.update', $person) : route('people.store') }}" class="space-y-4">
            @csrf
            @if ($person->exists) @method('PUT') @endif

            <div class="grid gap-4 sm:grid-cols-2">
                <x-input name="name" :label="__('Name')" :value="$person->name" />
                <x-select name="role" :label="__('Role')" :options="collect($roles)->mapWithKeys(fn ($r) => [$r->value => $r->label()])" :value="$person->role?->value" />
                <x-checkbox name="is_active" :label="__('Active')" :checked="$person->is_active" />
            </div>

            <x-form-actions :cancel="route('people.index')" />
        </form>
    </x-card>
</x-layouts.app>
