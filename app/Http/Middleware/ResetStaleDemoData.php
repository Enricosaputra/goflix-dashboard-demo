<?php

namespace App\Http\Middleware;

use App\Console\Commands\ResetDemoData;
use Closure;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

// The public demo has no login, so anyone can change the data. Put it back every few hours.
// This also builds the tables on the first request against an empty database, and rebuilds them
// after a deploy that changed the schema.
class ResetStaleDemoData
{
    public function handle(Request $request, Closure $next): Response
    {
        $hours = config('demo.reset_hours');

        if ($hours > 0 && $this->isStale($hours)) {
            $this->reset();
        }

        return $next($request);
    }

    private function isStale(int $hours): bool
    {
        try {
            return now()->timestamp - (int) Cache::get('demo:last-reset', 0) >= $hours * 3600
                || Cache::get('demo:schema') !== ResetDemoData::schemaVersion();
        } catch (QueryException) {
            return true; // a brand-new database has no cache table yet
        }
    }

    private function reset(): void
    {
        try {
            Cache::lock('demo:resetting', 120)->get(fn () => Artisan::call('demo:reset'));
        } catch (QueryException) {
            Artisan::call('demo:reset'); // first run: the lock table does not exist yet either
        }
    }
}
