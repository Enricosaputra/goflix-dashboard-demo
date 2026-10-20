<x-layouts.app title="Subscriptions">
    <x-page-header :title="__('Subscriptions')" :description="__('Every plan payment. Payments are records: they are never edited or deleted.')">
        <x-button :href="route('subscriptions.create')">{{ __('Add subscription') }}</x-button>
    </x-page-header>

    <form method="GET" class="mb-4 flex flex-wrap gap-3">
        <select name="package" class="rounded-lg border border-ink-300 bg-white px-3 py-2 text-sm shadow-sm" onchange="this.form.submit()">
            <option value="">{{ __('All plans') }}</option>
            @foreach ($packages as $package)
                <option value="{{ $package->id }}" @selected((string) request('package') === (string) $package->id)>{{ $package->name }}</option>
            @endforeach
        </select>
    </form>

    <div class="overflow-x-auto rounded-xl border border-ink-200 bg-white shadow-sm">
        <table class="w-full min-w-[720px] text-left text-sm">
            <thead class="text-xs uppercase tracking-wide text-ink-400">
                <tr>
                    <th class="px-4 py-3 font-medium">{{ __('Paid on') }}</th>
                    <th class="px-4 py-3 font-medium">{{ __('User') }}</th>
                    <th class="px-4 py-3 font-medium">{{ __('Plan') }}</th>
                    <th class="px-4 py-3 font-medium">{{ __('Period') }}</th>
                    <th class="px-4 py-3 text-right font-medium">{{ __('Paid') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                @forelse ($subscriptions as $s)
                    <tr>
                        <td class="whitespace-nowrap px-4 py-3 text-ink-600">{{ $s->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3"><a href="{{ route('users.show', $s->user) }}" class="font-medium hover:underline">{{ $s->user->name }}</a></td>
                        <td class="px-4 py-3 text-ink-600">{{ $s->package->name }}@if ($s->coupon) <span class="block font-mono text-xs text-ink-500">{{ $s->coupon->code }}</span>@endif</td>
                        <td class="whitespace-nowrap px-4 py-3 text-ink-600">{{ $s->starts_on->format('Y-m-d') }} - {{ $s->ends_on->format('Y-m-d') }}</td>
                        <td class="whitespace-nowrap px-4 py-3 text-right tabular-nums">
                            {{ rupiah($s->amount) }}
                            @if ($s->discount) <span class="block text-xs text-ink-500">{{ __(':amount off', ['amount' => rupiah($s->discount)]) }}</span>@endif
                        </td>
                    </tr>
                @empty
                    <x-empty-row span="5" />
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $subscriptions->links() }}</div>
</x-layouts.app>
