<?php

namespace App\Http\Controllers\Api;

use App\Application\Press\PressOrchestrator;
use App\Domain\Press\Services\SourceServices\LeMondeService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ArticleController extends Controller
{
    public function __construct(protected PressOrchestrator $orchestrator) {}

    public function index(LeMondeService $leMondeService): JsonResponse
    {
        $articles = $this->orchestrator->fetchAllFrontpages();

        return response()->json($articles);
    }

}
