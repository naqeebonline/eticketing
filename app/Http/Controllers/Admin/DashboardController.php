<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\Report\DashboardService;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __construct(private DashboardService $dashboardService) {}

    public function index(): View
    {
        $user = auth()->user();
        $isPlatformView = $user->isSuperAdmin();
        $isTerminalView = $user->isTerminalAdmin();
        $standIds = $isPlatformView ? null : $user->manageableBusStandIds();

        $stats = $this->dashboardService->getAdminStats($standIds, $isPlatformView);

        return view('admin.dashboard', compact('stats', 'isPlatformView', 'isTerminalView', 'standIds'));
    }
}
