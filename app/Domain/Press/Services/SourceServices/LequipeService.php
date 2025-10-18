<?php

namespace App\Domain\Press\Services\SourceServices;

use App\Domain\Press\DTO\ArticleData;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class LequipeService implements SourceServiceInterface
{
    const URI = '/lequipe';
    const NAME = 'Lequipe';

    public function fetchFrontpage(?array $options = null): array
    {
        $date = isset($options['date'])
            ? Carbon::parse($options['date'])
            : Carbon::now('+07:00');

        $formattedDate = $date->toIso8601String();

        $response = Http::get(config('app.api_source_base_url') . self::URI, [
            'token' => config('app.lequipe_api_token'),
            'date' => $formattedDate,

        ]);

        if ($response->failed()) {
            \Log::error('API LEQUIPE failed', [
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
            category: $a['category']['name'] ?? null,
            publishedAt: Carbon::parse($a['created_at'] ?? now()),
            keywords: $a['keywords'] ?? [],
            authors: [implode(' ', $a['authors'] ?? [])],
            url: config('app.api_source_base_url') . self::URI,
        ), $rawArticles);
    }

    public function getSourceName(): string
    {
        return self::NAME;
    }

}
