<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_redirects_authenticated_root_to_dashboard(): void
    {
        $response = $this->actingAs(User::factory()->create())->get('/');

        $response->assertRedirect('/dashboard');
    }
}
