<?php
/**
 * @copyright 2018 interactivesolutions
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * Contact InteractiveSolutions:
 * E-mail: hello@interactivesolutions.lt
 * http://www.interactivesolutions.lt
 */

declare(strict_types = 1);

namespace HoneyComb\Regions\Repositories;

use HoneyComb\Core\Repositories\Traits\HCQueryBuilderTrait;
use HoneyComb\Regions\Http\Requests\Admin\HCCityRequest;
use HoneyComb\Regions\Models\HCCity;
use HoneyComb\Starter\Repositories\HCBaseRepository;
use Illuminate\Support\Collection;

/**
 * Class HCCityRepository
 * @package HoneyComb\Regions\Repositories
 */
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
     * @param array $ids
     * @return array
     */
    public function deleteSoft(array $ids): array
    {
        $deleted = [];

        $records = $this->makeQuery()->whereIn('id', $ids)->get();

        /** @var HCCity $record */
        foreach ($records as $record) {
            if ($record->delete()) {
                $deleted[] = $record;
            }
        }

        return $deleted;
    }

    /**
     * @param array $ids
     * @return array
     */
    public function restore(array $ids): array
    {
        $restored = [];

        $records = $this->makeQuery()->withTrashed()->whereIn('id', $ids)->get();

        /** @var HCCity $record */
        foreach ($records as $record) {
            if ($record->restore()) {
                $restored[] = $record;
            }
        }

        return $restored;
    }

    /**
     * @param array $ids
     * @return array
     */
    public function deleteForce(array $ids): array
    {
        $deleted = [];

        $records = $this->makeQuery()->withTrashed()->whereIn('id', $ids)->get();

        /** @var HCCity $record */
        foreach ($records as $record) {
            if ($record->forceDelete()) {
                $deleted[] = $record;
            }
        }

        return $deleted;
    }

    /**
     * @param \HoneyComb\Regions\Http\Requests\Admin\HCCityRequest $request
     * @return \Illuminate\Support\Collection
     */
    public function getOptions(HCCityRequest $request): Collection
    {
        return optimizeTranslationOptions($this->createBuilderQuery($request)->where('visible', '1')->get());
    }

    /**
     * @param HCCity $record
     * @return array
     */
    public function formatForOptions(HCCity $record): array
    {
        return optimizeSingleTranslationOption($record);
    }
}