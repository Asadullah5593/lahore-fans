<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Tests\TestCase;

/**
 * This is a closed admin panel. Nobody signs themselves up, nobody resets their
 * own password by email, and nobody edits or deletes their own account outside
 * of Admin → Users. These tests fail loudly if any of those routes come back.
 *
 * Replaces the Breeze ProfileTest and PasswordResetTest, whose features are gone.
 */
class ClosedRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_routes_are_closed(): void
    {
        $this->get('/register')->assertNotFound();
        $this->post('/register', [])->assertNotFound();
    }

    public function test_password_reset_routes_are_closed(): void
    {
        $this->get('/forgot-password')->assertNotFound();
        $this->post('/forgot-password', ['email' => 'admin@lahorefan.com'])->assertNotFound();
        $this->get('/reset-password/any-token')->assertNotFound();
        $this->post('/reset-password', [])->assertNotFound();
    }

    public function test_profile_routes_are_closed_even_when_signed_in(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/profile')->assertNotFound();
        $this->actingAs($user)->patch('/profile', ['name' => 'x'])->assertNotFound();

        // The important one: this form used to let the last remaining admin
        // delete themselves and lock everyone out of the panel.
        $this->actingAs($user)->delete('/profile', [])->assertNotFound();

        $this->assertDatabaseHas('users', ['id' => $user->id]);
    }

    public function test_self_service_password_endpoints_are_closed(): void
    {
        $user = User::factory()->create();

        // The standalone "change my password" endpoint and the password
        // confirmation gate only existed to serve the profile page.
        $this->actingAs($user)->get('/confirm-password')->assertNotFound();
        $this->actingAs($user)->post('/confirm-password', ['password' => 'x'])->assertNotFound();
        $this->actingAs($user)->put('/password', [])->assertNotFound();
    }

    public function test_the_named_routes_no_longer_resolve(): void
    {
        $closed = [
            'profile.edit', 'profile.update', 'profile.destroy',
            'password.request', 'password.email', 'password.reset', 'password.store',
            'password.update', 'password.confirm',
        ];

        foreach ($closed as $name) {
            $this->assertFalse(
                Route::has($name),
                "Route [{$name}] should no longer be registered."
            );
        }
    }

    public function test_signing_in_still_works(): void
    {
        $user = User::factory()->create(['password' => 'C0rrect!Horse#Batt3ry']);

        // Breeze lands on /dashboard, which forwards to the outlets screen.
        $this->post('/login', [
            'email' => $user->email,
            'password' => 'C0rrect!Horse#Batt3ry',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($user);

        $this->get('/dashboard')->assertRedirect(route('admin.stores.index'));
    }
}
