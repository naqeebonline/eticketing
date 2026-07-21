<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'departure_date' => $this->departure_date->format('Y-m-d'),
            'departure_time' => $this->departure_time,
            'arrival_time' => $this->arrival_time,
            'fare' => $this->fare,
            'available_seats' => $this->available_seats,
            'status' => $this->status,
            'route' => $this->whenLoaded('route', fn () => [
                'uuid' => $this->route->uuid,
                'name' => $this->route->name,
                'from' => $this->route->departure_city,
                'to' => $this->route->destination_city,
                'distance_km' => $this->route->distance_km,
                'duration_minutes' => $this->route->duration_minutes,
            ]),
            'vehicle' => $this->whenLoaded('vehicle', fn () => [
                'uuid' => $this->vehicle->uuid,
                'name' => $this->vehicle->name,
                'bus_number' => $this->vehicle->bus_number,
                'is_ac' => $this->vehicle->is_ac,
                'bus_type' => $this->vehicle->bus_type,
                'amenities' => $this->vehicle->amenities ?? [],
            ]),
            'bus_stand' => $this->whenLoaded('route', fn () => $this->route->relationLoaded('busStand') && $this->route->busStand ? [
                'name' => $this->route->busStand->name,
                'city' => $this->route->busStand->city,
            ] : null),
        ];
    }
}
