<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lat',
        'lng',
        'zoom',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'lat' => 'float',
            'lng' => 'float',
            'zoom' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    /**
     * The biggest markets always head the public list, in this order. Everything
     * else follows behind them.
     */
    public const MAJOR_CITIES = ['Lahore', 'Karachi', 'Faisalabad', 'Rawalpindi', 'Islamabad'];

    /**
     * Major cities first (in MAJOR_CITIES order), then everything else by its
     * configured sort order and finally alphabetically.
     *
     * The ranking is computed in SQL rather than stored in sort_order, so a city
     * added later can never accidentally outrank Lahore by defaulting to 0.
     */
    public function scopeOrdered(Builder $query): Builder
    {
        $case = 'CASE';
        $bindings = [];

        foreach (array_values(self::MAJOR_CITIES) as $i => $name) {
            $case .= ' WHEN name = ? THEN '.($i + 1);
            $bindings[] = $name;
        }

        $case .= ' ELSE '.(count(self::MAJOR_CITIES) + 1).' END';

        return $query->orderByRaw($case, $bindings)
            ->orderBy('sort_order')
            ->orderBy('name');
    }
}
