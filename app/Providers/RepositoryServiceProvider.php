<?php

namespace App\Providers;

use App\Contracts\Repositories\BookingRepositoryInterface;
use App\Contracts\Repositories\ScheduleRepositoryInterface;
use App\Repositories\BookingRepository;
use App\Repositories\ScheduleRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->bind(ScheduleRepositoryInterface::class, ScheduleRepository::class);
    }
}
