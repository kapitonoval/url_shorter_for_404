<?php

namespace App\Services\Links\Contracts;

use Illuminate\Http\Request;

interface LinkThrottleRequestInterface
{
    public function setParam(string $value);

    public function setBlockTime(int $blockTime);

    public function saveError(): void;

    public function isBlocked(): bool;

    public function initParam(Request $request);
}
