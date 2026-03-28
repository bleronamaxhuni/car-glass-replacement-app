<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\CarBodyTypesRequest;
use App\Http\Requests\Api\CarModelsRequest;
use App\Http\Requests\Api\CarYearsRequest;
use App\Services\CarApiClient;
use Illuminate\Http\JsonResponse;

class CarApiController extends Controller
{
    /**
     * @var CarApiClient
     */
    private CarApiClient $client;

    /**
     * Initialize the CarApiClient
     * @param CarApiClient $client
     */
    public function __construct(CarApiClient $client)
    {
        $this->client = $client;
    }

    /**
     * Get list of car makes
     *
     * @return JsonResponse
     */
    public function makes(): JsonResponse
    {
        return response()->json([
            'data' => $this->client->getMakes(),
        ]);
    }

    /**
     * Get list of models for a specific make
     *
     * @param CarModelsRequest $request
     * @return JsonResponse
     */
    public function models(CarModelsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $models = $this->client->getModels($validated['make']);

        return response()->json([
            'data' => array_values($models),
        ]);
    }

    /**
     * Get list of years for a specific make and model
     *
     * @param CarYearsRequest $request
     * @return JsonResponse
     */
    public function years(CarYearsRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $years = $this->client->getYears($validated['make'], $validated['model']);

        return response()->json([
            'data' => array_values($years),
        ]);
    }

    /**
     * Get list of body types for a specific make / model / year
     *
     * @param CarBodyTypesRequest $request
     * @return JsonResponse
     */
    public function bodyTypes(CarBodyTypesRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $bodyTypes = $this->client->getBodyTypes(
            $validated['make'],
            $validated['model'],
            (int) $validated['year']
        );

        return response()->json([
            'data' => array_values($bodyTypes),
        ]);
    }
}

