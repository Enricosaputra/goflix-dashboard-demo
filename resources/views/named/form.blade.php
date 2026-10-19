<x-layouts.app :title="$meta['title']">
    <x-page-header :title="__(($item->exists ? 'Edit ' : 'New ').$meta['singular'])" />

    <x-card>
        <form method="POST" action="{{ $item->exists ? route($meta['route'].'.update', $item) : route($meta['route'].'.store') }}" class="space-y-4">
            @csrf
            @if ($item->exists) @method('PUT') @endif

            <div class="grid gap-4 sm:grid-cols-2">
                <x-input name="name" :label="__('Name')" :value="$item->name" />
                @if ($meta['has_active'])
                    <x-checkbox name="is_active" :label="__('Active')" :checked="$item->is_active" />
                @endif
            </div>

            <x-form-actions :cancel="route($meta['route'].'.index')" />
        </form>
    </x-card>
</x-layouts.app>
