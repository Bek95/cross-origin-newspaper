<?php

namespace App\Domain\Press\Services\SourceServices;

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class LequipeService implements SourceServiceInterface
{
    const URI = '/lequipe';

    public function fetchFrontpage(): array
    {
        $formattedDate = Carbon::now('+07:00')->toIso8601String();

        $response = Http::get(config('app.api_source_base_url') . self::URI, [
            'token' => config('app.lequipe_api_token'),
            'date' => $formattedDate,

        ]);

        if ($response->failed()) {
            return [];
        }

        return $response->json();
    }

}
