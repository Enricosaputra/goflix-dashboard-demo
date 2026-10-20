@props(['headings'])

<div class="overflow-x-auto rounded-xl border border-ink-200 bg-white shadow-sm">
    <table class="w-full min-w-[640px] text-left text-sm">
        <thead class="text-xs uppercase tracking-wide text-ink-400">
            <tr>
                @foreach ($headings as $heading)
                    <th class="px-4 py-3 font-medium {{ str_starts_with($heading, '>') ? 'text-right' : '' }}">{{ __(ltrim($heading, '>')) }}</th>
                @endforeach
                <th class="px-4 py-3"></th>
            </tr>
        </thead>
        <tbody class="divide-y divide-ink-100">{{ $slot }}</tbody>
    </table>
</div>
