<x-layouts.app title="Movies">
    <x-page-header :title="__($movie->exists ? 'Edit movie' : 'New movie')" />

    <form method="POST" action="{{ $movie->exists ? route('movies.update', $movie) : route('movies.store') }}" class="space-y-6">
        @csrf
        @if ($movie->exists) @method('PUT') @endif

        <x-card :title="__('Details')">
            <div class="grid gap-4 sm:grid-cols-2">
                <x-input name="title" :label="__('Title')" :value="$movie->title" />
                <x-select name="language_id" :label="__('Language')" :options="$languages->pluck('name', 'id')" :value="$movie->language_id" />
                <x-input name="release_year" :label="__('Release year')" type="number" :value="$movie->release_year" />
                <x-input name="duration_minutes" :label="__('Duration (minutes)')" type="number" :value="$movie->duration_minutes" />
                <x-select name="maturity_rating" :label="__('Age rating')" :options="['SU' => 'SU', '13+' => '13+', '17+' => '17+', '21+' => '21+']" :value="$movie->maturity_rating" :blank="__('Not set')" />
                <x-input name="imdb_rating" :label="__('IMDb rating')" type="number" step="0.1" :value="$movie->imdb_rating" />
                <x-checkbox-list name="genres" :label="__('Genres')" :options="$genres->pluck('name', 'id')" :selected="$movie->genres->pluck('id')" class="sm:col-span-2" />
                <x-textarea name="description" :label="__('Description')" :value="$movie->description" class="sm:col-span-2" />
                <div class="sm:col-span-2">
                    <label for="people" class="mb-1.5 block text-sm font-medium text-ink-700">{{ __('Cast and crew') }}</label>
                    <select id="people" name="people[]" multiple size="8" class="w-full rounded-lg border border-ink-300 bg-white px-3 py-2 text-sm shadow-sm">
                        @php $credited = collect(old('people', $movie->people->pluck('id')))->map(fn ($v) => (string) $v)->all(); @endphp
                        @foreach ($people as $person)
                            <option value="{{ $person->id }}" @selected(in_array((string) $person->id, $credited, true))>{{ $person->name }} ({{ $person->role->label() }})</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-ink-500">{{ __('Hold Ctrl (Cmd on Mac) to pick several.') }}</p>
                </div>
            </div>
        </x-card>

        <x-card :title="__('Access and playback')" :description="__('A rent price only applies to premium movies.')">
            <div class="grid gap-4 sm:grid-cols-2">
                <x-checkbox name="is_premium" :label="__('Premium (needs a subscription)')" :checked="$movie->is_premium" />
                <x-checkbox name="is_downloadable" :label="__('Can be downloaded')" :checked="$movie->is_downloadable" />
                <x-input name="rent_price" :label="__('Rent price (Rp), empty = not for rent')" type="number" :value="$movie->rent_price" />
                <x-input name="rent_days" :label="__('Rental period (days)')" type="number" :value="$movie->rent_days" />
                <x-select name="max_quality" :label="__('Max quality')" :options="['480p' => '480p', '720p' => '720p', '1080p' => '1080p']" :value="$movie->max_quality" />
                <x-checkbox name="is_active" :label="__('Active')" :checked="$movie->is_active" />
            </div>
        </x-card>

        <x-form-actions :cancel="route('movies.index')" />
    </form>
</x-layouts.app>
