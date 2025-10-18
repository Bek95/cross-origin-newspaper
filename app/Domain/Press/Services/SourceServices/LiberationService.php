<?php

namespace App\Domain\Press\Services\SourceServices;

use App\Domain\Press\DTO\ArticleData;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class LiberationService implements SourceServiceInterface
{
    const URI = '/liberation';
    const NAME = 'liberation';

    public function fetchFrontpage(?array $options = null): array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return [];
        }

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->get(config('app.api_source_base_url') . self::URI, [
            'page'   => $options['page'] ?? 1,
            'min_id' => $options['min_id'] ?? 3,
            'sort'   => $options['sort'] ?? 'id,asc',
        ]);

        if ($response->failed()) {
            \Log::error('API Liberation failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
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
            publishedAt: Carbon::parse($a['published_at'] ?? now()),
            keywords: $a['keywords'] ?? [],
            authors: [$a['author'] ?? []],
        ), $rawArticles);
    }

    private function getAccessToken(): ?string
    {
        $response = Http::asForm()->post(config('app.api_source_base_url') . '/oauth/token', [
            'client_id'     => config('app.liberation_client_id'),
            'client_secret' => config('app.liberation_client_secret'),
            'grant_type'    => 'client_credentials',
        ]);

        if ($response->failed()) {
            \Log::error('Liberation OAuth token request failed', [
                'status' => $response->status(),
                'body'   => $response->body(),
            ]);
            return null;
        }

        return $response->json('access_token');
    }

    public function getSourceName(): string
    {
        return self::NAME;
    }

}
