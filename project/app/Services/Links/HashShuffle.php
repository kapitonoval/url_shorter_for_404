<?php

namespace App\Services\Links;

use App\Services\Links\Contracts\HashShuffleInterface;

/**
 * Class HashShuffle
 * @package App\Services\Links
 */
class HashShuffle implements HashShuffleInterface
{
    const POSSIBLE = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';

    /**
     * @var int
     */
    protected $length = 15;

    /**
     * @param int $length
     * @return $this
     */
    public function setLength(int $length)
    {
        $this->length = $length;
        return $this;
    }

    /**
     * Generate a random string.
     * @return string
     */
    public function generate(): string
    {
        $characters = str_repeat(self::POSSIBLE, $this->length);
        return substr(str_shuffle($characters), 0, $this->length);
    }
}
