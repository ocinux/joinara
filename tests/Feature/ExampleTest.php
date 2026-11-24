<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_application_serves_public_landing_page(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('Undangan digital', false);
    }

    public function test_admin_alias_redirects_to_dashboard(): void
    {
        $this->get('/admin')->assertRedirect('/dashboard');
    }
}
