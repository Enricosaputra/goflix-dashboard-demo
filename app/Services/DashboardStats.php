<?php

namespace App\Services;

use App\Models\Episode;
use App\Models\Movie;
use App\Models\Rental;
use App\Models\Subscription;
use App\Models\User;
use App\Models\WatchHistory;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

// The numbers on the dashboard. Grouping is done in PHP so the demo runs on SQLite or Postgres
// unchanged; with production volumes these would be SQL aggregates or a summary table.
class DashboardStats
{
    public function revenueThisMonth(): int
    {
        $from = now()->startOfMonth();

        return (int) Subscription::where('created_at', '>=', $from)->sum('amount') + (int) Rental::where('created_at', '>=', $from)->sum('amount');
    }

    public function activeSubscribers(): int
    {
        return User::where('is_active', true)->whereDate('subscription_ends_on', '>=', today())->count();
    }

    public function watchHours(): int
    {
        return (int) round(WatchHistory::sum('stopped_at_seconds') / 3600);
    }

    /** @return array{labels: list<string>, series: list<string>, subscriptions: list<int>, rentals: list<int>} thousands of IDR */
    public function monthlyRevenue(int $months = 12): array
    {
        $keys = collect(range($months - 1, 0))->map(fn ($back) => now()->startOfMonth()->subMonths($back)->format('Y-m'));
        $sum = fn (Collection $rows) => $rows->groupBy(fn ($row) => $row->created_at->format('Y-m'))->map->sum('amount');
        $since = now()->startOfMonth()->subMonths($months - 1);
        $subscriptions = $sum(Subscription::where('created_at', '>=', $since)->get(['amount', 'created_at']));
        $rentals = $sum(Rental::where('created_at', '>=', $since)->get(['amount', 'created_at']));

        return [
            'labels' => $keys->map(fn ($month) => Carbon::parse("$month-01")->translatedFormat('M'))->all(),
            'series' => [__('Subscriptions'), __('Rentals')],
            'subscriptions' => $keys->map(fn ($month) => (int) round(($subscriptions[$month] ?? 0) / 1000))->all(),
            'rentals' => $keys->map(fn ($month) => (int) round(($rentals[$month] ?? 0) / 1000))->all(),
        ];
    }

    /** @return Collection<int, array{title: string, viewers: int}> */
    public function topMovies(int $limit = 5): Collection
    {
        return WatchHistory::where('watchable_type', Movie::class)->with('watchable')->get()
            ->groupBy('watchable_id')
            ->map(fn (Collection $rows) => ['title' => $rows->first()->watchable->title, 'viewers' => $rows->unique('user_id')->count()])
            ->sortByDesc('viewers')->take($limit)->values();
    }

    /** @return Collection<string, int> genre name => number of plays */
    public function genrePopularity(int $limit = 6): Collection
    {
        $plays = WatchHistory::get(['watchable_type', 'watchable_id']);
        $movies = Movie::with('genres')->whereIn('id', $plays->where('watchable_type', Movie::class)->pluck('watchable_id'))->get()->keyBy('id');
        $episodes = Episode::with('show.genres')->whereIn('id', $plays->where('watchable_type', Episode::class)->pluck('watchable_id'))->get()->keyBy('id');

        return $plays
            ->flatMap(fn (WatchHistory $w) => ($w->watchable_type === Movie::class
                ? $movies->get($w->watchable_id)?->genres
                : $episodes->get($w->watchable_id)?->show->genres)?->pluck('name') ?? [])
            ->countBy()->sortDesc()->take($limit);
    }

    /** @return Collection<string, int> plan name => subscriptions sold */
    public function salesByPackage(): Collection
    {
        return Subscription::with('package')->get()->countBy(fn (Subscription $s) => $s->package->name)->sortDesc();
    }

    /** @return Collection<string, int> login type value => users */
    public function usersByLoginType(): Collection
    {
        return User::selectRaw('login_type, count(*) as total')->groupBy('login_type')->pluck('total', 'login_type')->sortDesc();
    }

    /** @return Collection<int, User> subscriptions that end within the next week */
    public function expiringSoon(): Collection
    {
        return User::whereBetween('subscription_ends_on', [today(), today()->addDays(7)])->orderBy('subscription_ends_on')->limit(6)->get();
    }
}
