<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\BusStandController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\TerminalController;
use App\Http\Controllers\Admin\TerminalUserController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\RouteController as AdminRouteController;
use App\Http\Controllers\Admin\ScheduleController;
use App\Http\Controllers\Admin\VehicleController;
use App\Http\Controllers\Auth\WebAuthController;
use App\Http\Controllers\Passenger\BookingFlowController;
use App\Http\Controllers\Passenger\HomeController;
use App\Http\Controllers\TicketVerificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/locale/{locale}', function (string $locale) {
    if (in_array($locale, config('bss.supported_locales'))) {
        session(['locale' => $locale]);
        cookie()->queue('locale', $locale, 60 * 24 * 365);
    }

    return back();
})->name('locale.switch');

Route::get('/theme/{theme}', function (string $theme) {
    if (in_array($theme, ['light', 'dark'])) {
        session(['theme' => $theme]);
        cookie()->queue('theme', $theme, 60 * 24 * 365);
        if (auth()->check()) {
            auth()->user()->update(['theme' => $theme]);
        }
    }

    return back();
})->name('theme.switch');

Route::middleware('guest')->group(function () {
    Route::get('/login', [WebAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [WebAuthController::class, 'login']);
    Route::get('/register', [WebAuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [WebAuthController::class, 'register']);
});

Route::post('/logout', [WebAuthController::class, 'logout'])->middleware('auth')->name('logout');

// Passenger booking flow
Route::prefix('book')->name('book.')->group(function () {
    Route::get('/results', [BookingFlowController::class, 'results'])->name('results');
    Route::get('/{schedule}/seats', [BookingFlowController::class, 'seats'])->name('seats');
    Route::post('/{schedule}/seats/hold', [BookingFlowController::class, 'holdSeats'])->name('seats.hold');
    Route::get('/{schedule}/passengers', [BookingFlowController::class, 'passengers'])->name('passengers');
    Route::post('/{schedule}/passengers', [BookingFlowController::class, 'storePassengers'])->name('passengers.store');
    Route::get('/payment/{booking}', [BookingFlowController::class, 'payment'])->name('payment');
    Route::post('/payment/{booking}', [BookingFlowController::class, 'processPayment'])->name('payment.process');
    Route::get('/ticket/{booking}', [BookingFlowController::class, 'ticket'])->name('ticket');
});

Route::get('/bookings/{booking}/ticket', [BookingFlowController::class, 'ticket'])->name('bookings.ticket');
Route::get('/ticket/verify/{booking}', [TicketVerificationController::class, 'show'])->name('ticket.verify');

// Admin panel — shared dashboard (all 3 admin roles)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:super_admin,terminal_admin,admin'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

// Super Admin — cities, terminals (+ credentials)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'super_admin'])->group(function () {
    Route::resource('cities', CityController::class)->except(['show']);
    Route::resource('terminals', TerminalController::class)->except(['show', 'edit', 'update']);
    Route::delete('bus-stands/{busStand}', [BusStandController::class, 'destroy'])->name('bus-stands.destroy');
});

// Super Admin + Terminal Admin — bus stands & routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:super_admin,terminal_admin'])->group(function () {
    Route::get('bus-stands', [BusStandController::class, 'index'])->name('bus-stands.index');
    Route::get('bus-stands/create', [BusStandController::class, 'create'])->name('bus-stands.create');
    Route::post('bus-stands', [BusStandController::class, 'store'])->name('bus-stands.store');

});

// Routes — all admin roles (single registration; stand-scoped in RouteService)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:super_admin,terminal_admin,admin'])->group(function () {
    Route::get('routes', [AdminRouteController::class, 'index'])->name('routes.index');
    Route::get('routes/create', [AdminRouteController::class, 'create'])->name('routes.create');
    Route::post('routes', [AdminRouteController::class, 'store'])->name('routes.store');
    Route::get('routes/{route}/edit', [AdminRouteController::class, 'edit'])->name('routes.edit');
    Route::put('routes/{route}', [AdminRouteController::class, 'update'])->name('routes.update');
});

// Bus stand edit — all admin roles (must be a single registration; duplicate names overwrite middleware)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:super_admin,terminal_admin,admin'])->group(function () {
    Route::get('bus-stands/{busStand}/edit', [BusStandController::class, 'edit'])->name('bus-stands.edit');
    Route::put('bus-stands/{busStand}', [BusStandController::class, 'update'])->name('bus-stands.update');
});

// Terminal edit — super + terminal admin (single registration; duplicate names overwrite middleware)
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:super_admin,terminal_admin'])->group(function () {
    Route::get('terminals/{terminal}/edit', [TerminalController::class, 'edit'])->name('terminals.edit');
    Route::put('terminals/{terminal}', [TerminalController::class, 'update'])->name('terminals.update');
});

// Terminal / Adda Admin — terminal profile + stand users
Route::prefix('admin')->name('admin.')->middleware(['auth', 'terminal_admin'])->group(function () {
    Route::get('my-terminal', [TerminalController::class, 'myTerminal'])->name('terminals.my');

    Route::get('terminal-users', [TerminalUserController::class, 'index'])->name('terminal-users.index');
    Route::get('terminal-users/create', [TerminalUserController::class, 'create'])->name('terminal-users.create');
    Route::post('terminal-users', [TerminalUserController::class, 'store'])->name('terminal-users.store');
    Route::get('terminal-users/{terminalUser}/edit', [TerminalUserController::class, 'edit'])->name('terminal-users.edit');
    Route::put('terminal-users/{terminalUser}', [TerminalUserController::class, 'update'])->name('terminal-users.update');
});

// Bus Stand Admin — vehicles, schedules, bookings, own stand profile
Route::prefix('admin')->name('admin.')->middleware(['auth', 'bus_stand_admin'])->group(function () {
    Route::get('my-bus-stand', [BusStandController::class, 'myStand'])->name('bus-stands.my');

    Route::resource('vehicles', VehicleController::class);
    Route::resource('drivers', DriverController::class)->except(['show', 'destroy']);
    Route::get('schedule-plans/{weeklySchedulePlan}/edit', [ScheduleController::class, 'editPlan'])->name('schedules.plan.edit');
    Route::put('schedule-plans/{weeklySchedulePlan}', [ScheduleController::class, 'updatePlan'])->name('schedules.plan.update');
    Route::resource('schedules', ScheduleController::class)->except(['show', 'destroy']);
    Route::get('bookings', [AdminBookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/create', [AdminBookingController::class, 'create'])->name('bookings.create');
    Route::get('bookings/schedules/{schedule}/seats', [AdminBookingController::class, 'seats'])->name('bookings.seats');
    Route::post('bookings/schedules/{schedule}/seats', [AdminBookingController::class, 'holdSeats'])->name('bookings.seats.hold');
    Route::get('bookings/schedules/{schedule}/passengers', [AdminBookingController::class, 'passengers'])->name('bookings.passengers');
    Route::post('bookings/schedules/{schedule}/passengers', [AdminBookingController::class, 'store'])->name('bookings.passengers.store');
    Route::post('bookings/{booking}/passengers/cancel', [AdminBookingController::class, 'cancelPassengers'])->name('bookings.passengers.cancel');
    Route::post('bookings/{booking}/confirm', [AdminBookingController::class, 'confirm'])->name('bookings.confirm');
    Route::get('bookings/{booking}/receipt', [AdminBookingController::class, 'receipt'])->name('bookings.receipt');
    Route::get('bookings/{booking}', [AdminBookingController::class, 'show'])->name('bookings.show');
});
