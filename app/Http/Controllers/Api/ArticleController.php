<?php

namespace App\Http\Controllers\Api;

use App\Application\Press\PressOrchestrator;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{
    public function __construct(protected PressOrchestrator $orchestrator) {}

    public function index(Request $request): JsonResponse
    {

        $validated = Validator::make($request->all(), [
            'date'             => 'nullable|date_format:Y-m-d',
            'created_after'    => 'nullable|date',
            'publish_date_gte' => 'nullable|date',
            'page'             => 'nullable|integer|min:1',
            'min_id'           => 'nullable|integer',
            'sort'             => 'nullable|string|in:asc,desc,id_asc,id_desc',
        ])->validate();

        $articles = $this->orchestrator->fetchAllFrontpages($validated);

        return response()->json($articles);
    }

}
