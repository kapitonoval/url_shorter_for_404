<?php

namespace Tests\Unit;

use App\Services\Links\HashShuffle;
use PHPUnit\Framework\TestCase;

class CheckShuffleTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testMakeCheckShuffleStringLength()
    {
        $HashShuffle = new HashShuffle();

        foreach ([5,12,15] as $length){
            $this->assertTrue(strlen($HashShuffle->setLength($length)->generate()) == $length);
        }
    }
}
