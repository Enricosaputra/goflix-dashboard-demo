<x-layouts.app title="Cast and crew">
    <x-page-header :title="__('Cast and crew')" :description="__('Actors, actresses and directors credited in movies.')">
        <x-button :href="route('people.create')">{{ __('New person') }}</x-button>
    </x-page-header>

    <x-search>
        <select name="role" class="rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm shadow-sm">
            <option value="">{{ __('All roles') }}</option>
            @foreach ($roles as $role)
                <option value="{{ $role->value }}" @selected(request('role') === $role->value)>{{ $role->label() }}</option>
            @endforeach
        </select>
    </x-search>

    <x-table :headings="['Name', 'Role', '>Movies', 'Status']">
        @forelse ($people as $person)
            <tr>
                <td class="px-4 py-3 font-medium">{{ $person->name }}</td>
                <td class="px-4 py-3 text-slate-600">{{ $person->role->label() }}</td>
                <td class="px-4 py-3 text-right tabular-nums">{{ $person->movies_count }}</td>
                <td class="px-4 py-3"><x-active-badge :active="$person->is_active" /></td>
                <td class="px-4 py-3">
                    <x-row-actions :edit="route('people.edit', $person)" :destroy="route('people.destroy', $person)" :confirm="__('Delete :name?', ['name' => $person->name])" />
                </td>
            </tr>
        @empty
            <x-empty-row span="5" />
        @endforelse
    </x-table>

    <div class="mt-4">{{ $people->links() }}</div>
</x-layouts.app>
