<?php

namespace App\Http\Filters\InventoryFilters;

use Illuminate\Database\Eloquent\Builder;
use  App\Http\Filters\Filter;

class PriceFilter implements Filter
{
    /**
     * apply
     *
     * @param  Builder $builder
     * @param  string $value
     * @return Buillder for this specific search
     */
    public function apply(Builder $builder, $value)
    {
        return $builder->where('price', $value);
    }
}
