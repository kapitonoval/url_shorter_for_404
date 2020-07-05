<?php

namespace App\Services\Links;

use Illuminate\Support\Facades\Redis;
use \App\Services\Links\Contracts\LinkThrottleRequestInterface;

class LinkThrottleRequest implements LinkThrottleRequestInterface
{
    const PREPEND_KEY = "REQUEST_SHORT_URL_404_";
    const MAX_BAD_REQUEST = 10;

    private $blockTime = 60; // in seconds
    private $param = [];

    /**
     * @param string $value
     * @return $this
     */
    public function setParam(string $value)
    {
        $this->param[] = $value;
        return $this;
    }

    public function __construct()
    {
    }

    /**
     * @param int $blockTime
     * @return $this
     */
    public function setBlockTime(int $blockTime)
    {
        $this->blockTime = $blockTime;
        return $this;
    }

    public function saveError(): void
    {
        $key = $this->getKey();

        if ($counter = Redis::get($key)) {
            $counter++;
        } else {
            $counter = 1;
        }
        Redis::set($key, $counter, 'EX', $this->blockTime);
    }

    /**
     * @return bool
     */
    public function isBlocked(): bool
    {
        if ($counter = Redis::get($this->getKey())) {
            if ($counter >= self::MAX_BAD_REQUEST) {
                return true;
            }
        }
        return false;
    }

    /**
     * @return string
     */
    private function getKey(): string
    {
        return self::PREPEND_KEY . md5(implode('', $this->param));
    }

}
