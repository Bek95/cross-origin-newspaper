<?php

namespace App\Http\Controllers\Api;

use App\Application\Press\PressOrchestrator;
use App\Domain\Press\Services\SourceServices\LeMondeService;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    public function __construct(protected PressOrchestrator $orchestrator) {}

    public function index(LeMondeService $leMondeService): JsonResponse
    {
        $today = Carbon::now()->format('Y-m-d');

        $articles = $this->orchestrator->fetchAllFrontpages($today);

        return response()->json($articles);
    }

}
