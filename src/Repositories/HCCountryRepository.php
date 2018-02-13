<?php

declare(strict_types = 1);

namespace HoneyComb\Regions\Repositories;

use HoneyComb\Regions\Http\Requests\HCCountryRequest;
use HoneyComb\Regions\Models\HCCountry;
use HoneyComb\Core\Repositories\Traits\HCQueryBuilderTrait;
use HoneyComb\Starter\Repositories\HCBaseRepository;
use Illuminate\Database\Eloquent\Collection;

class HCCountryRepository extends HCBaseRepository
{
    use HCQueryBuilderTrait;

    /**
     * @return string
     */
    public function model(): string
    {
        return HCCountry::class;
    }

    /**
     * @param \HoneyComb\Regions\Http\Requests\HCCountryRequest $request
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getOptions (HCCountryRequest $request): Collection
    {
        return $this->createBuilderQuery($request)->where('visible', '1')->get();
    }
}