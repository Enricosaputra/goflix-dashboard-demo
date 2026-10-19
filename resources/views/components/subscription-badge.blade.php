@props(['user'])

@if ($user->hasActiveSubscription())
    <x-badge tone="green">{{ __('until :date', ['date' => $user->subscription_ends_on->translatedFormat('j M Y')]) }}</x-badge>
@elseif ($user->subscription_ends_on)
    <x-badge tone="amber">{{ __('expired :date', ['date' => $user->subscription_ends_on->translatedFormat('j M Y')]) }}</x-badge>
@else
    <x-badge>{{ __('never subscribed') }}</x-badge>
@endif
