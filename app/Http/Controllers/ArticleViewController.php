<?php

namespace App\Http\Controllers;

use App\Application\Press\PressOrchestrator;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class ArticleViewController extends Controller
{
    public function __construct(protected PressOrchestrator $orchestrator) {}

    public function index(Request $request): View
    {
        // get all articles
        $allArticles = $this->orchestrator->fetchAllFrontpages();
        $flatArticles = [];

        foreach ($allArticles as $serviceName => $serviceData) {
            if (isset($serviceData['articles']['data'])) {
                $flatArticles = array_merge($flatArticles, $serviceData['articles']['data']);
            }
        }

        usort($flatArticles, function($a, $b) {
            $dateA = isset($a['created_at']) ? strtotime($a['created_at']) : 0;
            $dateB = isset($b['created_at']) ? strtotime($b['created_at']) : 0;
            return $dateB <=> $dateA;
        });

        $perPage = 10;
        $page = $request->get('page', 1);
        $paginated = new LengthAwarePaginator(
            array_slice($flatArticles, ($page - 1) * $perPage, $perPage),
            count($flatArticles),
            $perPage,
            $page,
            ['path' => url()->current()]
        );

        return view('articles.index', [
            'articles' => $paginated
        ]);
    }
}
