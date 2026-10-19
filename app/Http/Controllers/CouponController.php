<?php

namespace App\Http\Controllers;

use App\Enums\AmountType;
use App\Http\Requests\CouponRequest;
use App\Models\Coupon;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::withCount(['subscriptions', 'rentals'])
            ->when(request('q'), fn ($q, $term) => $q->whereRaw('lower(code) like ?', ['%'.mb_strtolower($term).'%']))
            ->latest('ends_on')->paginate(15)->withQueryString();

        return view('coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('coupons.form', ['coupon' => new Coupon(['is_active' => true, 'amount_type' => AmountType::Percent, 'starts_on' => today(), 'ends_on' => today()->addMonth()]), 'types' => AmountType::cases()]);
    }

    public function store(CouponRequest $request)
    {
        Coupon::create($request->validated());

        return to_route('coupons.index')->with('status', __('Saved.'));
    }

    public function edit(Coupon $coupon)
    {
        return view('coupons.form', ['coupon' => $coupon, 'types' => AmountType::cases()]);
    }

    public function update(CouponRequest $request, Coupon $coupon)
    {
        $coupon->update($request->validated());

        return to_route('coupons.index')->with('status', __('Saved.'));
    }

    public function destroy(Coupon $coupon)
    {
        if ($reason = $coupon->deletionBlocker()) {
            return back()->with('error', $reason);
        }
        $coupon->delete();

        return back()->with('status', __('Deleted.'));
    }
}
