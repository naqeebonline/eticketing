@php
    $stands = auth()->user()->assignedBusStands()->with('terminal')->orderBy('name')->get();
@endphp

@if($stands->isNotEmpty())
    @if($stands->count() === 1)
        @php $stand = $stands->first(); @endphp
        <a href="{{ route('admin.bus-stands.edit', $stand) }}" class="admin-stand-card block transition hover:border-primary-300 dark:hover:border-primary-600">
            <p class="admin-stand-card-name">{{ $stand->displayTitle() }}</p>
            <p class="admin-stand-card-meta">{{ $stand->displaySubtitle() }}</p>
            @if($stand->is_active)
            <span class="mt-2 inline-flex items-center gap-1 rounded-full bg-success-50 px-2 py-0.5 text-[10px] font-semibold text-success-700 dark:bg-success-500/10 dark:text-success-500">
                <span class="h-1.5 w-1.5 rounded-full bg-success-500"></span> Active
            </span>
            @endif
        </a>
    @else
        <a href="{{ route('admin.bus-stands.my') }}" class="admin-stand-card block transition hover:border-primary-300 dark:hover:border-primary-600">
            <p class="text-[10px] font-bold uppercase tracking-wider text-primary-600 dark:text-primary-400">{{ $stands->count() }} stands</p>
            <p class="admin-stand-card-name mt-1">{{ $stands->first()->displayTitle() }}</p>
            <p class="admin-stand-card-meta">+ {{ $stands->count() - 1 }} more · tap to switch</p>
        </a>
    @endif
@endif
