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

namespace HoneyComb\Regions\Database\Seeds;

use HoneyComb\Regions\Models\HCCountry;
use HoneyComb\Regions\Repositories\HCCountryRepository;
use HoneyComb\Resources\Models\HCResource;
use HoneyComb\Resources\Services\HCResourceService;
use Illuminate\Database\Connection;
use Illuminate\Database\Seeder;

class HCCountriesSeeder extends Seeder
{
    /**
     * @var \HoneyComb\Resources\Services\HCResourceService
     */
    private $resourceService;
    /**
     * @var \Illuminate\Database\Connection
     */
    private $connection;
    /**
     * @var \HoneyComb\Regions\Repositories\HCCountryRepository
     */
    private $countryRepository;

    /**
     * HCCountriesSeeder constructor.
     * @param \Illuminate\Database\Connection $connection
     * @param \HoneyComb\Resources\Services\HCResourceService $resourceService
     * @param \HoneyComb\Regions\Repositories\HCCountryRepository $countryRepository
     */
    public function __construct(
        Connection $connection,
        HCResourceService $resourceService,
        HCCountryRepository $countryRepository
    ) {
        $this->resourceService = $resourceService;
        $this->connection = $connection;
        $this->countryRepository = $countryRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $countries = json_decode(file_get_contents(__DIR__ . '/../../resources/json/countries.json'), true);

        foreach ($countries as $country) {

            $this->connection->beginTransaction();

            try {
                $flag = $this->resourceService->getRepository()->findOneBy(['id' => $country['flag_id']]);

                if (!$flag) {
                    $this->resourceService->download(__DIR__ . '/../../resources/media/flags/' . $country['id'] . '.svg',
                        null, 'flag-' . $country['id']);
                }

                $translations = $country['translations'];
                array_forget($country, 'translations');

                $this->countryRepository->updateOrCreate(['id' => $country['id']], $country);
                $countryRecord = $this->countryRepository->findOneBy(['id' => $country['id']]);
                $countryRecord->updateTranslations($translations);

                $this->connection->commit();

            } catch (\Exception $e) {
                $this->connection->rollBack();
                dd($e);
            }
        }
    }
}