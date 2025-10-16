<?php

namespace App\Domain\Press\Services\SourceServices;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class LeParisienService implements SourceServiceInterface
{
    const URI = '/leparisien';

    public function fetchFrontpage(): array
    {
        // Conversion de la date en timestamp Unix
        $timestamp = Carbon::parse(now())->timestamp;

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'ApiToken ' . config('app.leparisien.api_token'),
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

        return $response->json();
    }
}
