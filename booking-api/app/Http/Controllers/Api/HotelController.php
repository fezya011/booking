<?php

namespace App\Http\Controllers\Api;


use App\Http\Requests\Api\Hotel\StoreHotelRequest;
use App\Http\Requests\Api\Hotel\UpdateHotelRequest;
use App\Http\Requests\Api\Hotel\FilterHotelRequest;
use App\Http\Resources\HotelResource;
use App\Http\Resources\HotelCollection;
use App\Models\Hotel;
use App\Services\Hotel\CreateHotelService;
use App\Services\Hotel\UpdateHotelService;
use App\Services\Hotel\FilterHotelsService;
use Illuminate\Http\JsonResponse;

class HotelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
        $this->middleware('can:admin')->only(['store', 'update', 'destroy']);
    }

    public function index(FilterHotelRequest $request, FilterHotelsService $filter): JsonResponse
    {
        $hotels = $filter->execute($request);

        return response()->json([
            'success' => true,
            'data' => new HotelCollection($hotels),
            'message' => 'Список отелей получен'
        ]);
    }

    public function store(StoreHotelRequest $request, CreateHotelService $action): JsonResponse
    {
        $hotel = $action->execute($request->validated());

        return response()->json([
            'success' => true,
            'data' => new HotelResource($hotel->load(['amenities'])),
            'message' => 'Отель успешно создан'
        ], 201);
    }

    public function show(Hotel $hotel): JsonResponse
    {
        $hotel->load(['amenities', 'rooms' => function($q) {
            $q->with('amenities')->where('is_active', true);
        }, 'reviews' => function($q) {
            $q->with('user')->latest()->limit(5);
        }]);

        return response()->json([
            'success' => true,
            'data' => new HotelResource($hotel),
            'message' => 'Информация об отеле получена'
        ]);
    }

    public function update(UpdateHotelRequest $request, Hotel $hotel, UpdateHotelService $action): JsonResponse
    {
        $hotel = $action->execute($hotel, $request->validated());

        return response()->json([
            'success' => true,
            'data' => new HotelResource($hotel->load(['amenities'])),
            'message' => 'Отель успешно обновлен'
        ]);
    }

    public function destroy(Hotel $hotel): JsonResponse
    {
        $hotel->delete();

        return response()->json([
            'success' => true,
            'message' => 'Отель успешно удален'
        ]);
    }
}
