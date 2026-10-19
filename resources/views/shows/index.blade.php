<x-layouts.app title="Series">
    <x-page-header :title="__('Series')" :description="__('Series with their episodes, grouped by season. An episode is premium on its own or when the whole series is.')">
        <x-button :href="route('shows.create')">{{ __('New series') }}</x-button>
    </x-page-header>

    <x-search />

    <x-table :headings="['Title', 'Genres', 'Episodes', 'Access', 'Status']">
        @forelse ($shows as $show)
            <tr>
                <td class="px-4 py-3">
                    <span class="block font-medium">{{ $show->title }}</span>
                    <span class="block text-xs text-slate-500">{{ $show->release_year }} · {{ $show->language->name }}@if ($show->maturity_rating) · {{ $show->maturity_rating }}@endif</span>
                </td>
                <td class="px-4 py-3 text-slate-600">{{ $show->genres->pluck('name')->implode(', ') }}</td>
                <td class="px-4 py-3 text-slate-600">
                    {{ trans_choice(':count episode|:count episodes', $show->episodes->count()) }}
                    <span class="block text-xs text-slate-500">{{ trans_choice(':count season|:count seasons', $show->episodes->unique('season_id')->count()) }}</span>
                </td>
                <td class="px-4 py-3"><x-badge :tone="$show->is_premium ? 'amber' : 'green'">{{ $show->is_premium ? __('Premium') : __('Free') }}</x-badge></td>
                <td class="px-4 py-3"><x-active-badge :active="$show->is_active" /></td>
                <td class="px-4 py-3">
                    <x-row-actions :edit="route('shows.edit', $show)" :destroy="route('shows.destroy', $show)" :confirm="__('Delete :name?', ['name' => $show->title])" />
                </td>
            </tr>
        @empty
            <x-empty-row span="6" />
        @endforelse
    </x-table>

    <div class="mt-4">{{ $shows->links() }}</div>
</x-layouts.app>
