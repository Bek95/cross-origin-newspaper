<?php

namespace App\Domain\Press\Services\SourceServices;

use App\Domain\Press\DTO\ArticleData;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class LeMondeService implements SourceServiceInterface
{
    const URI = '/lemonde';
    const NAME = 'Lemonde';

    public function fetchFrontpage(?array $options = null): array
    {
        $date = $options['date'] ?? now()->format('Y-m-d');


        $carbonDate = Carbon::parse($date);
        $yesterday = $carbonDate->format('Y-m-d');

        $response = Http::get(config('app.api_source_base_url') . self::URI, [
            'date' => $yesterday,
        ]);

        if ($response->failed()) {
            \Log::error('API Le Monde failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return [];
        }


        $rawArticles = $response->json()['data'] ?? [];


        return array_map(fn($a) => new ArticleData(
            id: $a['id'],
            title: $a['title'] ?? 'Sans titre',
            content: $a['content'] ?? '',
            source: $this->getSourceName(),
            category: $a['category'] ?? null,
            publishedAt: Carbon::parse($a['publish_date'] ?? now()),
            keywords: $a['keywords'] ?? [],
            authors: [$a['author']]
        ), $rawArticles);
    }

    public function getSourceName(): string
    {
        return self::NAME;
    }

}
