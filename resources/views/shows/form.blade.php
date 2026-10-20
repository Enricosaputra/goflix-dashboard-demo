<x-layouts.app title="Series">
    <x-page-header :title="__($show->exists ? 'Edit series' : 'New series')" />

    @php
        // Existing episodes plus three empty rows. After a failed save, show what was typed.
        $rows = old('episodes', $show->episodes->map(fn ($e) => $e->only(['season_id', 'episode_number', 'title', 'duration_minutes', 'is_premium']))->all());
        $last = collect($rows)->last();
        for ($i = 1; $i <= 3; $i++) {
            $rows[] = ['season_id' => $last['season_id'] ?? $seasons->first()?->id, 'episode_number' => ($last['episode_number'] ?? 0) + $i, 'title' => '', 'duration_minutes' => '', 'is_premium' => false];
        }
    @endphp

    <form method="POST" action="{{ $show->exists ? route('shows.update', $show) : route('shows.store') }}" class="space-y-6">
        @csrf
        @if ($show->exists) @method('PUT') @endif

        <x-card :title="__('Details')">
            <div class="grid gap-4 sm:grid-cols-2">
                <x-input name="title" :label="__('Title')" :value="$show->title" />
                <x-select name="language_id" :label="__('Language')" :options="$languages->pluck('name', 'id')" :value="$show->language_id" />
                <x-input name="release_year" :label="__('Release year')" type="number" :value="$show->release_year" />
                <x-select name="maturity_rating" :label="__('Age rating')" :options="['SU' => 'SU', '13+' => '13+', '17+' => '17+', '21+' => '21+']" :value="$show->maturity_rating" :blank="__('Not set')" />
                <x-input name="imdb_rating" :label="__('IMDb rating')" type="number" step="0.1" :value="$show->imdb_rating" />
                <div class="flex gap-6">
                    <x-checkbox name="is_premium" :label="__('Whole series is premium')" :checked="$show->is_premium" />
                    <x-checkbox name="is_active" :label="__('Active')" :checked="$show->is_active" />
                </div>
                <x-checkbox-list name="genres" :label="__('Genres')" :options="$genres->pluck('name', 'id')" :selected="$show->genres->pluck('id')" class="sm:col-span-2" />
                <x-textarea name="description" :label="__('Description')" :value="$show->description" class="sm:col-span-2" />
            </div>
        </x-card>

        <x-card :title="__('Episodes')" :description="__('Leave the title empty to skip a row or remove an episode.')">
            @error('episodes') <p class="mb-3 rounded-lg bg-red-50 p-3 text-sm text-red-800">{{ $message }}</p> @enderror
            <div class="-mx-6 overflow-x-auto px-6">
                <table class="w-full min-w-[640px] text-left text-sm">
                    <thead class="text-xs uppercase tracking-wide text-ink-400">
                        <tr>
                            <th class="pb-2 pr-3 font-medium">{{ __('Season') }}</th>
                            <th class="pb-2 pr-3 font-medium">{{ __('No.') }}</th>
                            <th class="pb-2 pr-3 font-medium">{{ __('Title') }}</th>
                            <th class="pb-2 pr-3 font-medium">{{ __('Minutes') }}</th>
                            <th class="pb-2 font-medium">{{ __('Premium') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($rows as $i => $row)
                            <tr>
                                <td class="py-1 pr-3">
                                    <select name="episodes[{{ $i }}][season_id]" class="rounded-lg border border-ink-300 px-2 py-2 text-sm shadow-sm">
                                        @foreach ($seasons as $season)
                                            <option value="{{ $season->id }}" @selected((string) ($row['season_id'] ?? '') === (string) $season->id)>{{ $season->name }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td class="py-1 pr-3"><input name="episodes[{{ $i }}][episode_number]" type="number" min="1" value="{{ $row['episode_number'] ?? '' }}" class="w-20 rounded-lg border border-ink-300 px-3 py-2 text-sm shadow-sm"></td>
                                <td class="py-1 pr-3"><input name="episodes[{{ $i }}][title]" value="{{ $row['title'] ?? '' }}" placeholder="{{ __('Episode title') }}" class="w-full min-w-[14rem] rounded-lg border border-ink-300 px-3 py-2 text-sm shadow-sm"></td>
                                <td class="py-1 pr-3"><input name="episodes[{{ $i }}][duration_minutes]" type="number" min="1" value="{{ $row['duration_minutes'] ?? '' }}" class="w-24 rounded-lg border border-ink-300 px-3 py-2 text-sm shadow-sm"></td>
                                <td class="py-1"><input type="checkbox" name="episodes[{{ $i }}][is_premium]" value="1" @checked(! empty($row['is_premium'])) class="h-4 w-4 rounded border-ink-300"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </x-card>

        <x-form-actions :cancel="route('shows.index')" />
    </form>
</x-layouts.app>
