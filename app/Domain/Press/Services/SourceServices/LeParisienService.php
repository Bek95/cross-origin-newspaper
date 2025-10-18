<?php

namespace App\Domain\Press\Services\SourceServices;

use App\Domain\Press\DTO\ArticleData;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class LeParisienService implements SourceServiceInterface
{
    const URI = '/leparisien';
    const NAME = 'Leparisien';

    public function fetchFrontpage(?array $options = null): array
    {
        $timestamp = $options['publish_date_gte'] ?? Carbon::now()->timestamp;
//        $timestamp = 1730304142;

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'ApiToken ' . config('app.le_parisien_api_token'),
        ])->get(config('app.api_source_base_url') . self::URI, [
            'publish_date_gte' => $timestamp,
        ]);

        if ($response->failed()) {
            \Log::error('API Parisien failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return [];
        }

        $rawArticles = $response->json()['data'] ?? [];

        return array_map(fn($a) => new ArticleData(
            id: $a['id'],
            title: $a['headlines']['basic'] ?? 'Sans titre',
            content: $a['content'] ?? '',
            source: 'LeParisien',
            category: $a['keywords'][0] ?? null,
            publishedAt: Carbon::createFromTimestamp($a['publish_date'] ?? time()),
            keywords: $a['keywords'] ?? [],
            authors: collect($a['credits'] ?? [])
                ->filter(fn($c) => ($c['type'] ?? '') === 'author')
                ->pluck('name')
                ->all(),
        ), $rawArticles);


    }

    public function getSourceName(): string
    {
        return self::NAME;
    }
}
