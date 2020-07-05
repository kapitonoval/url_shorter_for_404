<?php

namespace App\Services\Links\Contracts;

interface HashShuffleInterface
{
    public function setLength(int $url);
    public function generate(): string;
}
