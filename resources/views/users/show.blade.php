<x-layouts.app title="Users">
    <x-page-header :title="$user->name" :description="$user->email.' · '.$user->login_type->label()">
        <x-button :href="route('subscriptions.create', ['user' => $user->id])" variant="outline">{{ __('Add subscription') }}</x-button>
        <x-button :href="route('rentals.create', ['user' => $user->id])" variant="outline">{{ __('Add rental') }}</x-button>
        <x-button :href="route('users.edit', $user)">{{ __('Edit') }}</x-button>
    </x-page-header>

    <div class="grid gap-6 lg:grid-cols-3">
        <x-card :title="__('Subscription status')">
            <x-subscription-badge :user="$user" />
            @unless ($user->is_active)
                <p class="mt-2 text-sm text-red-700">{{ __('This account is inactive, so nothing can be watched.') }}</p>
            @endunless
        </x-card>

        <x-card :title="__('Can this user watch it?')" :description="__('The same rule the app\'s API checks before it plays a video.')" class="lg:col-span-2">
            <form method="GET" class="flex flex-wrap gap-2">
                <select name="content" class="min-w-0 flex-1 rounded-lg border border-ink-300 bg-white px-3 py-2 text-sm shadow-sm">
                    <optgroup label="{{ __('Movies') }}">
                        @foreach ($movies as $movie)
                            <option value="movie:{{ $movie->id }}" @selected(request('content') === "movie:{$movie->id}")>{{ $movie->title }}{{ $movie->is_premium ? ' ('.__('Premium').')' : '' }}</option>
                        @endforeach
                    </optgroup>
                    <optgroup label="{{ __('Episodes') }}">
                        @foreach ($episodes as $episode)
                            <option value="episode:{{ $episode->id }}" @selected(request('content') === "episode:{$episode->id}")>{{ $episode->label() }}{{ $episode->isPremium() ? ' ('.__('Premium').')' : '' }}</option>
                        @endforeach
                    </optgroup>
                </select>
                <x-button>{{ __('Check') }}</x-button>
            </form>
            @if ($check)
                <p class="mt-3 rounded-lg p-3 text-sm {{ $check['allowed'] ? 'bg-emerald-50 text-emerald-800' : 'bg-amber-50 text-amber-800' }}">
                    <strong>{{ $check['allowed'] ? __('Allowed.') : __('Not allowed.') }}</strong> {{ $check['reason'] }}
                </p>
            @endif
        </x-card>
    </div>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        <x-card :title="__('Subscriptions')">
            <ul class="-my-2 divide-y divide-ink-100 text-sm">
                @forelse ($user->subscriptions as $s)
                    <li class="flex justify-between gap-3 py-2">
                        <span>{{ $s->package->name }} <span class="block text-xs text-ink-500">{{ $s->starts_on->translatedFormat('j M Y') }} - {{ $s->ends_on->translatedFormat('j M Y') }}</span></span>
                        <span class="whitespace-nowrap tabular-nums">{{ rupiah($s->amount) }}</span>
                    </li>
                @empty
                    <li class="py-2 text-ink-500">{{ __('Nothing here yet.') }}</li>
                @endforelse
            </ul>
        </x-card>

        <x-card :title="__('Rentals')">
            <ul class="-my-2 divide-y divide-ink-100 text-sm">
                @forelse ($user->rentals as $r)
                    <li class="flex justify-between gap-3 py-2">
                        <span>{{ $r->movie->title }} <span class="block text-xs text-ink-500">{{ $r->isActive() ? __('until :date', ['date' => $r->expires_at->translatedFormat('j M Y H:i')]) : __('ended :date', ['date' => $r->expires_at->translatedFormat('j M Y')]) }}</span></span>
                        <span class="whitespace-nowrap tabular-nums">{{ rupiah($r->amount) }}</span>
                    </li>
                @empty
                    <li class="py-2 text-ink-500">{{ __('Nothing here yet.') }}</li>
                @endforelse
            </ul>
        </x-card>
    </div>

    <x-card :title="__('Continue watching')" :description="__('Written by the app while the user watches; the dashboard only reads it.')" class="mt-6">
        <ul class="-my-2 divide-y divide-ink-100 text-sm">
            @forelse ($user->watchHistory as $w)
                <li class="py-2">
                    <div class="flex justify-between gap-3">
                        <span>{{ $w->watchable instanceof \App\Models\Episode ? $w->watchable->label() : $w->watchable?->title }}</span>
                        <span class="whitespace-nowrap text-xs text-ink-500">{{ $w->updated_at->diffForHumans() }}</span>
                    </div>
                    <div class="mt-1 h-1.5 overflow-hidden rounded-full bg-ink-100"><div class="h-full rounded-full bg-ink-900" style="width: {{ $w->progress() }}%"></div></div>
                </li>
            @empty
                <li class="py-2 text-ink-500">{{ __('Nothing here yet.') }}</li>
            @endforelse
        </ul>
    </x-card>
</x-layouts.app>
