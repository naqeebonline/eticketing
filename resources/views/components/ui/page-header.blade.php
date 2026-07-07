@props(['title', 'subtitle' => null])

<div class="page-header mb-8">
    <div>
        <h1 class="font-display text-2xl font-bold tracking-tight text-slate-900 dark:text-white sm:text-3xl">{{ $title }}</h1>
        @if($subtitle)<p class="mt-1.5 text-sm text-slate-500 dark:text-slate-400">{{ $subtitle }}</p>@endif
    </div>
    @isset($actions)
    <div class="flex flex-wrap items-center gap-2">{{ $actions }}</div>
    @endisset
</div>
