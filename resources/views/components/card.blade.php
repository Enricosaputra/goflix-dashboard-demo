@props(['title' => null, 'description' => null])

<section {{ $attributes->merge(['class' => 'rounded-xl border border-slate-200 bg-white shadow-sm']) }}>
    @if ($title)
        <header class="border-b border-slate-100 px-6 py-4">
            <h2 class="font-semibold">{{ $title }}</h2>
            @if ($description)
                <p class="mt-0.5 text-sm text-slate-500">{{ $description }}</p>
            @endif
        </header>
    @endif
    <div class="p-6">{{ $slot }}</div>
</section>
