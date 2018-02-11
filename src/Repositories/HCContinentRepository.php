<?php

declare(strict_types = 1);

namespace HoneyComb\Regions\Repositories;

use HoneyComb\Regions\Models\HCContinent;
use HoneyComb\Core\Repositories\Traits\HCQueryBuilderTrait;
use HoneyComb\Starter\Repositories\HCBaseRepository;

class HCContinentRepository extends HCBaseRepository
{
    use HCQueryBuilderTrait;

    /**
     * @return string
     */
    public function model(): string
    {
        return HCContinent::class;
    }

}