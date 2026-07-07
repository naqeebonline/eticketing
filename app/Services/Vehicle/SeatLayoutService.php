<?php

namespace App\Services\Vehicle;

use App\Models\Seat;
use App\Models\SeatMap;
use Illuminate\Support\Collection;

class SeatLayoutService
{
    /**
     * @param  array<int, array{left: int, right: int, left_type?: string, right_type?: string, row_type?: string}>  $rowDefs
     * @return array{layout: array, rows: int, columns: int, total_seats: int, seat_definitions: list<array<string, mixed>>}
     */
    public function buildLayout(array $rowDefs, float $normalFare, float $luxuryFare): array
    {
        $normalized = [];
        $maxColumns = 0;
        $seatNumber = 1;
        $seatDefinitions = [];

        foreach (array_values($rowDefs) as $index => $row) {
            $left = max(0, min(4, (int) ($row['left'] ?? 0)));
            $right = max(0, min(4, (int) ($row['right'] ?? 0)));
            $legacyType = $this->normalizeRowType($row['row_type'] ?? 'normal');
            $leftType = $this->normalizeRowType($row['left_type'] ?? $legacyType);
            $rightType = $this->normalizeRowType($row['right_type'] ?? $legacyType);
            $leftFare = $leftType === 'luxury' ? $luxuryFare : $normalFare;
            $rightFare = $rightType === 'luxury' ? $luxuryFare : $normalFare;

            if ($left + $right < 1) {
                continue;
            }

            $rowNum = $index + 1;
            $normalized[] = [
                'row' => $rowNum,
                'left' => $left,
                'right' => $right,
                'left_type' => $leftType,
                'right_type' => $rightType,
            ];

            $column = 1;
            for ($i = 0; $i < $left; $i++) {
                $seatDefinitions[] = $this->seatDefinition($seatNumber++, $rowNum, $column++, 'left', $leftType, $leftFare);
            }

            if ($left > 0 && $right > 0) {
                $column++;
            }

            for ($i = 0; $i < $right; $i++) {
                $seatDefinitions[] = $this->seatDefinition($seatNumber++, $rowNum, $column++, 'right', $rightType, $rightFare);
            }

            $maxColumns = max($maxColumns, $column - 1);
        }

        return [
            'layout' => [
                'type' => 'row_aisle',
                'rows' => $normalized,
                'normal_fare' => $normalFare,
                'luxury_fare' => $luxuryFare,
            ],
            'rows' => count($normalized),
            'columns' => max($maxColumns, 1),
            'total_seats' => count($seatDefinitions),
            'seat_definitions' => $seatDefinitions,
        ];
    }

    private function normalizeRowType(string $type): string
    {
        return $type === 'luxury' ? 'luxury' : 'normal';
    }

    private function typeLabel(string $type): string
    {
        return $type === 'luxury' ? 'Luxury' : 'Normal';
    }

    /** @return array<string, mixed> */
    private function seatDefinition(
        int $seatNumber,
        int $row,
        int $column,
        string $side,
        string $rowType,
        float $fare,
    ): array {
        return [
            'seat_number' => (string) $seatNumber,
            'row' => $row,
            'column' => $column,
            'side' => $side,
            'type' => $rowType,
            'fare_amount' => $fare,
        ];
    }

    public function createSeats(SeatMap $seatMap, array $seatDefinitions): void
    {
        foreach ($seatDefinitions as $def) {
            Seat::create([
                'seat_map_id' => $seatMap->id,
                'seat_number' => $def['seat_number'],
                'row' => $def['row'],
                'column' => $def['column'],
                'type' => $def['type'] ?? 'normal',
                'fare_amount' => $def['fare_amount'] ?? null,
                'fare_multiplier' => 1,
            ]);
        }
    }

    /**
     * @param  Collection<int, array<string, mixed>>  $seats
     * @return array<int, array<string, mixed>>
     */
    public function groupSeatsForDisplay(Collection $seats, ?array $layout): array
    {
        $layout = $layout ?? [];

        if (($layout['type'] ?? '') !== 'row_aisle' || empty($layout['rows'])) {
            return $this->groupLegacyGrid($seats);
        }

        $rowMeta = collect($layout['rows'])->keyBy('row');
        $grouped = [];

        foreach ($seats->sortBy(['row', 'column']) as $seat) {
            $rowNum = (int) $seat['row'];
            if (! isset($grouped[$rowNum])) {
                $meta = $rowMeta->get($rowNum, ['left' => 0, 'right' => 0, 'left_type' => 'normal', 'right_type' => 'normal']);
                $legacyType = $this->normalizeRowType($meta['row_type'] ?? 'normal');
                $leftType = $this->normalizeRowType($meta['left_type'] ?? $legacyType);
                $rightType = $this->normalizeRowType($meta['right_type'] ?? $legacyType);
                $grouped[$rowNum] = [
                    'row' => $rowNum,
                    'left' => (int) ($meta['left'] ?? 0),
                    'right' => (int) ($meta['right'] ?? 0),
                    'left_type' => $leftType,
                    'right_type' => $rightType,
                    'left_type_label' => $this->typeLabel($leftType),
                    'right_type_label' => $this->typeLabel($rightType),
                    'row_type_label' => $this->combinedTypeLabel($leftType, $rightType),
                    'left_seats' => [],
                    'right_seats' => [],
                ];
            }

            $leftCount = $grouped[$rowNum]['left'];
            $rightCount = $grouped[$rowNum]['right'];

            if ($leftCount === 0) {
                $grouped[$rowNum]['right_seats'][] = $seat;
            } elseif ($rightCount === 0) {
                $grouped[$rowNum]['left_seats'][] = $seat;
            } elseif ((int) $seat['column'] <= $leftCount) {
                $grouped[$rowNum]['left_seats'][] = $seat;
            } else {
                $grouped[$rowNum]['right_seats'][] = $seat;
            }
        }

        foreach ($grouped as &$rowGroup) {
            usort($rowGroup['left_seats'], fn ($a, $b) => $a['column'] <=> $b['column']);
            usort($rowGroup['right_seats'], fn ($a, $b) => $a['column'] <=> $b['column']);
        }
        unset($rowGroup);

        $ordered = [];
        foreach ($layout['rows'] as $rowDef) {
            $rowNum = (int) ($rowDef['row'] ?? 0);
            if (isset($grouped[$rowNum])) {
                $ordered[] = $grouped[$rowNum];
            }
        }

        return $ordered !== [] ? $ordered : array_values($grouped);
    }

    private function combinedTypeLabel(string $leftType, string $rightType): string
    {
        if ($leftType === $rightType) {
            return $this->typeLabel($leftType);
        }

        return 'L: '.$this->typeLabel($leftType).' · R: '.$this->typeLabel($rightType);
    }

    /** @param  Collection<int, array<string, mixed>>  $seats */
    private function groupLegacyGrid(Collection $seats): array
    {
        return $seats->groupBy('row')->map(function (Collection $rowSeats, $rowNum) {
            $sorted = $rowSeats->sortBy('column')->values()->all();
            $half = (int) ceil(count($sorted) / 2);

            return [
                'row' => (int) $rowNum,
                'left' => $half,
                'right' => count($sorted) - $half,
                'left_type' => 'normal',
                'right_type' => 'normal',
                'left_type_label' => 'Normal',
                'right_type_label' => 'Normal',
                'row_type_label' => 'Normal',
                'left_seats' => array_slice($sorted, 0, $half),
                'right_seats' => array_slice($sorted, $half),
            ];
        })->sortKeys()->values()->all();
    }
}
