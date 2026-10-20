<x-layouts.app title="Rentals">
    <x-page-header :title="__('Rentals')" :description="__('Single premium movies rented for a few days by users without a subscription.')">
        <x-button :href="route('rentals.create')">{{ __('Add rental') }}</x-button>
    </x-page-header>

    <form method="GET" class="mb-4">
        <select name="status" class="rounded-lg border border-ink-300 bg-white px-3 py-2 text-sm shadow-sm" onchange="this.form.submit()">
            <option value="">{{ __('All rentals') }}</option>
            <option value="active" @selected(request('status') === 'active')>{{ __('Still running') }}</option>
        </select>
    </form>

    <div class="overflow-x-auto rounded-xl border border-ink-200 bg-white shadow-sm">
        <table class="w-full min-w-[640px] text-left text-sm">
            <thead class="text-xs uppercase tracking-wide text-ink-400">
                <tr>
                    <th class="px-4 py-3 font-medium">{{ __('Paid on') }}</th>
                    <th class="px-4 py-3 font-medium">{{ __('User') }}</th>
                    <th class="px-4 py-3 font-medium">{{ __('Movie') }}</th>
                    <th class="px-4 py-3 font-medium">{{ __('Until') }}</th>
                    <th class="px-4 py-3 text-right font-medium">{{ __('Paid') }}</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-ink-100">
                @forelse ($rentals as $r)
                    <tr>
                        <td class="whitespace-nowrap px-4 py-3 text-ink-600">{{ $r->created_at->format('Y-m-d') }}</td>
                        <td class="px-4 py-3"><a href="{{ route('users.show', $r->user) }}" class="font-medium hover:underline">{{ $r->user->name }}</a></td>
                        <td class="px-4 py-3 text-ink-600">{{ $r->movie->title }}</td>
                        <td class="whitespace-nowrap px-4 py-3">
                            <x-badge :tone="$r->isActive() ? 'green' : 'slate'">{{ $r->expires_at->format('Y-m-d H:i') }}</x-badge>
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-right tabular-nums">{{ rupiah($r->amount) }}</td>
                    </tr>
                @empty
                    <x-empty-row span="5" />
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">{{ $rentals->links() }}</div>
</x-layouts.app>
