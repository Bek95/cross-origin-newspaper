<?php

namespace App\Domain\Press\Services\SourceServices;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class LequipeService implements SourceServiceInterface
{
    const URI = '/lequipe';

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

        return $response->json();
    }

}
