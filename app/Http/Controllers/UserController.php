<?php

namespace App\Http\Controllers;

use App\Enums\LoginType;
use App\Http\Requests\UserRequest;
use App\Models\Episode;
use App\Models\Movie;
use App\Models\User;
use App\Services\AccessService;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount('subscriptions')
            ->when(request('status') === 'active', fn ($q) => $q->whereDate('subscription_ends_on', '>=', today()))
            ->when(request('status') === 'expired', fn ($q) => $q->whereDate('subscription_ends_on', '<', today()))
            ->when(request('status') === 'never', fn ($q) => $q->whereNull('subscription_ends_on'))
            ->when(request('q'), function ($q, $term) {
                $like = '%'.mb_strtolower($term).'%';
                $q->where(fn ($w) => $w->whereRaw('lower(name) like ?', [$like])->orWhereRaw('lower(email) like ?', [$like]));
            })
            ->latest('id')->paginate(15)->withQueryString();

        return view('users.index', compact('users'));
    }

    public function show(User $user, AccessService $access)
    {
        $user->load(['subscriptions.package', 'rentals.movie', 'watchHistory' => fn ($q) => $q->with('watchable')->limit(10)]);

        // "Can this user watch X?" — the same rule the Go API applies before streaming.
        $check = null;
        if (preg_match('/^(movie|episode):(\d+)$/', (string) request('content'), $m)) {
            $content = $m[1] === 'movie' ? Movie::find($m[2]) : Episode::with('show', 'season')->find($m[2]);
            $check = $content ? $access->check($user, $content) + ['content' => $content] : null;
        }

        return view('users.show', [
            'user' => $user,
            'check' => $check,
            'movies' => Movie::orderBy('title')->get(['id', 'title', 'is_premium']),
            'episodes' => Episode::with('show', 'season')->get()->sortBy(fn (Episode $e) => $e->label()),
        ]);
    }

    public function create()
    {
        return view('users.form', ['user' => new User(['is_active' => true, 'login_type' => LoginType::Email]), 'types' => LoginType::cases()]);
    }

    public function store(UserRequest $request)
    {
        $user = User::create($request->validated());

        return to_route('users.show', $user)->with('status', __('Saved.'));
    }

    public function edit(User $user)
    {
        return view('users.form', ['user' => $user, 'types' => LoginType::cases()]);
    }

    public function update(UserRequest $request, User $user)
    {
        $user->update($request->validated());

        return to_route('users.show', $user)->with('status', __('Saved.'));
    }

    public function destroy(User $user)
    {
        if ($reason = $user->deletionBlocker()) {
            return back()->with('error', $reason);
        }
        $user->delete();

        return to_route('users.index')->with('status', __('Deleted.'));
    }
}
