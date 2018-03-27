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

namespace HoneyComb\Regions\Http\Controllers\Admin;

use HoneyComb\Regions\Services\HCCountryService;
use HoneyComb\Regions\Http\Requests\Admin\HCCountryRequest;
use HoneyComb\Regions\Models\HCCountry;

use HoneyComb\Core\Http\Controllers\HCBaseController;
use HoneyComb\Core\Http\Controllers\Traits\HCAdminListHeaders;
use HoneyComb\Starter\Helpers\HCFrontendResponse;
use Illuminate\Database\Connection;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;


/**
 * Class HCCountryController
 * @package HoneyComb\Regions\Http\Controllers\Admin
 */
class HCCountryController extends HCBaseController
{
    use HCAdminListHeaders;

    /**
     * @var HCCountryService
     */
    protected $service;

    /**
     * @var Connection
     */
    private $connection;

    /**
     * @var HCFrontendResponse
     */
    private $response;

    /**
     * HCCountryController constructor.
     * @param Connection $connection
     * @param HCFrontendResponse $response
     * @param HCCountryService $service
     */
    public function __construct(Connection $connection, HCFrontendResponse $response, HCCountryService $service)
    {
        $this->connection = $connection;
        $this->response = $response;
        $this->service = $service;
    }

    /**
     * Admin panel page view
     *
     * @return View
     */
    public function index(): View
    {
        $config = [
            'title' => trans('HCRegion::regions_countries.page_title'),
            'url' => route('admin.api.regions.countries'),
            'form' => route('admin.api.form-manager', ['regions.countries']),
            'headers' => $this->getTableColumns(),
            'actions' => $this->getActions('honey_comb_regions_regions_countries'),
        ];

        return view('HCCore::admin.service.index', ['config' => $config]);
    }

    /**
     * Get admin page table columns settings
     *
     * @return array
     */
    public function getTableColumns(): array
    {
        $columns = [
            'flag_id' => $this->headerImage(trans('HCRegion::regions_countries.flag_id')),
            'translation.label' => $this->headerText(trans('HCRegion::regions_countries.label')),
            'visible' => $this->headerCheckBox(trans('HCRegion::regions_countries.visible')),
        ];

        return $columns;
    }

    /**
     * @param string $id
     * @return HCCountry|null
     */
    public function getById(string $id): ? HCCountry
    {
        return $this->service->getRepository()->findOneBy(['id' => $id]);
    }

    /**
     * Creating data list
     * @param HCCountryRequest $request
     * @return JsonResponse
     */
    public function getListPaginate(HCCountryRequest $request): JsonResponse
    {
        return response()->json(
            $this->service->getRepository()->getListPaginate($request)
        );
    }

    /**
     * Creating data list
     * @param HCCountryRequest $request
     * @return JsonResponse
     */
    public function getList(HCCountryRequest $request): JsonResponse
    {
        return response()->json($this->service->getRepository()->getOptions($request));
    }


    /**
     * Update record
     *
     * @param HCCountryRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(HCCountryRequest $request, string $id): JsonResponse
    {
        $model = $this->service->getRepository()->findOneBy(['id' => $id]);
        $model->update($request->getRecordData());
        $model->updateTranslations($request->getTranslations());

        return $this->response->success("Created");
    }

    /**
     * @param \HoneyComb\Regions\Http\Requests\Admin\HCCountryRequest $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function patch (HCCountryRequest $request, string  $id)
    {
        $this->service->getRepository()->update($request->getPatchValues(), $id);

        return $this->response->success('Updated');
    }

}