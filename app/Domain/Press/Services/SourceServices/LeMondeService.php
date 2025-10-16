<?php

namespace App\Domain\Press\Services\SourceServices;

use Illuminate\Support\Facades\Http;

class LeMondeService implements SourceServiceInterface
{
    const URI = '/lemonde';

    public function fetchFrontpage(?array $options = null): array
    {
        $date = $options['date'] ?? now()->format('Y-m-d');

        $response = Http::get(config('app.api_source_base_url') . self::URI, [
            'date' => $date,
        ]);

        if ($response->failed()) {
            \Log::error('API Le Monde failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return [];
        }

        return $response->json();
    }

}
