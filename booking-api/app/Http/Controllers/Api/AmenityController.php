<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Amenity\StoreAmenityRequest;
use App\Http\Requests\Api\Amenity\UpdateAmenityRequest;
use App\Http\Requests\Api\Amenity\FilterAmenityRequest;
use App\Http\Requests\Api\Amenity\BulkStoreAmenityRequest;
use App\Http\Resources\AmenityResource;
use App\Http\Resources\AmenityCollection;
use App\Models\Amenity;
use App\Services\Amenity\CreateAmenityService;
use App\Services\Amenity\UpdateAmenityService;
use App\Services\Amenity\FilterAmenitiesService;
use App\Services\Amenity\BulkCreateAmenitiesService;
use Illuminate\Http\JsonResponse;

class AmenityController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->middleware('can:admin')->only(['store', 'update', 'destroy', 'bulkStore']);
    }

    public function index(FilterAmenityRequest $request, FilterAmenitiesService $filter): JsonResponse
    {
        $amenities = $filter->execute($request);

        return response()->json([
            'success' => true,
            'data' => $request->has('per_page')
                ? new AmenityCollection($amenities)
                : AmenityResource::collection($amenities),
            'message' => 'Список удобств получен'
        ]);
    }

    public function store(StoreAmenityRequest $request, CreateAmenityService $action): JsonResponse
    {
        $amenity = $action->execute($request->validated());

        return response()->json([
            'success' => true,
            'data' => new AmenityResource($amenity),
            'message' => 'Удобство успешно создано'
        ], 201);
    }

    public function show(Amenity $amenity): JsonResponse
    {
        $amenity->loadCount(['hotels', 'rooms']);

        return response()->json([
            'success' => true,
            'data' => new AmenityResource($amenity),
            'message' => 'Информация об удобстве получена'
        ]);
    }

    public function update(
        UpdateAmenityRequest $request,
        Amenity $amenity,
        UpdateAmenityService $action
    ): JsonResponse {
        $amenity = $action->execute($amenity, $request->validated());

        return response()->json([
            'success' => true,
            'data' => new AmenityResource($amenity),
            'message' => 'Удобство обновлено'
        ]);
    }

    public function destroy(Amenity $amenity): JsonResponse
    {
        // Проверка, используется ли удобство
        $usageCount = $amenity->hotels()->count() + $amenity->rooms()->count();

        if ($usageCount > 0) {
            return response()->json([
                'success' => false,
                'message' => "Невозможно удалить удобство: оно используется в {$usageCount} объектах",
                'data' => [
                    'hotels_count' => $amenity->hotels()->count(),
                    'rooms_count' => $amenity->rooms()->count(),
                ]
            ], 409);
        }

        $amenity->delete();

        return response()->json([
            'success' => true,
            'message' => 'Удобство удалено'
        ]);
    }

    public function bulkStore(
        BulkStoreAmenityRequest $request,
        BulkCreateAmenitiesService $action
    ): JsonResponse {
        $result = $action->execute($request->amenities);

        return response()->json([
            'success' => count($result['errors']) === 0,
            'message' => count($result['created']) . ' удобств создано, ' . count($result['errors']) . ' ошибок',
            'data' => [
                'created' => AmenityResource::collection($result['created']),
                'errors' => $result['errors'],
            ]
        ], count($result['errors']) === 0 ? 201 : 207);
    }
}
