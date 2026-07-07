@props(['seat'])

@php
    $isLuxury = ($seat['type'] ?? 'normal') === 'luxury';
    $typeLabel = $isLuxury ? 'Luxury' : 'Normal';
    $fare = (float) ($seat['fare'] ?? 0);
@endphp

<button
    type="button"
    @if($seat['status'] !== 'available') disabled @endif
    @click="toggleSeat({{ $seat['id'] }})"
    :class="{
        'bus-seat-selected': selected.includes({{ $seat['id'] }}),
        'bus-seat-available': !selected.includes({{ $seat['id'] }}) && '{{ $seat['status'] }}' === 'available',
        'bus-seat-booked': '{{ $seat['status'] }}' === 'booked',
        'bus-seat-held': '{{ $seat['status'] }}' === 'held',
    }"
    class="bus-seat bus-seat-interactive {{ $isLuxury ? 'bus-seat--luxury' : '' }}"
    aria-label="Seat {{ $seat['seat_number'] }}, {{ $typeLabel }}, PKR {{ number_format($fare) }}"
>
    <span class="bus-seat-interactive__num">{{ $seat['seat_number'] }}</span>
    <span class="bus-seat-interactive__type">{{ $typeLabel }}</span>
    <span class="bus-seat-interactive__fare">PKR {{ number_format($fare) }}</span>
</button>
