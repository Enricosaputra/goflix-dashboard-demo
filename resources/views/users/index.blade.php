<x-layouts.app title="Users">
    <x-page-header :title="__('Users')" :description="__('People who use the app. They sign up through the app; support can add or fix accounts here.')">
        <x-button :href="route('users.create')">{{ __('New user') }}</x-button>
    </x-page-header>

    <x-search>
        <select name="status" class="rounded-lg border border-ink-300 bg-white px-3 py-2 text-sm shadow-sm">
            <option value="">{{ __('Everyone') }}</option>
            <option value="active" @selected(request('status') === 'active')>{{ __('Subscribed now') }}</option>
            <option value="expired" @selected(request('status') === 'expired')>{{ __('Subscription expired') }}</option>
            <option value="never" @selected(request('status') === 'never')>{{ __('Never subscribed') }}</option>
        </select>
    </x-search>

    <x-table :headings="['Name', 'Sign-in', 'Subscription', '>Payments', 'Account']">
        @forelse ($users as $user)
            <tr>
                <td class="px-4 py-3">
                    <a href="{{ route('users.show', $user) }}" class="block font-medium hover:underline">{{ $user->name }}</a>
                    <span class="block text-xs text-ink-500">{{ $user->email }}</span>
                </td>
                <td class="px-4 py-3 text-ink-600">{{ $user->login_type->label() }}</td>
                <td class="px-4 py-3"><x-subscription-badge :user="$user" /></td>
                <td class="px-4 py-3 text-right tabular-nums">{{ $user->subscriptions_count }}</td>
                <td class="px-4 py-3"><x-active-badge :active="$user->is_active" /></td>
                <td class="px-4 py-3">
                    <x-row-actions :edit="route('users.edit', $user)" :destroy="route('users.destroy', $user)" :confirm="__('Delete :name?', ['name' => $user->name])" />
                </td>
            </tr>
        @empty
            <x-empty-row span="6" />
        @endforelse
    </x-table>

    <div class="mt-4">{{ $users->links() }}</div>
</x-layouts.app>
