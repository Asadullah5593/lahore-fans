<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private const STRONG_PASSWORD = 'Str0ng!Passw0rd#2026';

    public function test_users_module_requires_authentication(): void
    {
        $this->get('/admin/users')->assertRedirect('/login');
    }

    public function test_admin_screens_render(): void
    {
        $admin = User::factory()->create(['name' => 'Ada Admin']);

        $this->actingAs($admin)->get('/admin/users')->assertOk()->assertSee('Ada Admin');
        $this->actingAs($admin)->get('/admin/users/create')->assertOk()->assertSee('Add user');
        $this->actingAs($admin)->get(route('admin.users.edit', $admin))->assertOk()->assertSee($admin->email);

        // The city and outlet forms had fields commented out — make sure the
        // Blade still compiles and the hidden inputs are really gone.
        $this->actingAs($admin)->get('/admin/cities/create')
            ->assertOk()
            ->assertDontSee('name="lat"', false)
            ->assertDontSee('name="zoom"', false)
            ->assertDontSee('name="sort_order"', false);

        $this->actingAs($admin)->get('/admin/stores/create')
            ->assertOk()
            ->assertSee('name="lat"', false)          // outlet coordinates stay
            ->assertDontSee('name="sort_order"', false);
    }

    public function test_admin_can_create_a_user(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Second Admin',
            'email' => 'second@lahorefan.com',
            'password' => self::STRONG_PASSWORD,
            'password_confirmation' => self::STRONG_PASSWORD,
        ])->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', ['email' => 'second@lahorefan.com']);

        $created = User::firstWhere('email', 'second@lahorefan.com');
        $this->assertTrue(Hash::check(self::STRONG_PASSWORD, $created->password));
    }

    public function test_weak_passwords_are_rejected(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->post('/admin/users', [
            'name' => 'Weak',
            'email' => 'weak@lahorefan.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertSessionHasErrors('password');

        $this->assertDatabaseMissing('users', ['email' => 'weak@lahorefan.com']);
    }

    public function test_editing_without_a_password_keeps_the_current_one(): void
    {
        $admin = User::factory()->create();
        $target = User::factory()->create(['password' => 'OriginalP4ss!word']);

        $this->actingAs($admin)->put(route('admin.users.update', $target), [
            'name' => 'Renamed',
            'email' => $target->email,
            'password' => '',
            'password_confirmation' => '',
        ])->assertRedirect(route('admin.users.index'));

        $target->refresh();
        $this->assertSame('Renamed', $target->name);
        $this->assertTrue(Hash::check('OriginalP4ss!word', $target->password));
    }

    public function test_a_user_cannot_delete_their_own_account(): void
    {
        $admin = User::factory()->create();
        User::factory()->create(); // a second account, so the "last user" guard is not what blocks it

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $admin))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', ['id' => $admin->id]);
    }

    public function test_admin_can_delete_another_user(): void
    {
        $admin = User::factory()->create();
        $target = User::factory()->create();

        $this->actingAs($admin)
            ->delete(route('admin.users.destroy', $target))
            ->assertSessionHas('success');

        $this->assertDatabaseMissing('users', ['id' => $target->id]);
    }

    public function test_login_page_does_not_offer_a_password_reset_link(): void
    {
        $this->get('/login')
            ->assertOk()
            ->assertDontSee('Forgot your password?');
    }
}
