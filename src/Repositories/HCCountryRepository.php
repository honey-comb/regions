<?php

declare(strict_types = 1);

namespace HoneyComb\Regions\Repositories;

use HoneyComb\Regions\Models\HCCountry;
use HoneyComb\Core\Repositories\Traits\HCQueryBuilderTrait;
use HoneyComb\Starter\Repositories\HCBaseRepository;

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
}