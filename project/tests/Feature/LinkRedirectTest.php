<?php

namespace Tests\Feature;

use App\Models\Links;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use \Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class LinkRedirectTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testCheckRedirect()
    {
        $link = Links::firstOrFail();
        $response = $this->get('/'.$link->link_short_shuffle);
        $response->assertStatus(Response::HTTP_MOVED_PERMANENTLY);
    }
}
