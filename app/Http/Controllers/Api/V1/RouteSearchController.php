<?php

namespace App\Http\Controllers\Api\V1;

use App\Contracts\Repositories\ScheduleRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\ScheduleResource;
use App\Services\City\CityService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RouteSearchController extends Controller
{
    public function __construct(
        private ScheduleRepositoryInterface $scheduleRepository,
        private CityService $cityService,
    ) {}

    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'from' => $this->cityService->nameValidationRules(),
            'to' => $this->cityService->nameValidationRules(),
            'date' => 'required|date|after_or_equal:today',
        ]);

        $schedules = $this->scheduleRepository->searchAvailable(
            $request->from,
            $request->to,
            $request->date
        );

        return response()->json([
            'data' => ScheduleResource::collection($schedules),
            'meta' => [
                'from' => $request->from,
                'to' => $request->to,
                'date' => $request->date,
                'count' => $schedules->count(),
            ],
        ]);
    }

    public function cities(): JsonResponse
    {
        return response()->json([
            'data' => $this->cityService->activeNames()->values(),
        ]);
    }
}
