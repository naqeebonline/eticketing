@props(['seatRows' => []])

<div {{ $attributes->merge(['class' => 'bus-seat-map']) }}>
    <p class="mb-4 text-center text-xs font-semibold uppercase tracking-widest text-slate-400">↑ Front · Driver</p>

    <div class="mx-auto flex max-w-lg flex-col gap-3">
        @forelse($seatRows as $row)
        @php
            $rowClass = match (true) {
                ($row['left'] ?? 0) === 0 && ($row['right'] ?? 0) > 0 => 'bus-seat-row--right-only',
                ($row['right'] ?? 0) === 0 && ($row['left'] ?? 0) > 0 => 'bus-seat-row--left-only',
                default => '',
            };
        @endphp
        <div class="bus-seat-row {{ $rowClass }}">
            <span class="bus-seat-row-label">R{{ $row['row'] }}</span>
            <div class="bus-seat-row-seats">
                <div class="bus-seat-zone bus-seat-zone--left">
                    @foreach($row['left_seats'] ?? [] as $seat)
                    <span class="bus-seat bus-seat-preview">{{ is_array($seat) ? ($seat['seat_number'] ?? '·') : $seat }}</span>
                    @endforeach
                </div>
                @if(($row['left'] ?? 0) > 0 && ($row['right'] ?? 0) > 0)
                <div class="bus-aisle" aria-hidden="true"></div>
                @elseif(($row['left'] ?? 0) === 0 && ($row['right'] ?? 0) > 0)
                <div class="bus-aisle bus-aisle--spacer" aria-hidden="true"></div>
                @endif
                <div class="bus-seat-zone bus-seat-zone--right">
                    @foreach($row['right_seats'] ?? [] as $seat)
                    <span class="bus-seat bus-seat-preview">{{ is_array($seat) ? ($seat['seat_number'] ?? '·') : $seat }}</span>
                    @endforeach
                </div>
            </div>
        </div>
        @empty
        <p class="text-center text-sm text-slate-500">Add rows to preview the layout.</p>
        @endforelse
    </div>
</div>
