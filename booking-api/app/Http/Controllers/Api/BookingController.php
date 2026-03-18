<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Api\Booking\StoreBookingRequest;
use App\Http\Requests\Api\Booking\UpdateBookingRequest;
use App\Http\Requests\Api\Booking\FilterBookingRequest;
use App\Http\Requests\Api\Booking\CancelBookingRequest;
use App\Http\Requests\Api\Booking\ConfirmBookingRequest;
use App\Http\Resources\BookingResource;
use App\Http\Resources\BookingCollection;
use App\Models\Booking;
use App\Models\User;
use App\Services\Booking\CreateBookingService;
use App\Services\Booking\UpdateBookingService;
use App\Services\Booking\CancelBookingService;
use App\Services\Booking\FilterBookingsService;
use App\Services\Booking\ConfirmBookingService;

class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(FilterBookingRequest $request, FilterBookingsService $filter): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $bookings = $filter->execute($request, $user);

        return response()->json([
            'success' => true,
            'data' => new BookingCollection($bookings),
            'message' => 'Список бронирований получен'
        ]);
    }

    public function store(StoreBookingRequest $request, CreateBookingService $action): JsonResponse
    {
        try {
            /** @var User $user */
            $user = $request->user();

            $booking = $action->execute($user, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Бронирование успешно создано',
                'data' => new BookingResource($booking)
            ], 201);

        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function show(Request $request, Booking $booking): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($booking->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет прав для просмотра этого бронирования'
            ], 403);
        }

        return response()->json([
            'success' => true,
            'data' => new BookingResource($booking->load(['hotel', 'room'])),
            'message' => 'Детали бронирования получены'
        ]);
    }

    public function update(UpdateBookingRequest $request, Booking $booking, UpdateBookingService $action): JsonResponse
    {
        try
        {
            $booking = $action->execute($booking, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Бронирование обновлено',
                'data' => new BookingResource($booking)
            ]);

        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function destroy(CancelBookingRequest $request, Booking $booking, CancelBookingService $action): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        if ($booking->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет прав для отмены этого бронирования'
            ], 403);
        }

        try {
            $action->execute($booking, $request->reason);

            return response()->json([
                'success' => true,
                'message' => 'Бронирование отменено'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function confirm(ConfirmBookingRequest $request, Booking $booking, ConfirmBookingService $action): JsonResponse
    {
        try {
            $booking = $action->execute($booking, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Бронирование подтверждено',
                'data' => new BookingResource($booking)
            ]);

        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function upcoming(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $bookings = Booking::where('user_id', $user->id)
            ->where('check_in', '>', now())
            ->whereIn('status', ['pending', 'confirmed'])
            ->with(['hotel', 'room'])
            ->orderBy('check_in')
            ->get();

        return response()->json([
            'success' => true,
            'data' => BookingResource::collection($bookings),
            'message' => 'Предстоящие бронирования получены'
        ]);
    }


    public function history(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $bookings = Booking::where('user_id', $user->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->with(['hotel', 'room'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return response()->json([
            'success' => true,
            'data' => new BookingCollection($bookings),
            'message' => 'История бронирований получена'
        ]);
    }
}
