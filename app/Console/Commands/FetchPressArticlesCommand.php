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

        // 1. Je met en cache local les sources existantes pour éviter le N+1
        $sourcesCache = Source::pluck('id', 'slug')->toArray();

        $articlesToUpsert = [];
        $success = 0;
        $errors = 0;

        foreach ($results as $result) {
            if (!is_array($result) || !array_key_exists('articles', $result)) {
                continue;
            }

            foreach ($result['articles'] as $article) {
                try {
                    $data = $article->toArray();
                    $slug = Str::slug($data['source']);

                    // 2. Gestion de la source via le cache local
                    if (!isset($sourcesCache[$slug])) {
                        $source = Source::create([
                            'slug' => $slug,
                            'name' => $data['source'],
                            'website' => $data['url'] ?? null,
                        ]);
                        $sourcesCache[$slug] = $source->id;
                    }
                    $sourceId = $sourcesCache[$slug];

                    // 3. Je prépare les données pour l'upsert de masse
                    $articlesToUpsert[] = [
                        'title'        => $data['title'],
                        'source_id'    => $sourceId,
                        'author'       => $data['authors'][0] ?? null,
                        'category'     => $data['category'] ?? null,
                        'content'      => $data['content'] ?? null,
                        'published_at' => $data['publishedAt'] ?? null,
                        'url'          => $data['url'] ?? null,
                        'created_at'   => now(), // Requis pour l'insertion de masse
                        'updated_at'   => now(),
                    ];

                    $success++;
                } catch (\Throwable $e) {
                    $title = $data['title'] ?? '(titre inconnu)';
                    $this->error("Erreur de parsing sur {$title} : {$e->getMessage()}");
                    $errors++;
                }
            }
        }

        // 4. J'envoie tout en BDD d'un seul coup (par paquets de 500 pour la sécurité)
        if (!empty($articlesToUpsert)) {
            $this->info('Enregistrement des articles en base de données...');

            foreach (array_chunk($articlesToUpsert, 500) as $chunk) {
                Article::upsert(
                    $chunk,
                    ['title', 'source_id'], // Les colonnes qui identifient l'unicité
                    ['author', 'category', 'content', 'published_at', 'url', 'updated_at'] // Les colonnes à mettre à jour si doublon
                );
            }
        }

        $this->info("$success articles traités / $errors erreurs de parsing");
    }
}
