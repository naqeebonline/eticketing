<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Services\Report\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService) {}

    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        $isPlatformView = $user->isSuperAdmin();

        $standIds = $isPlatformView
            ? ($request->filled('bus_stand_id') ? [$request->integer('bus_stand_id')] : null)
            : $user->manageableBusStandIds();

        return response()->json($this->dashboardService->getAdminStats($standIds, $isPlatformView));
    }
}
