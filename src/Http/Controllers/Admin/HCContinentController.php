<?php

declare(strict_types = 1);

namespace HoneyComb\Regions\Http\Controllers\Admin;

use HoneyComb\Regions\Services\HCContinentService;
use HoneyComb\Regions\Http\Requests\HCContinentRequest;
use HoneyComb\Regions\Models\HCContinent;

use HoneyComb\Core\Http\Controllers\HCBaseController;
use HoneyComb\Core\Http\Controllers\Traits\HCAdminListHeaders;
use HoneyComb\Starter\Helpers\HCFrontendResponse;
use Illuminate\Database\Connection;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

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
    private $connection;

    /**
     * @var HCFrontendResponse
     */
    private $response;

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
     * Update record
     *
     * @param HCContinentRequest $request
     * @param string $id
     * @return JsonResponse
     */
    public function update(HCContinentRequest $request, string $id): JsonResponse
    {
        $model = $this->service->getRepository()->findOneBy(['id' => $id]);
        $model->update($request->getRecordData());

        return $this->response->success("Created");
    }


}