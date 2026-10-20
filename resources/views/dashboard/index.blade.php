<x-layouts.app title="Dashboard">
    <x-page-header :title="__('Dashboard')" :description="__('Revenue comes from subscriptions and rentals; viewing numbers come from what the app records while people watch.')" />

    @php
        $cards = [
            [__('Revenue this month'), rupiah($revenue), __('subscriptions and rentals')],
            [__('Active subscribers'), $activeSubscribers, __('of :count users', ['count' => $users])],
            [__('Hours watched'), number_format($watchHours, 0, ',', '.'), __('all time')],
            [__('Movies in the catalog'), $catalog, __('active titles')],
        ];
        $bar = fn ($value, $max) => max(2, $value / max(1, $max) * 100);
    @endphp

    <div class="grid grid-cols-2 gap-4 lg:grid-cols-4">
        @foreach ($cards as [$label, $value, $note])
            <div class="rounded-xl border border-ink-200 bg-white p-5 shadow-sm">
                <p class="whitespace-nowrap text-xl font-bold">{{ $value }}</p>
                <p class="text-sm text-ink-500">{{ $label }}</p>
                <p class="mt-1 text-xs text-ink-400">{{ $note }}</p>
            </div>
        @endforeach
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-3">
        <x-card :title="__('Revenue per month')" :description="__('Thousands of rupiah')" class="lg:col-span-2">
            <div class="h-64"><canvas id="revenue-chart" data-chart='@json($monthly)'></canvas></div>
        </x-card>

        <x-card :title="__('Ending this week')" :description="__('Subscriptions to remind about renewal')">
            <ul class="-my-2 divide-y divide-ink-100 text-sm">
                @forelse ($expiring as $u)
                    <li class="flex justify-between gap-3 py-2">
                        <a href="{{ route('users.show', $u) }}" class="truncate hover:underline">{{ $u->name }}</a>
                        <span class="whitespace-nowrap text-ink-500">{{ $u->subscription_ends_on->translatedFormat('j M') }}</span>
                    </li>
                @empty
                    <li class="py-2 text-ink-500">{{ __('Nothing here yet.') }}</li>
                @endforelse
            </ul>
        </x-card>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <x-card :title="__('Most watched movies')" :description="__('By number of viewers')">
            @php $most = $topMovies->max('viewers'); @endphp
            <ul class="space-y-3">
                @foreach ($topMovies as $row)
                    <li>
                        <div class="mb-1 flex justify-between text-sm"><span class="truncate">{{ $row['title'] }}</span><span class="text-ink-500">{{ $row['viewers'] }}</span></div>
                        <div class="h-2 overflow-hidden rounded-full bg-ink-100"><div class="h-full rounded-full bg-ink-900" style="width: {{ $bar($row['viewers'], $most) }}%"></div></div>
                    </li>
                @endforeach
            </ul>
        </x-card>

        <x-card :title="__('Popular genres')" :description="__('By plays of movies and episodes')">
            @php $most = $genres->max(); @endphp
            <ul class="space-y-3">
                @foreach ($genres as $genre => $plays)
                    <li>
                        <div class="mb-1 flex justify-between text-sm"><span>{{ $genre }}</span><span class="text-ink-500">{{ $plays }}</span></div>
                        <div class="h-2 overflow-hidden rounded-full bg-ink-100"><div class="h-full rounded-full bg-accent-600" style="width: {{ $bar($plays, $most) }}%"></div></div>
                    </li>
                @endforeach
            </ul>
        </x-card>

        <x-card :title="__('Plans sold')">
            @php $most = $packages->max(); @endphp
            <ul class="space-y-3">
                @foreach ($packages as $name => $count)
                    <li>
                        <div class="mb-1 flex justify-between text-sm"><span>{{ $name }}</span><span class="text-ink-500">{{ $count }}</span></div>
                        <div class="h-2 overflow-hidden rounded-full bg-ink-100"><div class="h-full rounded-full bg-ink-900" style="width: {{ $bar($count, $most) }}%"></div></div>
                    </li>
                @endforeach
            </ul>
        </x-card>

        <x-card :title="__('How users sign in')">
            @php $most = $loginTypes->max(); @endphp
            <ul class="space-y-3">
                @foreach ($loginTypes as $type => $count)
                    <li>
                        <div class="mb-1 flex justify-between text-sm"><span>{{ \App\Enums\LoginType::from($type)->label() }}</span><span class="text-ink-500">{{ $count }}</span></div>
                        <div class="h-2 overflow-hidden rounded-full bg-ink-100"><div class="h-full rounded-full bg-ink-400" style="width: {{ $bar($count, $most) }}%"></div></div>
                    </li>
                @endforeach
            </ul>
        </x-card>
    </div>

    <x-card :title="__('Watched recently')" class="mt-6">
        <ul class="-my-2 divide-y divide-ink-100 text-sm">
            @foreach ($recent as $w)
                <li class="flex items-center justify-between gap-3 py-2">
                    <span class="min-w-0">
                        <span class="block truncate font-medium">{{ $w->watchable instanceof \App\Models\Episode ? $w->watchable->label() : $w->watchable?->title }}</span>
                        <span class="block text-xs text-ink-500">{{ $w->user->name }} · {{ __(':percent% watched', ['percent' => $w->progress()]) }}</span>
                    </span>
                    <span class="whitespace-nowrap text-xs text-ink-500">{{ $w->updated_at->diffForHumans() }}</span>
                </li>
            @endforeach
        </ul>
    </x-card>
</x-layouts.app>
