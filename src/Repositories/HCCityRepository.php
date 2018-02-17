<?php

declare(strict_types = 1);

namespace HoneyComb\Regions\Repositories;

use HoneyComb\Regions\Http\Requests\HCCityRequest;
use HoneyComb\Regions\Models\HCCity;
use HoneyComb\Core\Repositories\Traits\HCQueryBuilderTrait;
use HoneyComb\Starter\Repositories\HCBaseRepository;
use Illuminate\Support\Collection;

class HCCityRepository extends HCBaseRepository
{
    use HCQueryBuilderTrait;

    /**
     * @return string
     */
    public function model(): string
    {
        return HCCity::class;
    }

    /**
     * Soft deleting records
     * @param $ids
     */
    public function deleteSoft(array $ids): void
    {
        $records = $this->makeQuery()->whereIn('id', $ids)->get();

        foreach ($records as $record) {
            /** @var HCCity $record */
            $record->translations()->delete();
            $record->delete();
        }
    }

    /**
     * Restore soft deleted records
     *
     * @param array $ids
     * @return void
     */
    public function restore(array $ids): void
    {
        $records = $this->makeQuery()->withTrashed()->whereIn('id', $ids)->get();

        foreach ($records as $record) {
            /** @var HCCity $record */
            $record->translations()->restore();
            $record->restore();
        }
    }

    /**
     * Force delete records by given id
     *
     * @param array $ids
     * @return void
     */
    public function deleteForce(array $ids): void
    {
        $records = $this->makeQuery()->withTrashed()->whereIn('id', $ids)->get();

        foreach ($records as $record) {
            /** @var HCCity $record */
            $record->translations()->forceDelete();
            $record->forceDelete();
        }
    }

    /**
     * @param \HoneyComb\Regions\Http\Requests\HCCityRequest $request
     * @return \Illuminate\Support\Collection
     */
    public function getOptions (HCCityRequest $request): Collection
    {
        return optimizeTranslationOptions($this->createBuilderQuery($request)->where('visible', '1')->get());
    }

    /**
     * @param $record
     * @return mixed
     */
    public function formatForOptions(HCcity $record): array
    {
        return optimizeSingleTranslationOption($record);
    }
}