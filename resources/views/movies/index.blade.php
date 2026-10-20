<x-layouts.app title="Movies">
    <x-page-header :title="__('Movies')" :description="__('Free movies play for everyone. Premium ones need a subscription, or a rental if a rent price is set.')">
        <x-button :href="route('movies.create')">{{ __('New movie') }}</x-button>
    </x-page-header>

    <x-search>
        <select name="access" class="rounded-lg border border-ink-300 bg-white px-3 py-2 text-sm shadow-sm">
            <option value="">{{ __('Free and premium') }}</option>
            <option value="free" @selected(request('access') === 'free')>{{ __('Free') }}</option>
            <option value="premium" @selected(request('access') === 'premium')>{{ __('Premium') }}</option>
        </select>
    </x-search>

    <x-table :headings="['Title', 'Genres', 'Language', 'Access', '>Viewers', 'Status']">
        @forelse ($movies as $movie)
            <tr>
                <td class="px-4 py-3">
                    <span class="block font-medium">{{ $movie->title }}</span>
                    <span class="block text-xs text-ink-500">{{ $movie->release_year }} · {{ $movie->duration_minutes }} {{ __('min') }}@if ($movie->maturity_rating) · {{ $movie->maturity_rating }}@endif @if ($movie->imdb_rating) · IMDb {{ number_format($movie->imdb_rating, 1) }}@endif</span>
                </td>
                <td class="px-4 py-3 text-ink-600">{{ $movie->genres->pluck('name')->implode(', ') }}</td>
                <td class="px-4 py-3 text-ink-600">{{ $movie->language->name }}</td>
                <td class="px-4 py-3">
                    <x-badge :tone="$movie->is_premium ? 'amber' : 'green'">{{ $movie->is_premium ? __('Premium') : __('Free') }}</x-badge>
                    @if ($movie->isRentable())
                        <span class="mt-1 block whitespace-nowrap text-xs text-ink-500">{{ __('rent :price / :days days', ['price' => rupiah($movie->rent_price), 'days' => $movie->rent_days]) }}</span>
                    @endif
                </td>
                <td class="px-4 py-3 text-right tabular-nums">{{ $movie->watches_count }}</td>
                <td class="px-4 py-3"><x-active-badge :active="$movie->is_active" /></td>
                <td class="px-4 py-3">
                    <x-row-actions :edit="route('movies.edit', $movie)" :destroy="route('movies.destroy', $movie)" :confirm="__('Delete :name?', ['name' => $movie->title])" />
                </td>
            </tr>
        @empty
            <x-empty-row span="7" />
        @endforelse
    </x-table>

    <div class="mt-4">{{ $movies->links() }}</div>
</x-layouts.app>
