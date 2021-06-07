<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomeTest extends TestCase
{
    use RefreshDatabase;

    public function testHomeExists(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
    }
}
