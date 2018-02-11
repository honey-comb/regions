<?php

declare(strict_types = 1);

namespace HoneyComb\Regions\Services;

use HoneyComb\Regions\Repositories\HCContinentRepository;

class HCContinentService
{
    /**
     * @var HCContinentRepository
     */
    private $repository;

    /**
     * HCContinentService constructor.
     * @param HCContinentRepository $repository
     */
    public function __construct(HCContinentRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return HCContinentRepository
     */
    public function getRepository(): HCContinentRepository
    {
        return $this->repository;
    }
}