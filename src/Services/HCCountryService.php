<?php

declare(strict_types = 1);

namespace HoneyComb\Regions\Services;

use HoneyComb\Regions\Repositories\HCCountryRepository;

class HCCountryService
{
    /**
     * @var HCCountryRepository
     */
    private $repository;

    /**
     * HCCountryService constructor.
     * @param HCCountryRepository $repository
     */
    public function __construct(HCCountryRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return HCCountryRepository
     */
    public function getRepository(): HCCountryRepository
    {
        return $this->repository;
    }
}