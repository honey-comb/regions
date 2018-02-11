<?php

declare(strict_types = 1);

namespace HoneyComb\Regions\Http\Controllers\Admin;

use HoneyComb\Regions\Services\HCCountryService;
use HoneyComb\Regions\Http\Requests\HCCountryRequest;
use HoneyComb\Regions\Models\HCCountry;

use HoneyComb\Core\Http\Controllers\HCBaseController;
use HoneyComb\Core\Http\Controllers\Traits\HCAdminListHeaders;
use HoneyComb\Starter\Helpers\HCFrontendResponse;
use Illuminate\Database\Connection;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

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


}