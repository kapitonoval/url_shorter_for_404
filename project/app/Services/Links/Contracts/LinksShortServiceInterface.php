<?php

namespace App\Services\Links\Contracts;

interface LinksShortServiceInterface
{
    public function shortHash(string $url): string;
}
