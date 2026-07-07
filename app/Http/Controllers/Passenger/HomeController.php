<?php

namespace App\Http\Controllers\Passenger;

use App\Http\Controllers\Controller;
use App\Models\Route;
use App\Services\City\CityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function __construct(private CityService $cityService) {}
    public function index(): View
    {
        $popularRoutes = Route::where('is_active', true)
            ->with('busStand')
            ->limit(6)
            ->get();

        return view('passenger.home', compact('popularRoutes'));
    }

    public function search(Request $request): RedirectResponse
    {
        $request->validate([
            'from' => $this->cityService->nameValidationRules(),
            'to' => $this->cityService->nameValidationRules(),
            'date' => 'required|date|after_or_equal:today',
        ]);

        return redirect()->route('book.results', $request->only('from', 'to', 'date'));
    }
}
