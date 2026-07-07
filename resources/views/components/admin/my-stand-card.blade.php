@props(['stand'])

<a
    href="{{ route('admin.bus-stands.edit', $stand) }}"
    class="group relative flex flex-col overflow-hidden rounded-2xl border border-slate-200/90 bg-white p-0 shadow-soft transition hover:border-primary-300 hover:shadow-lg dark:border-slate-700/80 dark:bg-slate-900 dark:hover:border-primary-700"
>
    <div class="border-b border-slate-100 bg-gradient-to-r from-primary-600/10 via-transparent to-sky-500/10 px-5 py-4 dark:border-slate-800">
        <div class="flex items-start justify-between gap-3">
            <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl bg-primary-600 text-white shadow-md shadow-primary-500/30">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
            </div>
            @if($stand->is_active)
            <span class="inline-flex items-center gap-1 rounded-full bg-success-50 px-2.5 py-1 text-[10px] font-bold uppercase tracking-wide text-success-700 dark:bg-success-500/10 dark:text-success-400">
                <span class="h-1.5 w-1.5 rounded-full bg-success-500"></span> Active
            </span>
            @else
            <span class="rounded-full bg-slate-100 px-2.5 py-1 text-[10px] font-bold uppercase text-slate-500 dark:bg-slate-800">Inactive</span>
            @endif
        </div>
        <h3 class="mt-4 font-display text-lg font-bold tracking-tight text-slate-900 group-hover:text-primary-700 dark:text-white dark:group-hover:text-primary-300">
            {{ $stand->displayTitle() }}
        </h3>
        @if($stand->looksLikeRouteName($stand->name) && $stand->address)
        <p class="mt-1 text-xs text-amber-700 dark:text-amber-400">Rename stand in settings — use counter name, not route</p>
        @endif
    </div>

    <div class="flex flex-1 flex-col gap-4 p-5">
        <div class="space-y-1 text-sm text-slate-600 dark:text-slate-400">
            @if($stand->terminal)
            <p class="flex items-center gap-2">
                <svg class="h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                <span class="font-medium text-slate-800 dark:text-slate-200">{{ $stand->terminal->name }}</span>
            </p>
            @endif
            <p class="flex items-start gap-2">
                <svg class="mt-0.5 h-4 w-4 shrink-0 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                <span>{{ $stand->address }}</span>
            </p>
        </div>

        <div class="grid grid-cols-2 gap-2">
            <div class="rounded-xl bg-slate-50 px-3 py-2.5 dark:bg-slate-800/60">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Vehicles</p>
                <p class="font-display text-xl font-bold text-slate-900 dark:text-white">{{ $stand->vehicles_count ?? 0 }}</p>
            </div>
            <div class="rounded-xl bg-slate-50 px-3 py-2.5 dark:bg-slate-800/60">
                <p class="text-[10px] font-bold uppercase tracking-wider text-slate-400">Routes</p>
                <p class="font-display text-xl font-bold text-slate-900 dark:text-white">{{ $stand->active_routes_count ?? 0 }}</p>
            </div>
        </div>

        <span class="mt-auto inline-flex items-center gap-1 text-sm font-semibold text-primary-600 dark:text-primary-400">
            Manage stand
            <svg class="h-4 w-4 transition group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </span>
    </div>
</a>
