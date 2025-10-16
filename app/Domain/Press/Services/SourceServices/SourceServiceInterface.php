<?php

namespace App\Domain\Press\Services\SourceServices;


interface SourceServiceInterface
{
/**
* get front-page articles from sources
*
* @return array
*/
public function fetchFrontpage(): array;

}
