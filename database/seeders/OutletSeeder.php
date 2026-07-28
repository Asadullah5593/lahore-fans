<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Store;
use Illuminate\Database\Seeder;

class OutletSeeder extends Seeder
{
    /**
     * Seed the cities and a starter set of outlets.
     *
     * The city list + coordinates are the standard Pakistan metro set (used for
     * map-focus and grouping). The store rows below are DEMO/PLACEHOLDER data
     * carried over so the page works on day one — replace them with Lahore Fan's
     * real dealer list via the admin panel.
     */
    public function run(): void
    {
        // name => [lat, lng, zoom], ordered south -> north. sort_order follows array order.
        $cities = [
            'Karachi'    => [24.8607, 67.0011, 12],
            'Hyderabad'  => [25.3960, 68.3578, 12],
            'Quetta'     => [30.1798, 66.9750, 12],
            'Multan'     => [30.1575, 71.5249, 12],
            'Sahiwal'    => [30.6705, 73.1066, 12],
            'Faisalabad' => [31.4504, 73.1350, 12],
            'Lahore'     => [31.5204, 74.3587, 12],
            'Gujranwala' => [32.1617, 74.1883, 12],
            'Sialkot'    => [32.4945, 74.5229, 12],
            'Peshawar'   => [34.0151, 71.5249, 12],
            'Mardan'     => [34.1958, 72.0400, 12],
            'Rawalpindi' => [33.5651, 73.0169, 12],
            'Islamabad'  => [33.6844, 73.0479, 12],
        ];

        $cityIds = [];
        $sort = 0;
        foreach ($cities as $name => [$lat, $lng, $zoom]) {
            $city = City::updateOrCreate(
                ['name' => $name],
                ['lat' => $lat, 'lng' => $lng, 'zoom' => $zoom, 'sort_order' => $sort++]
            );
            $cityIds[$name] = $city->id;
        }

        // Demo outlets — [city, name, address, phone, lat, lng, google_maps_url]
        $stores = [
            ['Karachi', 'FAUCET EMPORIUM', '37C, Street 11, Lane 4, Bukhari Commercial Area Karachi', '0337 7556660', 24.792461, 67.064634, 'https://g.co/kgs/wkSvkdy'],
            ['Multan', 'SHAFIQUE ENTERPRISES', 'Dera adda Tipu Sultan Colony, Multan', '0307 7807578', 30.189642, 71.453955, 'https://g.co/kgs/KkR4HPm'],
            ['Faisalabad', 'MARHABA TILES', 'Susan Road, Madina Town, Faisalabad', '0331 1111062', 31.416474, 73.111692, 'https://g.co/kgs/KkR4HPm'],
            ['Lahore', 'QURESHI SONS', '119, New Galaxy Cinema, Lahore – Kasur Rd, Fazlia Colony, Lahore', '(042) 37429744', 31.527625, 74.323116, 'https://g.co/kgs/uVmAqcJ'],
            ['Lahore', 'NAEEM TRADING COMPANY', 'Bedian Rd, near Askari 11, Askari XI, Lahore', '(042) 37162971', 31.464109, 74.432774, 'https://g.co/kgs/rJjaeBK'],
            ['Peshawar', 'YASEEN TRADERS', 'Karkhano Market Peshawar, Peshawar', '0313 7030295', 33.998499, 71.435127, 'https://g.co/kgs/yVMMGFu'],
            ['Gujranwala', 'SHAFIQUE SONS', 'N5, GT Rd, opposite Punjab College of Commerce, Gujranwala', '0333 1154001', 32.131451, 74.195123, 'https://g.co/kgs/bFuuDEs'],
        ];

        $sort = 0;
        foreach ($stores as [$city, $name, $address, $phone, $lat, $lng, $url]) {
            Store::updateOrCreate(
                ['name' => $name, 'city_id' => $cityIds[$city]],
                [
                    'address' => $address,
                    'phone' => $phone,
                    'lat' => $lat,
                    'lng' => $lng,
                    'google_maps_url' => $url,
                    'is_active' => true,
                    'sort_order' => $sort++,
                ]
            );
        }
    }
}
