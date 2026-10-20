@props(['title' => null, 'description' => null])

<section {{ $attributes->merge(['class' => 'rounded-xl border border-ink-200 bg-white shadow-sm']) }}>
    @if ($title)
        <header class="border-b border-ink-100 px-6 py-4">
            <h2 class="font-display text-lg font-semibold">{{ $title }}</h2>
            @if ($description)
                <p class="mt-0.5 text-sm text-ink-500">{{ $description }}</p>
            @endif
        </header>
    @endif
    <div class="p-6">{{ $slot }}</div>
</section>
