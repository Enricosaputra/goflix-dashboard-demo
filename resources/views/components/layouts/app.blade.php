@props(['title' => 'Dashboard'])

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ __($title) }} - Goflix Dashboard</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased lg:grid lg:grid-cols-[16rem_1fr]">
    @php
        $menu = [
            [null, [['dashboard', 'Dashboard', 'dashboard']]],
            ['Content', [['movies.index', 'Movies', 'movies.*'], ['shows.index', 'Series', 'shows.*']]],
            ['Master data', [['genres.index', 'Genres', 'genres.*'], ['languages.index', 'Languages', 'languages.*'], ['people.index', 'Cast and crew', 'people.*'], ['seasons.index', 'Seasons', 'seasons.*']]],
            ['Users and payments', [['users.index', 'Users', 'users.*'], ['subscriptions.index', 'Subscriptions', 'subscriptions.*'], ['rentals.index', 'Rentals', 'rentals.*']]],
            ['Plans', [['packages.index', 'Plans', 'packages.*'], ['coupons.index', 'Coupons', 'coupons.*']]],
        ];
    @endphp

    <aside class="bg-slate-900 p-4 lg:sticky lg:top-0 lg:flex lg:h-screen lg:flex-col lg:overflow-y-auto lg:p-5">
        <div class="mb-4 flex items-center justify-between lg:mb-8">
            <a href="{{ route('dashboard') }}" class="font-semibold text-white">Goflix <span class="font-normal text-slate-400">Dashboard</span></a>
            <div class="flex rounded-lg bg-white/10 p-0.5 text-xs font-medium" aria-label="{{ __('Language') }}">
                @foreach (['id' => 'ID', 'en' => 'EN'] as $locale => $label)
                    <a href="{{ route('locale', $locale) }}"
                       class="rounded-md px-2 py-1 {{ app()->getLocale() === $locale ? 'bg-white text-slate-900' : 'text-slate-300 hover:text-white' }}">{{ $label }}</a>
                @endforeach
            </div>
        </div>

        <nav class="flex items-center gap-1 overflow-x-auto lg:flex-col lg:items-stretch lg:overflow-visible">
            @foreach ($menu as [$heading, $items])
                <div class="flex items-center gap-1 lg:flex-col lg:items-stretch">
                    @if ($heading)
                        <p class="hidden px-3 pb-1 pt-5 text-[11px] font-semibold uppercase tracking-wider text-slate-500 lg:block">{{ __($heading) }}</p>
                    @endif
                    @foreach ($items as [$route, $label, $pattern])
                        <a href="{{ route($route) }}"
                           class="flex-shrink-0 rounded-lg px-3 py-2 text-sm font-medium transition-colors {{ request()->routeIs($pattern) ? 'bg-white/10 text-white' : 'text-slate-400 hover:bg-white/5 hover:text-white' }}">
                            {{ __($label) }}
                        </a>
                    @endforeach
                </div>
            @endforeach
        </nav>

    </aside>

    <main class="min-w-0 px-4 py-8 sm:px-8 lg:px-10 lg:py-10">
        <div class="mx-auto max-w-5xl">
            @if (session('status'))
                <p role="status" class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 p-3 text-sm text-emerald-800">{{ session('status') }}</p>
            @endif
            @if (session('error'))
                <p role="alert" class="mb-6 rounded-lg border border-red-200 bg-red-50 p-3 text-sm text-red-800">{{ session('error') }}</p>
            @endif

            {{ $slot }}
        </div>
    </main>
</body>
</html>
