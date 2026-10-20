@props(['title', 'description' => null])

<div class="mb-8 flex flex-wrap items-start justify-between gap-4">
    <div>
        <h1 class="font-display text-3xl font-bold tracking-tight">{{ $title }}</h1>
        @if ($description)
            <p class="mt-1 max-w-2xl text-sm text-ink-500">{{ $description }}</p>
        @endif
    </div>
    <div class="flex flex-wrap items-center gap-2">{{ $slot }}</div>
</div>
