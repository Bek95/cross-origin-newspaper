<?php

namespace App\Console\Commands;

use App\Domain\Press\Models\Article;
use App\Domain\Press\Models\Source;
use App\Domain\Press\Repositories\EloquentArticleRepository;
use Illuminate\Console\Command;
use App\Application\Press\PressOrchestrator;
use Illuminate\Support\Str;

class FetchPressArticlesCommand extends Command
{
    protected $signature = 'press:fetch';
    protected $description = 'Get press articles from all sources with the PressOrechestrator';

    public function __construct(
        protected PressOrchestrator $orchestrator,
        protected EloquentArticleRepository $repository
    ) {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Début de la récupération des articles...');

        $results = $this->orchestrator->fetchAllFrontpages();
        $success = 0;
        $errors = 0;

        foreach ($results as $result) {

            // vérification
            if (!is_array($result) || !array_key_exists('articles', $result)) {
                continue;
            }

            foreach ($result['articles'] as $article) {
                $data = [];

                try {
                    $data = $article->toArray();

                    $slug = Str::slug($data['source']);
                    $source = Source::firstOrCreate(
                        ['slug' => $slug],
                        [
                            'name' => $data['source'],
                            'website' => $data['url'] ?? null,
                        ]
                    );

                    Article::updateOrCreate(
                        [
                            'title' => $data['title'],
                            'source_id' => $source->id,
                        ],
                        [
                            'author' => $data['authors'][0] ?? null,
                            'category' => $data['category'] ?? null,
                            'content' => $data['content'] ?? null,
                            'published_at' => $data['publishedAt'] ?? null,
                            'url' => $data['url'] ?? null,
                        ]
                    );

                    $success++;
                } catch (\Throwable $e) {
                    $title = $data['title'] ?? '(titre inconnu)';
                    $this->error("Erreur sur {$title} : {$e->getMessage()}");
                    $errors++;
                }
            }
        }

        $this->info("$success articles insérés / $errors erreurs");
    }
}

