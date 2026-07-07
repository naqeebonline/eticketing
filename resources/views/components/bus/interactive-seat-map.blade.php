@props(['seatRows' => []])

<div {{ $attributes->merge(['class' => 'booking-seat-map']) }}>
    <div class="booking-seat-map__front">
        <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
        Front · Driver
    </div>

    <div class="booking-seat-map__rows">
        @forelse($seatRows as $row)
        @php
            $rowClass = match (true) {
                ($row['left'] ?? 0) === 0 && ($row['right'] ?? 0) > 0 => 'bus-seat-row--right-only',
                ($row['right'] ?? 0) === 0 && ($row['left'] ?? 0) > 0 => 'bus-seat-row--left-only',
                default => '',
            };
            $leftSeats = $row['left_seats'] ?? [];
            $rightSeats = $row['right_seats'] ?? [];
            $leftType = $row['left_type'] ?? ($leftSeats[0]['type'] ?? 'normal');
            $rightType = $row['right_type'] ?? ($rightSeats[0]['type'] ?? 'normal');
            $leftFare = isset($leftSeats[0]['fare']) ? (float) $leftSeats[0]['fare'] : null;
            $rightFare = isset($rightSeats[0]['fare']) ? (float) $rightSeats[0]['fare'] : null;
        @endphp
        <div class="booking-seat-map__row">
            <span class="booking-seat-map__row-num">{{ $row['row'] }}</span>
            <div class="booking-seat-map__layout seat-map-preview__layout {{ $rowClass }}">
                <div class="booking-seat-map__zone booking-seat-map__zone--left seat-map-preview__zone seat-map-preview__zone--left">
                    @if(count($leftSeats) > 0)
                    <div class="seat-map-preview__zone-head {{ $leftType === 'luxury' ? 'seat-map-preview__zone-head--luxury' : '' }}">
                        <span class="seat-map-preview__zone-tag">Left · {{ $row['left_type_label'] ?? ($leftType === 'luxury' ? 'Luxury' : 'Normal') }}</span>
                        @if($leftFare !== null)
                        <span class="seat-map-preview__zone-fare">PKR {{ number_format($leftFare) }}</span>
                        @endif
                    </div>
                    <div class="booking-seat-map__seats seat-map-preview__seats">
                        @foreach($leftSeats as $seat)
                            <x-bus.interactive-seat-button :seat="$seat" />
                        @endforeach
                    </div>
                    @endif
                </div>

                @if(($row['left'] ?? 0) > 0 && ($row['right'] ?? 0) > 0)
                <div class="booking-seat-map__aisle seat-map-preview__aisle" aria-hidden="true"></div>
                @elseif(($row['left'] ?? 0) === 0 && ($row['right'] ?? 0) > 0)
                <div class="booking-seat-map__aisle seat-map-preview__aisle seat-map-preview__aisle--spacer" aria-hidden="true"></div>
                @endif

                <div class="booking-seat-map__zone booking-seat-map__zone--right seat-map-preview__zone seat-map-preview__zone--right">
                    @if(count($rightSeats) > 0)
                    <div class="seat-map-preview__zone-head {{ $rightType === 'luxury' ? 'seat-map-preview__zone-head--luxury' : '' }}">
                        <span class="seat-map-preview__zone-tag">Right · {{ $row['right_type_label'] ?? ($rightType === 'luxury' ? 'Luxury' : 'Normal') }}</span>
                        @if($rightFare !== null)
                        <span class="seat-map-preview__zone-fare">PKR {{ number_format($rightFare) }}</span>
                        @endif
                    </div>
                    <div class="booking-seat-map__seats seat-map-preview__seats">
                        @foreach($rightSeats as $seat)
                            <x-bus.interactive-seat-button :seat="$seat" />
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @empty
        <p class="text-center text-sm text-slate-500">Seat map is not configured for this bus. Please contact support.</p>
        @endforelse
    </div>
</div>
