<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WhereToBuyTest extends TestCase
{
    use RefreshDatabase;

    private function city(array $attrs = []): City
    {
        return City::create(array_merge([
            'name' => 'Lahore',
            'lat' => 31.5204,
            'lng' => 74.3587,
            'zoom' => 12,
            'sort_order' => 0,
        ], $attrs));
    }

    private function store(City $city, array $attrs = []): Store
    {
        return Store::create(array_merge([
            'city_id' => $city->id,
            'name' => 'Test Dealer',
            'address' => '123 Test Road',
            'phone' => '0300 0000000',
            'lat' => 31.5,
            'lng' => 74.3,
            'is_active' => true,
            'sort_order' => 0,
        ], $attrs));
    }

    public function test_public_page_shows_active_stores_grouped_by_city(): void
    {
        $lahore = $this->city(['name' => 'Lahore', 'sort_order' => 0]);
        $this->store($lahore, ['name' => 'Active Dealer']);

        $this->get('/')
            ->assertOk()
            ->assertSee('Lahore')
            ->assertSee('Active Dealer');
    }

    public function test_public_page_hides_inactive_stores_and_empty_cities(): void
    {
        $lahore = $this->city(['name' => 'Lahore']);
        $this->store($lahore, ['name' => 'Hidden Dealer', 'is_active' => false]);
        $this->city(['name' => 'Karachi', 'lat' => 24.86, 'lng' => 67.0, 'sort_order' => 1]); // no stores

        $response = $this->get('/')->assertOk();
        $response->assertDontSee('Hidden Dealer');
        $response->assertDontSee('KARACHI'); // city header only shows for cities with active stores
    }

    public function test_where_to_buy_path_redirects_to_root(): void
    {
        $this->get('/where-to-buy')->assertRedirect('/');
    }

    public function test_admin_requires_authentication(): void
    {
        $this->get('/admin/stores')->assertRedirect('/login');
        $this->get('/admin/cities')->assertRedirect('/login');
    }

    public function test_registration_is_disabled(): void
    {
        $this->get('/register')->assertNotFound();
    }

    public function test_admin_can_create_store_and_public_cache_refreshes(): void
    {
        $admin = User::factory()->create();
        $lahore = $this->city(['name' => 'Lahore']);

        // Prime the public page (empty).
        $this->get('/')->assertDontSee('Brand New Dealer');

        $this->actingAs($admin)->post('/admin/stores', [
            'city_id' => $lahore->id,
            'name' => 'Brand New Dealer',
            'address' => 'New Road',
            'phone' => '0311 1111111',
            'lat' => '31.51',
            'lng' => '74.34',
            'google_maps_url' => 'https://g.co/kgs/example',
            'is_active' => '1',
            'sort_order' => '0',
        ])->assertRedirect(route('admin.stores.index'));

        $this->assertDatabaseHas('stores', ['name' => 'Brand New Dealer']);
        // Observer should have flushed the cache — new dealer appears immediately.
        $this->get('/')->assertSee('Brand New Dealer');
    }

    public function test_store_validation_rejects_out_of_range_coordinates(): void
    {
        $admin = User::factory()->create();
        $lahore = $this->city();

        $this->actingAs($admin)->post('/admin/stores', [
            'city_id' => $lahore->id,
            'name' => 'Bad Coords',
            'address' => 'x',
            'phone' => 'x',
            'lat' => '999',
            'lng' => '74',
            'is_active' => '1',
        ])->assertSessionHasErrors('lat');

        $this->assertDatabaseMissing('stores', ['name' => 'Bad Coords']);
    }

    public function test_city_with_stores_cannot_be_deleted(): void
    {
        $admin = User::factory()->create();
        $lahore = $this->city();
        $this->store($lahore);

        $this->actingAs($admin)
            ->delete(route('admin.cities.destroy', $lahore))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('cities', ['id' => $lahore->id]);
    }
}
