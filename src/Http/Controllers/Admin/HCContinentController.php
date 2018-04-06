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

use HoneyComb\Core\Http\Controllers\HCBaseController;
use HoneyComb\Core\Http\Controllers\Traits\HCAdminListHeaders;
use HoneyComb\Regions\Events\Admin\Continent\HCContinentUpdated;
use HoneyComb\Regions\Http\Requests\Admin\HCContinentRequest;
use HoneyComb\Regions\Models\HCContinent;
use HoneyComb\Regions\Services\HCContinentService;
use HoneyComb\Starter\Helpers\HCFrontendResponse;
use Illuminate\Database\Connection;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;


/**
 * Class HCContinentController
 * @package HoneyComb\Regions\Http\Controllers\Admin
 */
class HCContinentController extends HCBaseController
{
    use HCAdminListHeaders;

    /**
     * @var HCContinentService
     */
    protected $service;

    /**
     * @var Connection
     */
    protected $connection;

    /**
     * @var HCFrontendResponse
     */
    protected $response;

    /**
     * HCContinentController constructor.
     * @param Connection $connection
     * @param HCFrontendResponse $response
     * @param HCContinentService $service
     */
    public function __construct(Connection $connection, HCFrontendResponse $response, HCContinentService $service)
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
            'title' => trans('HCRegion::regions_continents.page_title'),
            'url' => route('admin.api.regions.continents'),
            'form' => route('admin.api.form-manager', ['regions.continents']),
            'headers' => $this->getTableColumns(),
            'actions' => $this->getActions('honey_comb_regions_regions_continents'),
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
            'translation.label' => $this->headerText(trans('HCRegion::regions_continents.label')),
        ];

        return $columns;
    }

    /**
     * @param string $id
     * @return HCContinent|null
     */
    public function getById(string $id): ? HCContinent
    {
        return $this->service->getRepository()->findOneBy(['id' => $id]);
    }

    /**
     * Creating data list
     * @param HCContinentRequest $request
     * @return JsonResponse
     */
    public function getListPaginate(HCContinentRequest $request): JsonResponse
    {
        return response()->json(
            $this->service->getRepository()->getListPaginate($request)
        );
    }

    /**
     * Create data list
     * @param HCMenuRequest $request
     * @return JsonResponse
     */
    public function getOptions(HCContinentRequest $request): JsonResponse
    {
        return response()->json(
            $this->service->getRepository()->getOptions($request)
        );
    }

    /**
     * @param HCContinentRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(HCContinentRequest $request, string $id): JsonResponse
    {
        /** @var HCContinent $record */
        $record = $this->service->getRepository()->findOneBy(['id' => $id]);
        $record->update($request->getRecordData());

        if ($record) {
            $record = $this->service->getRepository()->find($id);

            event(new HCContinentUpdated($record));
        }

        return $this->response->success("Created");
    }


}