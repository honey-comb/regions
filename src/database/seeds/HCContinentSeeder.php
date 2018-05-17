<?php
/**
 * @copyright 2018 innovationbase
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
 * Contact InnovationBase:
 * E-mail: hello@innovationbase.eu
 * https://innovationbase.eu
 */

declare(strict_types = 1);

namespace HoneyComb\Regions\Database\Seeds;

use HoneyComb\Regions\Repositories\HCContinentRepository;
use HoneyComb\Regions\Repositories\HCCountryRepository;
use Illuminate\Database\Connection;
use Illuminate\Database\Seeder;

/**
 * Class HCContinentSeeder
 * @package HoneyComb\Regions\Database\Seeds
 */
class HCContinentSeeder extends Seeder
{
    /**
     * @var \Illuminate\Database\Connection
     */
    private $connection;

    /**
     * @var HCContinentRepository
     */
    private $continentRepository;

    /**
     * HCContinentSeeder constructor.
     * @param Connection $connection
     * @param HCContinentRepository $continentRepository
     */
    public function __construct(
        Connection $connection,
        HCContinentRepository $continentRepository
    ) {
        $this->connection = $connection;
        $this->continentRepository = $continentRepository;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $continents = json_decode(file_get_contents(__DIR__ . '/../../resources/json/continents.json'), true);

        foreach ($continents as $continent) {

            $this->connection->beginTransaction();

            try {
                $translations = $continent['translations'];
                array_forget($continent, 'translations');

                $continentRecord = $this->continentRepository->updateOrCreate(['id' => $continent['id']], $continent);
                $continentRecord->updateTranslations($translations);

                $this->connection->commit();

            } catch (\Exception $e) {
                $this->connection->rollBack();
                dd($e);
            }
        }
    }
}