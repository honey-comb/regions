<?php
/**
 * @copyright 2017 interactivesolutions
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the 'Software'), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED 'AS IS', WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
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

namespace HoneyComb\Regions\Forms;

use HoneyComb\Regions\Repositories\HCCountryRepository;
use HoneyComb\Starter\Forms\HCBaseForm;

/**
 * Class HCCityForm
 * @package HoneyComb\Regions\Forms
 */
class HCCityForm extends HCBaseForm
{
    /**
     * @var bool
     */
    protected $multiLanguage = true;
    /**
     * @var \HoneyComb\Regions\Repositories\HCCountryRepository
     */
    private $countryRepository;

    /**
     * HCCityForm constructor.
     * @param \HoneyComb\Regions\Repositories\HCCountryRepository $countryRepository
     */
    public function __construct(HCCountryRepository $countryRepository)
    {

        $this->countryRepository = $countryRepository;
    }

    /**
     * Creating form
     *
     * @param bool $edit
     * @return array
     * @throws \Illuminate\Container\EntryNotFoundException
     */
    public function createForm(bool $edit = false): array
    {
        $form = [
            'storageUrl' => route('admin.api.regions.city'),
            'buttons' => [
                'submit' => [
                    'label' => $this->getSubmitLabel($edit),
                ],
            ],
            'structure' => $this->getStructure($edit),
        ];

        if ($this->multiLanguage) {
            $form['availableLanguages'] = getHCContentLanguages();
        }

        return $form;
    }

    /**
     * @param string $prefix
     * @return array
     */
    public function getStructureNew(string $prefix): array
    {
        return [
            $prefix . 'country_id' =>
                [
                    'type' => 'dropDownList',
                    'label' => trans('HCRegions::regions_city.country_id'),
                    'required' => 1,
                    'options' => optimizeTranslationOptions($this->countryRepository->all())
                ],
            $prefix . 'translations.label' =>
                [
                    'type' => 'singleLine',
                    'label' => trans('HCRegions::regions_city.label'),
                    'multiLanguage' => 1,
                    'required' => 1,
                    'requiredVisible' => 1,
                ]
        ];
    }

    /**
     * @param string $prefix
     * @return array
     */
    public function getStructureEdit(string $prefix): array
    {
        return $this->getStructureNew($prefix);
    }
}
