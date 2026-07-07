@props(['showLuxury' => false, 'compact' => false])

@php
$items = [
    [
        'label' => 'Available',
        'pill' => 'border-emerald-200 bg-emerald-50 text-emerald-800 dark:border-emerald-700 dark:bg-emerald-950/60 dark:text-emerald-300',
        'swatch' => 'border-2 border-emerald-500 bg-emerald-200 dark:border-emerald-400 dark:bg-emerald-700',
    ],
    [
        'label' => 'Selected',
        'pill' => 'border-indigo-200 bg-indigo-50 text-indigo-800 dark:border-indigo-700 dark:bg-indigo-950/60 dark:text-indigo-300',
        'swatch' => 'border-2 border-indigo-600 bg-indigo-500 ring-2 ring-indigo-300 dark:border-indigo-400 dark:bg-indigo-600 dark:ring-indigo-500/50',
    ],
    [
        'label' => 'Booked',
        'pill' => 'border-slate-200 bg-slate-100 text-slate-600 dark:border-slate-600 dark:bg-slate-800 dark:text-slate-400',
        'swatch' => 'border-2 border-slate-300 bg-slate-300 dark:border-slate-500 dark:bg-slate-600',
    ],
    [
        'label' => 'Held',
        'pill' => 'border-orange-200 bg-orange-50 text-orange-800 dark:border-orange-700 dark:bg-orange-950/60 dark:text-orange-300',
        'swatch' => 'border-2 border-orange-400 bg-orange-300 dark:border-orange-500 dark:bg-orange-700',
    ],
];

if ($showLuxury) {
    $items[] = [
        'label' => 'Luxury',
        'pill' => 'border-amber-300 bg-amber-50 text-amber-900 dark:border-amber-700 dark:bg-amber-950/60 dark:text-amber-200',
        'swatch' => 'border-2 border-amber-500 bg-gradient-to-br from-amber-300 to-amber-500 dark:from-amber-700 dark:to-amber-500',
    ];
}
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-wrap items-center gap-2 sm:gap-2.5 ' . ($compact ? 'justify-start' : 'justify-center')]) }}>
    @foreach($items as $item)
    <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1.5 text-xs font-semibold shadow-sm {{ $item['pill'] }}">
        <span class="inline-block h-4 w-4 shrink-0 rounded-md {{ $item['swatch'] }}" aria-hidden="true"></span>
        {{ $item['label'] }}
    </span>
    @endforeach
</div>
