<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class LinkAddTest extends TestCase
{
    public function testError()
    {
        $this->withoutMiddleware();

        $res = $this->post('/api/add', ['url' => 'Sally']);

        $res->assertStatus(401);
    }

    public function testSuccess()
    {
        $this->withoutMiddleware();
        $res = $this->post('/api/add', ['url' => 'https://mail.google.com/']);
        $res->assertStatus(200);
    }
}
