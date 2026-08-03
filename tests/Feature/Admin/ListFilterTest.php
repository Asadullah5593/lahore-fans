<?php

namespace Tests\Feature\Admin;

use App\Models\City;
use App\Models\Store;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ListFilterTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private City $lahore;

    private City $karachi;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create();
        $this->lahore = City::create(['name' => 'Lahore']);
        $this->karachi = City::create(['name' => 'Karachi']);

        $this->dealer($this->lahore, 'Alpha Electric', ['owner_name' => 'Mr Sajjad', 'phone' => '0300-1112223']);
        $this->dealer($this->lahore, 'Beta Lights', ['is_active' => false]);
        $this->dealer($this->karachi, 'Gamma Traders', ['is_featured' => true, 'phone' => '0321-9998887']);
    }

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

    private function outlets(array $query = [])
    {
        return $this->actingAs($this->admin)->get(route('admin.stores.index', $query))->assertOk();
    }

    public function test_search_matches_name_owner_and_phone(): void
    {
        $this->outlets(['q' => 'Alpha'])->assertSee('Alpha Electric')->assertDontSee('Gamma Traders');
        $this->outlets(['q' => 'Sajjad'])->assertSee('Alpha Electric')->assertDontSee('Gamma Traders');
        $this->outlets(['q' => '9998887'])->assertSee('Gamma Traders')->assertDontSee('Alpha Electric');
    }

    public function test_filters_by_city_status_and_featured(): void
    {
        $this->outlets(['city' => $this->karachi->id])
            ->assertSee('Gamma Traders')->assertDontSee('Alpha Electric');

        $this->outlets(['status' => 'hidden'])
            ->assertSee('Beta Lights')->assertDontSee('Alpha Electric');

        $this->outlets(['featured' => 'yes'])
            ->assertSee('Gamma Traders')->assertDontSee('Beta Lights');

        $this->outlets(['featured' => 'no'])
            ->assertSee('Alpha Electric')->assertDontSee('Gamma Traders');
    }

    public function test_filters_combine(): void
    {
        $this->outlets(['city' => $this->lahore->id, 'status' => 'active'])
            ->assertSee('Alpha Electric')
            ->assertDontSee('Beta Lights')
            ->assertDontSee('Gamma Traders');
    }

    public function test_an_unmatched_filter_explains_itself(): void
    {
        $this->outlets(['q' => 'no-such-dealer'])
            ->assertSee('No outlets match those filters')
            ->assertDontSee('No outlets yet');
    }

    public function test_a_wildcard_in_the_search_box_is_treated_literally(): void
    {
        // An unescaped % would match every row.
        $this->outlets(['q' => '%'])->assertSee('No outlets match those filters');
    }

    public function test_filters_survive_pagination_links(): void
    {
        for ($i = 0; $i < 25; $i++) {
            $this->dealer($this->lahore, "Bulk Dealer {$i}");
        }

        $html = $this->outlets(['city' => $this->lahore->id])->getContent();

        $this->assertStringContainsString('city='.$this->lahore->id, $html,
            'Pagination links must carry the active filters.');
    }

    public function test_cities_list_can_be_searched(): void
    {
        // "Lahore" also appears in the page title, so assert on the row's own
        // edit link rather than the bare word.
        $this->actingAs($this->admin)->get(route('admin.cities.index', ['q' => 'Kara']))
            ->assertOk()
            ->assertSee('Karachi')
            ->assertSee(route('admin.cities.edit', $this->karachi), false)
            ->assertDontSee(route('admin.cities.edit', $this->lahore), false);
    }
}
