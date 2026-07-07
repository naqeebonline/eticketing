@props(['current' => 1])

@php
$steps = [
    1 => ['label' => 'Search', 'icon' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z'],
    2 => ['label' => 'Seats', 'icon' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5z'],
    3 => ['label' => 'Passengers', 'icon' => 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
    4 => ['label' => 'Ticket', 'icon' => 'M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z'],
];
$progress = count($steps) > 1 ? (($current - 1) / (count($steps) - 1)) * 100 : 0;
@endphp

<nav aria-label="Booking progress" class="booking-progress">
    <div class="relative mb-6 hidden sm:block">
        <div class="booking-progress-track" aria-hidden="true"></div>
        <div class="booking-progress-fill" style="width: {{ $progress }}%" aria-hidden="true"></div>
    </div>
    <ol class="flex min-w-max items-center justify-between gap-1 sm:gap-0">
        @foreach($steps as $num => $step)
        @php
            $state = $num < $current ? 'done' : ($num === $current ? 'active' : 'pending');
        @endphp
        <li class="booking-step booking-step-{{ $state }} relative z-10 flex flex-1 flex-col items-center gap-2 px-1">
            <span class="booking-step-dot">
                @if($state === 'done')
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                @else
                {{ $num }}
                @endif
            </span>
            <span class="text-center text-[11px] font-semibold sm:text-xs">{{ $step['label'] }}</span>
        </li>
        @endforeach
    </ol>
</nav>
