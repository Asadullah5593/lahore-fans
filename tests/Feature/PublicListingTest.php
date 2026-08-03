<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * How the where-to-buy list is ordered and presented: major cities first,
 * starred dealers at the head of their city, and names in caps.
 */
class PublicListingTest extends TestCase
{
    use RefreshDatabase;

    private function dealer(City $city, string $name, array $attrs = []): Store
    {
        return Store::create(array_merge([
            'city_id' => $city->id,
            'name' => $name,
            'address' => 'Test Road',
            'phone' => '0300 0000000',
            'lat' => 31.5,
            'lng' => 74.3,
            'is_active' => true,
        ], $attrs));
    }

    /** City headings in the order they appear on the page. */
    private function cityOrder(string $html): array
    {
        preg_match_all('/<h2 class="city-header[^>]*>.*?<\/span>\s*(.*?)\s*<\/h2>/s', $html, $m);

        return array_map('trim', $m[1]);
    }

    public function test_major_cities_lead_the_list_in_the_configured_order(): void
    {
        // Created deliberately out of order, and alphabetically unhelpful.
        foreach (['Abbottabad', 'Islamabad', 'Zhob', 'Karachi', 'Rawalpindi', 'Faisalabad', 'Lahore'] as $name) {
            $this->dealer(City::create(['name' => $name]), "Dealer {$name}");
        }

        $order = $this->cityOrder($this->get('/')->assertOk()->getContent());

        $this->assertSame(
            ['Lahore', 'Karachi', 'Faisalabad', 'Rawalpindi', 'Islamabad'],
            array_slice($order, 0, 5)
        );
        // Everything else follows, alphabetically.
        $this->assertSame(['Abbottabad', 'Zhob'], array_slice($order, 5));
    }

    public function test_a_city_added_later_cannot_outrank_the_major_cities(): void
    {
        // sort_order defaults to 0, which would sort first if ranking were
        // stored in that column instead of computed.
        $this->dealer(City::create(['name' => 'Lahore']), 'Lahore Dealer');
        $this->dealer(City::create(['name' => 'Aaa Newtown']), 'New Dealer');

        $order = $this->cityOrder($this->get('/')->assertOk()->getContent());

        $this->assertSame('Lahore', $order[0]);
    }

    public function test_featured_dealers_lead_their_city_and_are_marked(): void
    {
        $lahore = City::create(['name' => 'Lahore']);
        $this->dealer($lahore, 'Aaa Ordinary Dealer');
        $this->dealer($lahore, 'Zzz Starred Dealer', ['is_featured' => true]);

        $html = $this->get('/')->assertOk()->getContent();

        // Starred first despite sorting last alphabetically.
        $this->assertLessThan(
            strpos($html, 'AAA ORDINARY DEALER'),
            strpos($html, 'ZZZ STARRED DEALER'),
            'A featured dealer should appear above the others in its city.'
        );

        $this->assertStringContainsString('store-card--featured', $html);
        $this->assertSame(1, substr_count($html, 'store-card--featured'));
    }

    public function test_dealer_and_owner_names_render_in_caps(): void
    {
        $this->dealer(City::create(['name' => 'Lahore']), 'Mixed Case Dealer', [
            'owner_name' => 'tauseef ahmad',
        ]);

        $this->get('/')->assertOk()
            ->assertSee('MIXED CASE DEALER')
            ->assertDontSee('>Mixed Case Dealer<', false)
            ->assertSee('TAUSEEF AHMAD')
            ->assertDontSee('tauseef ahmad');
    }

    public function test_the_maps_link_is_labelled_and_points_at_google(): void
    {
        $this->dealer(City::create(['name' => 'Lahore']), 'Linked Dealer', [
            'google_maps_url' => 'https://maps.app.goo.gl/example',
        ]);

        $this->get('/')->assertOk()
            ->assertSee('Open in Google Maps')
            ->assertSee('https://maps.app.goo.gl/example', false)
            ->assertDontSee('See more');
    }

    public function test_an_admin_can_star_and_unstar_from_the_list(): void
    {
        $admin = User::factory()->create();
        $store = $this->dealer(City::create(['name' => 'Lahore']), 'Toggle Me');

        $this->actingAs($admin)->post(route('admin.stores.feature', $store))->assertRedirect();
        $this->assertTrue($store->fresh()->is_featured);

        $this->actingAs($admin)->post(route('admin.stores.feature', $store))->assertRedirect();
        $this->assertFalse($store->fresh()->is_featured);
    }

    public function test_starring_refreshes_the_public_page(): void
    {
        $admin = User::factory()->create();
        $store = $this->dealer(City::create(['name' => 'Lahore']), 'Cache Test Dealer');

        // Prime the cache with the unstarred version.
        $this->get('/')->assertDontSee('store-card--featured');

        $this->actingAs($admin)->post(route('admin.stores.feature', $store));

        $this->get('/')->assertSee('store-card--featured');
    }
}
