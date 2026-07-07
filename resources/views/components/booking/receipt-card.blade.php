@props(['booking', 'showQr' => true])

@php
    $activePassengers = $booking->passengers->whereNull('cancelled_at')->sortBy(fn ($p) => (int) ($p->seat?->seat_number ?? $p->id));
    $primaryPassenger = $activePassengers->first(fn ($p) => $p->passenger_type === 'adult') ?? $activePassengers->first();
    $maleCount = $activePassengers->where('gender', 'male')->where('passenger_type', 'adult')->count();
    $femaleCount = $activePassengers->where('gender', 'female')->where('passenger_type', 'adult')->count();
    $childCount = $activePassengers->where('passenger_type', 'child')->count();
    $travelerSummary = collect([
        $maleCount > 0 ? $maleCount.' Male' : null,
        $femaleCount > 0 ? $femaleCount.' Female' : null,
        $childCount > 0 ? $childCount.' Child' : null,
    ])->filter()->join(', ');
    $contactName = $primaryPassenger
        ? preg_replace('/\s+\(Child \d+\)$/', '', $primaryPassenger->full_name)
        : '—';
    $luxuryCount = $activePassengers->filter(fn ($p) => ($p->seat?->type ?? 'normal') === 'luxury')->count();
    $normalCount = $activePassengers->count() - $luxuryCount;
    $verifyUrl = $booking->ticketVerificationUrl();
    $statusLabel = ucfirst($booking->status->value ?? $booking->status);
    $departureLine = $booking->schedule->departure_date->format('D, M d, Y').' · '.\Carbon\Carbon::parse($booking->schedule->departure_time)->format('h:i A');
@endphp

<div {{ $attributes->merge(['class' => 'thermal-receipt']) }}>
    {{-- Thermal / print header --}}
    <div class="thermal-slip__top print:block hidden">
        <p class="thermal-slip__brand">BSS Booking</p>
        <p class="thermal-slip__number">{{ $booking->booking_number }}</p>
        <p class="thermal-slip__status">{{ $statusLabel }}</p>
    </div>

    {{-- Screen header --}}
    <div class="ticket-card-header thermal-receipt__header print:hidden">
        <div class="thermal-receipt__header-pattern pointer-events-none absolute inset-0 opacity-50"></div>
        <p class="relative text-sm font-medium text-primary-100">Booking receipt</p>
        <p class="relative mt-2 font-mono text-2xl font-bold tracking-wider">{{ $booking->booking_number }}</p>
        <span class="relative mt-4 inline-flex rounded-full bg-white/20 px-3 py-1 text-xs font-semibold backdrop-blur">{{ $statusLabel }}</span>
    </div>

    <div class="thermal-slip__body">
        <div class="thermal-slip__divider print:block hidden"></div>

        {{-- Route --}}
        <div class="thermal-slip__route">
            <p class="thermal-slip__route-cities print:block hidden">
                {{ $booking->schedule->route->departure_city }}
                <span class="thermal-slip__arrow">→</span>
                {{ $booking->schedule->route->destination_city }}
            </p>
            <p class="thermal-slip__route-date print:block hidden">{{ $departureLine }}</p>

            <div class="thermal-receipt-route flex items-center justify-center gap-3 px-4 py-4 print:hidden">
                <div class="text-center">
                    <p class="text-xs uppercase text-slate-400">From</p>
                    <p class="font-display text-xl font-bold text-slate-900 dark:text-white">{{ $booking->schedule->route->departure_city }}</p>
                </div>
                <div class="flex flex-col items-center gap-1 text-primary-500">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
                    <span class="text-xs font-medium">{{ $booking->schedule->departure_date->format('M d') }}</span>
                </div>
                <div class="text-center">
                    <p class="text-xs uppercase text-slate-400">To</p>
                    <p class="font-display text-xl font-bold text-slate-900 dark:text-white">{{ $booking->schedule->route->destination_city }}</p>
                </div>
            </div>
        </div>

        <div class="thermal-slip__divider"></div>

        @foreach([
            ['Passenger', $contactName],
            ['CNIC', $primaryPassenger?->cnic ?? '—'],
            ['Travelers', $travelerSummary ?: '—'],
            ['Bus', ($booking->schedule->vehicle->name ?? 'Bus').' ('.($booking->schedule->vehicle->bus_number ?? '—').')'],
        ] as [$label, $value])
        <div class="thermal-slip__row">
            <span class="thermal-slip__label">{{ $label }}</span>
            <span class="thermal-slip__value">{{ $value }}</span>
        </div>
        @endforeach
        <div class="thermal-slip__row print:hidden">
            <span class="thermal-slip__label">Departure</span>
            <span class="thermal-slip__value">{{ $departureLine }}</span>
        </div>

        <div class="thermal-slip__divider"></div>

        <div class="thermal-slip__seats">
            <div class="thermal-slip__seats-head">
                <span>Seats ({{ $activePassengers->count() }})</span>
                <span>
                    @if($normalCount > 0){{ $normalCount }} Normal @endif
                    @if($normalCount > 0 && $luxuryCount > 0), @endif
                    @if($luxuryCount > 0){{ $luxuryCount }} Luxury @endif
                </span>
            </div>
            <div class="thermal-slip__seat-grid">
                <div class="thermal-slip__seat-row thermal-slip__seat-row--head">
                    <span>Seat</span>
                    <span>Type</span>
                    <span>Fare</span>
                </div>
                @foreach($activePassengers as $passenger)
                @php
                    $isLuxury = ($passenger->seat?->type ?? 'normal') === 'luxury';
                @endphp
                <div class="thermal-slip__seat-row">
                    <span class="thermal-slip__seat-num">{{ $passenger->seat?->seat_number ?? '—' }}</span>
                    <span class="thermal-slip__seat-type {{ $isLuxury ? 'thermal-slip__seat-type--luxury' : '' }}">
                        {{ $isLuxury ? 'Luxury' : 'Normal' }}
                    </span>
                    <span class="thermal-slip__seat-fare">{{ number_format($passenger->fare) }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="thermal-slip__divider"></div>

        <div class="thermal-slip__total">
            <span>Total</span>
            <span>PKR {{ number_format($booking->total_amount) }}</span>
        </div>
        @if((float) $booking->paid_amount > 0)
        <div class="thermal-slip__total thermal-slip__total--paid">
            <span>Paid</span>
            <span>PKR {{ number_format($booking->paid_amount) }}</span>
        </div>
        @endif
    </div>

    @if($showQr)
    <div class="thermal-slip__qr">
        <div class="thermal-slip__divider"></div>
        <p class="thermal-slip__qr-label">Scan to verify</p>
        <div class="ticket-qr-wrap mx-auto">
            {!! QrCode::size(110)->margin(0)->generate($verifyUrl) !!}
        </div>
        <p class="thermal-receipt__verify-hint text-xs text-slate-500 print:hidden">QR scan — ticket verify</p>
        <a href="{{ $verifyUrl }}" class="thermal-receipt__verify-link mt-2 inline-block break-all text-xs font-medium text-primary-600 hover:underline print:hidden" target="_blank" rel="noopener">{{ $verifyUrl }}</a>
    </div>
    @endif
</div>
