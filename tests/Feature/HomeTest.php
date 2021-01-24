<?php

namespace Tests\Feature;

use App\Models\User;
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

    public function testSeesExpectedLinks(): void
    {
        $response = $this->get(route('home'));

        $response->assertStatus(200);
        $response->assertSeeText(__('menu.login'));
        $response->assertSeeText(__('menu.register'));
        $response->assertDontSee(__('menu.logout'));

        $user = User::factory()->create();
        $response = $this->actingAs($user)->get('/');

        $response->assertStatus(200);
        $response->assertDontSee(__('menu.login'));
        $response->assertDontSee(__('menu.register'));
        $response->assertSeeText(__('menu.logout'));
    }
}
