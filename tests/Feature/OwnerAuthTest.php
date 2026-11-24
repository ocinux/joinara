<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OwnerAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_visiting_dashboard_is_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect('/login');
        $this->get('/')->assertRedirect('/dashboard');
        $this->get('/', ['Accept' => 'text/html']);
    }

    public function test_owner_can_login_with_valid_credentials(): void
    {
        $owner = User::factory()->create([
            'email' => 'owner@joinara.test',
            'password' => bcrypt('secret-password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'owner@joinara.test',
            'password' => 'secret-password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($owner);
    }

    public function test_owner_login_with_invalid_credentials_fails(): void
    {
        User::factory()->create([
            'email' => 'owner@joinara.test',
            'password' => bcrypt('secret-password'),
        ]);

        $response = $this->from('/login')->post('/login', [
            'email' => 'owner@joinara.test',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_owner_can_logout(): void
    {
        $owner = User::factory()->create();

        $this->actingAs($owner)->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_authenticated_owner_sees_dashboard(): void
    {
        $owner = User::factory()->create();

        $this->actingAs($owner)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Joinara');
    }
}
