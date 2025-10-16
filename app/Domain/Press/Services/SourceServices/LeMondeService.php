<?php

namespace App\Domain\Press\Services\SourceServices;

use Illuminate\Support\Facades\Http;

class LeMondeService implements SourceServiceInterface
{
    const URI = '/lemonde';

    public function fetchFrontpage(string $date): array
    {

        $response = Http::get(config('app.api_source_base_url') . self::URI, [
            'date' => $date,
        ]);

        if ($response->failed()) {
            return [];
        }

        return $response->json();
    }

}
