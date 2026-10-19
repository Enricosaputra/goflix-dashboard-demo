<x-layouts.app :title="$meta['title']">
    <x-page-header :title="__($meta['title'])" :description="__($meta['description'])">
        <x-button :href="route($meta['route'].'.create')">{{ __('New '.$meta['singular']) }}</x-button>
    </x-page-header>

    <x-search />

    <x-table :headings="array_merge(['Name', '>'.$meta['count_heading']], $meta['has_active'] ? ['Status'] : [])">
        @forelse ($items as $item)
            <tr>
                <td class="px-4 py-3 font-medium">{{ $item->name }}</td>
                <td class="px-4 py-3 text-right tabular-nums">{{ $item->{$meta['count_attribute']} }}</td>
                @if ($meta['has_active'])
                    <td class="px-4 py-3"><x-active-badge :active="$item->is_active" /></td>
                @endif
                <td class="px-4 py-3">
                    <x-row-actions :edit="route($meta['route'].'.edit', $item)" :destroy="route($meta['route'].'.destroy', $item)" :confirm="__('Delete :name?', ['name' => $item->name])" />
                </td>
            </tr>
        @empty
            <x-empty-row :span="$meta['has_active'] ? 4 : 3" />
        @endforelse
    </x-table>

    <div class="mt-4">{{ $items->links() }}</div>
</x-layouts.app>
