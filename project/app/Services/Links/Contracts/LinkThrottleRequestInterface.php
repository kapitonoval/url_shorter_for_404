<?php

namespace App\Services\Links\Contracts;

interface LinkThrottleRequestInterface
{
    public function setParam(string $value);

    public function setBlockTime(int $blockTime);

    public function saveError(): void;

    public function isBlocked(): bool;
}
