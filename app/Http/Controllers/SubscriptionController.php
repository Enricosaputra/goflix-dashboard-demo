<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Package;
use App\Models\Subscription;
use App\Models\User;
use App\Services\SubscriptionService;
use DomainException;
use Illuminate\Http\Request;

// Payments are records, so there is no edit or delete. A mistake is fixed with a new entry.
class SubscriptionController extends Controller
{
    public function index()
    {
        $subscriptions = Subscription::with(['user', 'package', 'coupon'])
            ->when(request('package'), fn ($q, $id) => $q->where('package_id', $id))
            ->latest('created_at')->latest('id')->paginate(20)->withQueryString();

        return view('subscriptions.index', ['subscriptions' => $subscriptions, 'packages' => Package::orderBy('price')->get()]);
    }

    public function create()
    {
        return view('subscriptions.form', [
            'users' => User::where('is_active', true)->orderBy('name')->get(),
            'packages' => Package::where('is_active', true)->orderBy('price')->get(),
            'coupons' => Coupon::where('is_active', true)->orderBy('code')->get(),
        ]);
    }

    public function store(Request $request, SubscriptionService $subscriptions)
    {
        $data = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'package_id' => ['required', 'exists:packages,id'],
            'coupon_id' => ['nullable', 'exists:coupons,id'],
            'payment_reference' => ['nullable', 'string', 'max:100'],
        ]);

        try {
            $subscription = $subscriptions->subscribe(
                User::findOrFail($data['user_id']),
                Package::findOrFail($data['package_id']),
                isset($data['coupon_id']) ? Coupon::find($data['coupon_id']) : null,
                $data['payment_reference'] ?? null,
            );
        } catch (DomainException $e) {
            return back()->withInput()->withErrors(['coupon_id' => $e->getMessage()]);
        }

        return to_route('users.show', $subscription->user_id)->with('status', __('Subscription added until :date.', ['date' => $subscription->ends_on->translatedFormat('j M Y')]));
    }
}
