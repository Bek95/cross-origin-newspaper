<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Domain\Press\Models\Article;
use App\Application\Press\PressOrchestrator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;

class FetchPressArticlesCommandTest extends TestCase
{
    use RefreshDatabase;

    public function test_press_fetch_command_inserts_articles_and_sources()
    {

        $articleMock = new class {
            public function toArray() {
                return [
                    'title' => 'Test Article',
                    'content' => 'Contenu de test',
                    'source' => 'Le Monde',
                    'authors' => ['John Doe'],
                    'category' => 'Economie',
                    'publishedAt' => '2025-10-19 10:00:00',
                    'url' => 'https://example.com/test-article',
                ];
            }
        };

        // Mock du PressOrchestrator
        $mockOrchestrator = Mockery::mock(PressOrchestrator::class);
        $mockOrchestrator->shouldReceive('fetchAllFrontpages')
            ->once()
            ->andReturn([
                'lemonde' => [
                    'articles' => [$articleMock]
                ]
            ]);

        // Remplace le binding dans le conteneur Laravel
        $this->app->instance(PressOrchestrator::class, $mockOrchestrator);

        // Exécute la commande
        $this->artisan('press:fetch')
            ->expectsOutput('Début de la récupération des articles...')
            ->assertExitCode(0);

        // Vérifie la source en base
        $this->assertDatabaseHas('sources', [
            'name' => 'Le Monde',
            'slug' => 'le-monde',
        ]);

        // Vérifie l'article en base
        $this->assertDatabaseHas('articles', [
            'title' => 'Test Article',
            'author' => 'John Doe',
            'category' => 'Economie',
            'url' => 'https://example.com/test-article',
        ]);

        //Vérifie que l'article est bien lié à la source
        $article = Article::where('title', 'Test Article')->first();
        $this->assertEquals('Le Monde', $article->source->name);
    }
}
