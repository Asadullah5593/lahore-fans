<?php

namespace Tests\Feature\Admin;

use App\Models\City;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Sort order, city centre coordinates and map zoom were removed from the admin
 * forms. These tests pin down what happens now that the browser no longer sends
 * those fields.
 */
class HiddenFieldDefaultsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_city_can_be_created_with_only_a_name(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)
            ->post('/admin/cities', ['name' => 'Sargodha'])
            ->assertRedirect(route('admin.cities.index'));

        $city = City::firstWhere('name', 'Sargodha');

        $this->assertNotNull($city);
        $this->assertNull($city->lat);
        $this->assertNull($city->lng);
        $this->assertSame(12, $city->zoom);      // database default
        $this->assertSame(0, $city->sort_order); // database default
    }

    public function test_editing_a_city_name_keeps_its_existing_centre_and_order(): void
    {
        $admin = User::factory()->create();
        $city = City::create([
            'name' => 'Lahore',
            'lat' => 31.5204,
            'lng' => 74.3587,
            'zoom' => 14,
            'sort_order' => 3,
        ]);

        $this->actingAs($admin)
            ->put(route('admin.cities.update', $city), ['name' => 'Lahore City'])
            ->assertRedirect(route('admin.cities.index'));

        $city->refresh();

        $this->assertSame('Lahore City', $city->name);
        $this->assertSame(31.5204, $city->lat);
        $this->assertSame(74.3587, $city->lng);
        $this->assertSame(14, $city->zoom);
        $this->assertSame(3, $city->sort_order);
    }

    public function test_editing_an_outlet_keeps_its_existing_sort_order(): void
    {
        $admin = User::factory()->create();
        $city = City::create(['name' => 'Multan']);
        $store = Store::create([
            'city_id' => $city->id,
            'name' => 'Old Name',
            'address' => '1 Test Road',
            'phone' => '0300 0000000',
            'lat' => 30.15,
            'lng' => 71.52,
            'is_active' => true,
            'sort_order' => 7,
        ]);

        // Exactly what the form now posts — no sort_order field at all.
        $this->actingAs($admin)->put(route('admin.stores.update', $store), [
            'city_id' => $city->id,
            'name' => 'New Name',
            'address' => '1 Test Road',
            'phone' => '0300 0000000',
            'lat' => '30.15',
            'lng' => '71.52',
            'is_active' => '1',
        ])->assertRedirect(route('admin.stores.index'));

        $store->refresh();

        $this->assertSame('New Name', $store->name);
        $this->assertSame(7, $store->sort_order, 'sort_order must survive an edit that does not post it');
    }

    public function test_public_payload_derives_a_city_centre_from_its_outlets(): void
    {
        $city = City::create(['name' => 'Faisalabad']); // no centre supplied

        foreach ([[31.40, 73.10], [31.50, 73.20]] as [$lat, $lng]) {
            Store::create([
                'city_id' => $city->id,
                'name' => "Dealer {$lat}",
                'address' => 'Test Road',
                'phone' => '0300 0000000',
                'lat' => $lat,
                'lng' => $lng,
                'is_active' => true,
            ]);
        }

        $response = $this->get('/')->assertOk();

        // The map script reads this JSON block; the centre is the midpoint of
        // the two outlets above.
        $payload = $this->extractPayload($response->getContent());

        $this->assertSame('Faisalabad', $payload[0]['name']);
        $this->assertEqualsWithDelta(31.45, $payload[0]['lat'], 0.0001);
        $this->assertEqualsWithDelta(73.15, $payload[0]['lng'], 0.0001);
    }

    public function test_a_stored_city_centre_still_wins_over_the_derived_one(): void
    {
        $city = City::create(['name' => 'Karachi', 'lat' => 24.8607, 'lng' => 67.0011]);

        Store::create([
            'city_id' => $city->id,
            'name' => 'Dealer',
            'address' => 'Test Road',
            'phone' => '0300 0000000',
            'lat' => 24.90,
            'lng' => 67.10,
            'is_active' => true,
        ]);

        $payload = $this->extractPayload($this->get('/')->assertOk()->getContent());

        $this->assertEqualsWithDelta(24.8607, $payload[0]['lat'], 0.0001);
        $this->assertEqualsWithDelta(67.0011, $payload[0]['lng'], 0.0001);
    }

    /** Pull the JSON the public page hands to the map script. */
    private function extractPayload(string $html): array
    {
        preg_match('/<script type="application\/json" id="wtb-data">(.*?)<\/script>/s', $html, $m);

        $this->assertNotEmpty($m, 'The wtb-data JSON block was not found on the page.');

        return json_decode(html_entity_decode($m[1], ENT_QUOTES), true);
    }
}
