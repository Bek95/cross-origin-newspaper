<?php

namespace App\Domain\Press\Services\SourceServices;

use Carbon\Carbon;

interface SourceServiceInterface
{
/**
* get front-page articles from sources
*
* @return array
*/
public function fetchFrontpage(string $date): array;

}
