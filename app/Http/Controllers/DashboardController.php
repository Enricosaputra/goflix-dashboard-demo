<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\User;
use App\Models\WatchHistory;
use App\Services\DashboardStats;

class DashboardController extends Controller
{
    public function __invoke(DashboardStats $stats)
    {
        return view('dashboard.index', [
            'revenue' => $stats->revenueThisMonth(),
            'activeSubscribers' => $stats->activeSubscribers(),
            'users' => User::count(),
            'watchHours' => $stats->watchHours(),
            'catalog' => Movie::where('is_active', true)->count(),
            'monthly' => $stats->monthlyRevenue(),
            'topMovies' => $stats->topMovies(),
            'genres' => $stats->genrePopularity(),
            'packages' => $stats->salesByPackage(),
            'loginTypes' => $stats->usersByLoginType(),
            'expiring' => $stats->expiringSoon(),
            'recent' => WatchHistory::with(['user', 'watchable'])->latest('updated_at')->limit(6)->get(),
        ]);
    }
}
