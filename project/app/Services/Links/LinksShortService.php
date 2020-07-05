<?php

namespace App\Services\Links;

use App\Models\Links;
use App\Services\Links\Contracts\LinksShortServiceInterface;
use App\Services\Links\Exceptions\NotUniqueHashGenerateException;

class LinksShortService implements LinksShortServiceInterface
{
    private $HashShuffle = null;

    public function __construct(HashShuffle $HashShuffle)
    {
        $this->HashShuffle = $HashShuffle;
    }

    /**
     * @param string $url
     * @return string
     * @throws NotUniqueHashGenerateException
     */
    public function shortHash(string $url): string
    {
        $hash = $this->HashShuffle->generate($url);
        if (is_null(Links::where('link_short_shuffle', $hash)->first())) {
            return $hash;
        }
        throw  new NotUniqueHashGenerateException($hash);
    }

}
