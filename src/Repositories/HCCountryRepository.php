<?php

declare(strict_types = 1);

namespace HoneyComb\Regions\Repositories;

use HoneyComb\Regions\Http\Requests\Admin\HCCountryRequest;
use HoneyComb\Regions\Models\HCCountry;
use HoneyComb\Core\Repositories\Traits\HCQueryBuilderTrait;
use HoneyComb\Starter\Repositories\HCBaseRepository;
use Illuminate\Support\Collection;

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
     * @param \HoneyComb\Regions\Http\Requests\Admin\HCCountryRequest $request
     * @return \Illuminate\Support\Collection
     */
    public function getOptions (HCCountryRequest $request): Collection
    {
        return optimizeTranslationOptions($this->createBuilderQuery($request)->where('visible', '1')->get());
    }
}