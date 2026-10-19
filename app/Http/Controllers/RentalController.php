<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Movie;
use App\Models\Rental;
use App\Models\User;
use App\Services\RentalService;
use DomainException;
use Illuminate\Http\Request;

class RentalController extends Controller
{
    public function index()
    {
        $rentals = Rental::with(['user', 'movie', 'coupon'])
            ->when(request('status') === 'active', fn ($q) => $q->where('expires_at', '>', now()))
            ->latest('created_at')->latest('id')->paginate(20)->withQueryString();

        return view('rentals.index', compact('rentals'));
    }

    public function create()
    {
        return view('rentals.form', [
            'users' => User::where('is_active', true)->orderBy('name')->get(),
            'movies' => Movie::where('is_active', true)->where('is_premium', true)->whereNotNull('rent_price')->orderBy('title')->get(),
            'coupons' => Coupon::where('is_active', true)->orderBy('code')->get(),
        ]);
    }

    public function store(Request $request, RentalService $rentals)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'movie_id' => ['required', 'exists:movies,id'],
            'coupon_id' => ['nullable', 'exists:coupons,id'],
        ]);

        try {
            $rental = $rentals->rent(
                User::findOrFail($data['user_id']),
                Movie::findOrFail($data['movie_id']),
                isset($data['coupon_id']) ? Coupon::find($data['coupon_id']) : null,
            );
        } catch (DomainException $e) {
            return back()->withInput()->withErrors(['movie_id' => $e->getMessage()]);
        }

        return to_route('users.show', $rental->user_id)->with('status', __('Rental added until :date.', ['date' => $rental->expires_at->translatedFormat('j M Y H:i')]));
    }
}
