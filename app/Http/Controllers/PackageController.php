<?php

namespace App\Http\Controllers;

use App\Http\Requests\PackageRequest;
use App\Models\Package;
use Illuminate\Support\Facades\DB;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::with('features')->withCount('subscriptions')->withSum('subscriptions as revenue', 'amount')
            ->orderBy('price')->paginate(15);

        return view('packages.index', compact('packages'));
    }

    public function create()
    {
        return view('packages.form', ['package' => new Package(['is_active' => true, 'ads_free' => true, 'watch_on_tv' => true, 'duration_months' => 1, 'max_devices' => 2, 'max_quality' => '1080p'])]);
    }

    public function store(PackageRequest $request)
    {
        DB::transaction(function () use ($request) {
            $package = Package::create($request->safe()->except('features'));
            $package->setFeatures($request->input('features', []));
        });

        return to_route('packages.index')->with('status', __('Saved.'));
    }

    public function edit(Package $package)
    {
        return view('packages.form', ['package' => $package->load('features')]);
    }

    public function update(PackageRequest $request, Package $package)
    {
        DB::transaction(function () use ($request, $package) {
            $package->update($request->safe()->except('features'));
            $package->setFeatures($request->input('features', []));
        });

        return to_route('packages.index')->with('status', __('Saved.'));
    }

    public function destroy(Package $package)
    {
        if ($reason = $package->deletionBlocker()) {
            return back()->with('error', $reason);
        }
        $package->delete();

        return back()->with('status', __('Deleted.'));
    }
}
