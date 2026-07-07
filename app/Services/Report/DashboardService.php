<?php

namespace App\Services\Report;

use App\Enums\BookingStatus;
use App\Models\Booking;
use App\Models\BusStand;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Terminal;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Eloquent\Builder;

class DashboardService
{
    /**
     * @param  list<int>|null  $busStandIds  null = platform-wide (super admin)
     */
    public function getAdminStats(?array $busStandIds = null, bool $platformView = false): array
    {
        $bookingQuery = Booking::query();
        $scheduleQuery = Schedule::query()->where('departure_date', '>=', today());

        if ($busStandIds !== null) {
            $bookingQuery->whereHas('schedule.route', fn (Builder $q) => $q->whereIn('bus_stand_id', $busStandIds));
            $scheduleQuery->whereHas('route', fn (Builder $q) => $q->whereIn('bus_stand_id', $busStandIds));
        }

        $todayBookings = (clone $bookingQuery)->whereDate('created_at', today())->count();
        $todayRevenue = (clone $bookingQuery)
            ->whereDate('created_at', today())
            ->where('status', BookingStatus::Confirmed)
            ->sum('total_amount');

        $routeQuery = Route::query()->where('is_active', true);
        $vehicleQuery = Vehicle::query()->where('is_active', true);

        if ($busStandIds !== null) {
            $routeQuery->whereIn('bus_stand_id', $busStandIds);
            $vehicleQuery->whereIn('bus_stand_id', $busStandIds);
        }

        $stats = [
            'total_bookings' => (clone $bookingQuery)->count(),
            'today_bookings' => $todayBookings,
            'today_revenue' => $todayRevenue,
            'active_routes' => $routeQuery->count(),
            'running_buses' => (clone $scheduleQuery)->whereIn('status', ['boarding', 'departed'])->count(),
            'upcoming_schedules' => (clone $scheduleQuery)->whereIn('status', ['scheduled', 'boarding'])->count(),
            'total_vehicles' => $vehicleQuery->count(),
            'total_stands' => $busStandIds !== null
                ? count($busStandIds)
                : BusStand::where('is_active', true)->count(),
            'recent_bookings' => (clone $bookingQuery)
                ->with(['user', 'schedule.route'])
                ->latest()
                ->limit(8)
                ->get(),
            'upcoming_departures' => (clone $scheduleQuery)
                ->with(['route', 'vehicle'])
                ->whereIn('status', ['scheduled', 'boarding'])
                ->orderBy('departure_date')
                ->orderBy('departure_time')
                ->limit(6)
                ->get(),
        ];

        if ($platformView) {
            $stats['registered_stands'] = BusStand::count();
            $stats['active_stand_admins'] = User::role('admin')
                ->whereHas('assignedBusStands')
                ->count();
            $stats['registered_terminals'] = Terminal::where('is_active', true)->count();
            $stats['active_cities'] = \App\Models\City::where('is_active', true)->count();
        }

        if ($busStandIds !== null && ! $platformView) {
            $stats['active_stands'] = BusStand::query()
                ->whereIn('id', $busStandIds)
                ->where('is_active', true)
                ->count();
        }

        return $stats;
    }
}
