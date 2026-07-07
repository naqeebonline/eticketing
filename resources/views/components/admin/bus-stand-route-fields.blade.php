@props([
    'toCity' => '',
])

<x-ui.form-section
    title="Route"
    description="From = terminal city (auto). To = destination — har stand alag route."
    icon="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"
>
    <div class="grid gap-4 sm:grid-cols-2">
        <div>
            <label class="form-label">From</label>
            <input type="text" class="input-field bg-slate-100 dark:bg-slate-800" readonly x-model="fromCity">
            <p class="form-hint">Terminal ki city — auto</p>
        </div>
        <x-ui.city-select
            label="To"
            name="to_city"
            :value="old('to_city', $toCity)"
            required
            hint="Terminal city select nahi ho sakti"
        />
    </div>

    <div class="rounded-xl border border-slate-200 bg-slate-50/80 px-4 py-3 dark:border-slate-700 dark:bg-slate-800/40">
        <p class="text-xs font-semibold uppercase tracking-wider text-slate-500">Route preview</p>
        <p class="mt-1 text-lg font-semibold text-slate-900 dark:text-white" x-text="routeLabel || '—'"></p>
    </div>

    <div class="space-y-3">
        <label class="flex items-center gap-2 text-sm cursor-pointer">
            <input type="checkbox" x-model="customLabel" class="rounded border-slate-300 text-primary-600">
            <span class="font-medium text-slate-700 dark:text-slate-300">Custom stand name</span>
        </label>
        <div x-show="customLabel" x-cloak>
            <label class="form-label">Stand name</label>
            <input type="text" x-model="standName" class="input-field" placeholder="e.g. Saddar counter — Lahore route">
        </div>
        <input type="hidden" name="name" :value="customLabel ? standName : routeLabel">
    </div>
</x-ui.form-section>
