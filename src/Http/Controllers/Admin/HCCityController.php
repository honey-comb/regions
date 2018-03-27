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

use HoneyComb\Regions\Services\HCCityService;
use HoneyComb\Regions\Http\Requests\Admin\HCCityRequest;
use HoneyComb\Regions\Models\HCCity;

use HoneyComb\Core\Http\Controllers\HCBaseController;
use HoneyComb\Core\Http\Controllers\Traits\HCAdminListHeaders;
use HoneyComb\Starter\Helpers\HCFrontendResponse;
use Illuminate\Database\Connection;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

/**
 * Class HCCityController
 * @package HoneyComb\Regions\Http\Controllers\Admin
 */
class HCCityController extends HCBaseController
{
    use HCAdminListHeaders;

    /**
     * @var HCCityService
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
     * HCCityController constructor.
     * @param Connection $connection
     * @param HCFrontendResponse $response
     * @param HCCityService $service
     */
    public function __construct(Connection $connection, HCFrontendResponse $response, HCCityService $service)
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
            'title' => trans('HCRegion::regions_city.page_title'),
            'url' => route('admin.api.regions.city'),
            'form' => route('admin.api.form-manager', ['regions.city']),
            'headers' => $this->getTableColumns(),
            'actions' => $this->getActions('honey_comb_regions_regions_city'),
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
            'country_id' => $this->headerText(trans('HCRegion::regions_city.country_id')),
            'translation.label' => $this->headerText(trans('HCRegion::regions_city.label')),
            'visible' => $this->headerCheckBox(trans('HCRegion::regions_city.visible')),
        ];

        return $columns;
    }

    /**
     * @param string $id
     * @return HCCity|null
     */
    public function getById(string $id): ? HCCity
    {
        return $this->service->getRepository()->findOneBy(['id' => $id]);
    }

    /**
     * Creating pages of data
     * @param HCCityRequest $request
     * @return JsonResponse
     */
    public function getListPaginate(HCCityRequest $request): JsonResponse
    {
        return response()->json(
            $this->service->getRepository()->getListPaginate($request)
        );
    }

    /**
     * Creating list
     *
     * @param \HoneyComb\Regions\Http\Requests\Admin\HCCityRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getList(HCCityRequest $request): JsonResponse
    {
        return response()->json(($this->service->getRepository()->getOptions($request)));
    }

    /**
     * Create record
     *
     * @param HCCityRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function store(HCCityRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $model = $this->service->getRepository()->create($request->getRecordData());
            $model->updateTranslations($request->getTranslations());

            $this->connection->commit();
        } catch (\Throwable $e) {
            $this->connection->rollBack();

            return $this->response->error($e->getMessage());
        }

        return $this->response->success("Created", $this->responseData($request, $model->id));
    }

    /**
     * @param \HoneyComb\Regions\Http\Requests\Admin\HCCityRequest $request
     * @param string $id
     * @return array|null
     */
    protected function responseData(HCCityRequest $request, string $id)
    {
        if ($request->isResponseForOptions())
            return $this->service->getRepository()->formatForOptions($this->getById($id));

        return null;
    }


    /**
     * Update record
     *
     * @param HCCityRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(HCCityRequest $request, string $id): JsonResponse
    {
        $model = $this->service->getRepository()->findOneBy(['id' => $id]);
        $model->update($request->getRecordData());
        $model->updateTranslations($request->getTranslations());

        return $this->response->success("Created");
    }

    /**
     * @param \HoneyComb\Regions\Http\Requests\Admin\HCCityRequest $request
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function patch (HCCityRequest $request, string  $id)
    {
        $this->service->getRepository()->update($request->getPatchValues(), $id);

        return $this->response->success('Updated');
    }


    /**
     * Soft delete record
     *
     * @param HCCityRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function deleteSoft(HCCityRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->service->getRepository()->deleteSoft($request->getListIds());

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            return $this->response->error($exception->getMessage());
        }

        return $this->response->success('Successfully deleted');
    }


    /**
     * Restore record
     *
     * @param HCCityRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function restore(HCCityRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->service->getRepository()->restore($request->getListIds());

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            return $this->response->error($exception->getMessage());
        }

        return $this->response->success('Successfully restored');
    }


    /**
     * Force delete record
     *
     * @param HCCityRequest $request
     * @return JsonResponse
     * @throws \Throwable
     */
    public function deleteForce(HCCityRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();

        try {
            $this->service->getRepository()->deleteForce($request->getListIds());

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            return $this->response->error($exception->getMessage());
        }

        return $this->response->success('Successfully deleted');
    }

}