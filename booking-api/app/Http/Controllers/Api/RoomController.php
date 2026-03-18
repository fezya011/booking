<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Room\StoreRoomRequest;
use App\Http\Requests\Api\Room\UpdateRoomRequest;
use App\Http\Requests\Api\Room\CheckAvailabilityRequest;
use App\Http\Resources\RoomResource;
use App\Http\Resources\RoomCollection;
use App\Models\Room;
use App\Models\Hotel;
use App\Services\Room\CreateRoomService;
use App\Services\Room\UpdateRoomService;
use App\Services\Room\CheckRoomAvailabilityService;
use App\Services\Room\FilterRoomsService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class RoomController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show', 'checkAvailability']);
        $this->middleware('can:admin')->only(['store', 'update', 'destroy']);
    }

    public function index(Request $request, $hotelId, FilterRoomsService $filter): JsonResponse
    {
        $hotel = Hotel::find($hotelId);

        if (!$hotel) {
            return response()->json([
                'success' => false,
                'message' => 'Отель не найден'
            ], 404);
        }

        $rooms = $filter->execute($request, $hotelId);

        return response()->json([
            'success' => true,
            'data' => RoomResource::collection($rooms),
            'meta' => [
                'total' => $rooms->count(),
                'hotel_id' => (int)$hotelId,
                'hotel_name' => $hotel->name,
            ],
            'message' => 'Список номеров получен'
        ]);
    }

    public function store(StoreRoomRequest $request, CreateRoomService $action): JsonResponse
    {
        $room = $action->execute($request->validated());

        return response()->json([
            'success' => true,
            'data' => new RoomResource($room),
            'message' => 'Номер успешно создан'
        ], 201);
    }

    public function show(Room $room): JsonResponse
    {
        $room->load(['hotel', 'amenities']);

        return response()->json([
            'success' => true,
            'data' => new RoomResource($room),
            'message' => 'Информация о номере получена'
        ]);
    }

    public function update(UpdateRoomRequest $request, Room $room, UpdateRoomService $action): JsonResponse
    {
        $room = $action->execute($room, $request->validated());

        return response()->json([
            'success' => true,
            'data' => new RoomResource($room),
            'message' => 'Номер успешно обновлен'
        ]);
    }

    public function destroy(Room $room): JsonResponse
    {
        // Проверка на наличие активных бронирований
        if ($room->bookings()->whereIn('status', ['pending', 'confirmed'])->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Невозможно удалить номер с активными бронированиями'
            ], 409);
        }

        $room->delete();

        return response()->json([
            'success' => true,
            'message' => 'Номер успешно удален'
        ]);
    }

    public function checkAvailability(
        CheckAvailabilityRequest $request,
        Room $room,
        CheckRoomAvailabilityService $service
    ): JsonResponse {
        $result = $service->execute($room, $request);

        return response()->json([
            'success' => true,
            'data' => $result,
            'message' => $result['is_available'] ? 'Номер доступен' : 'Номер недоступен на выбранные даты'
        ]);
    }
}
