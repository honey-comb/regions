<?php

declare(strict_types = 1);

namespace HoneyComb\Regions\Services;

use HoneyComb\Regions\Repositories\HCCityRepository;

class HCCityService
{
    /**
     * @var HCCityRepository
     */
    private $repository;

    /**
     * HCCityService constructor.
     * @param HCCityRepository $repository
     */
    public function __construct(HCCityRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return HCCityRepository
     */
    public function getRepository(): HCCityRepository
    {
        return $this->repository;
    }
}