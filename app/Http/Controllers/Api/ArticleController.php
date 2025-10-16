<?php

namespace App\Http\Controllers\Api;

use App\Application\Press\PressOrchestrator;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ArticleController extends Controller
{
    public function __construct(protected PressOrchestrator $orchestrator) {}

    public function index(Request $request): JsonResponse
    {

        $options = [
            'date'   => $request->query('date'),
            'created_after'   => $request->query('created_after'),
            'publish_date_gte'   => $request->query('publish_date_gte'),
            'page'   => $request->query('page'),
            'min_id' => $request->query('min_id'),
            'sort'   => $request->query('sort'),
        ];

        $articles = $this->orchestrator->fetchAllFrontpages($options);

        return response()->json($articles);
    }

}
