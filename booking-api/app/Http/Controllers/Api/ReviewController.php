<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\Api\Review\StoreReviewRequest;
use App\Http\Requests\Api\Review\UpdateReviewRequest;
use App\Http\Requests\Api\Review\FilterReviewRequest;
use App\Http\Requests\Api\Review\RespondToReviewRequest;
use App\Http\Requests\Api\Review\ReportReviewRequest;
use App\Http\Resources\ReviewResource;
use App\Http\Resources\ReviewCollection;
use App\Models\Review;
use App\Models\Hotel;
use App\Models\User;
use App\Services\Review\CreateReviewService;
use App\Services\Review\UpdateReviewService;
use App\Services\Review\FilterReviewsService;
use App\Services\Review\HelpfulVoteService;
use App\Services\Review\UpdateHotelRatingService;
use Illuminate\Http\JsonResponse;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->except(['index', 'show']);
    }

    public function index(FilterReviewRequest $request, int $hotelId, FilterReviewsService $filter): JsonResponse
    {
        $hotel = Hotel::find($hotelId);

        if (!$hotel) {
            return response()->json([
                'success' => false,
                'message' => 'Отель не найден'
            ], 404);
        }

        $reviews = $filter->execute($request, $hotelId, $request->user());

        return response()->json([
            'success' => true,
            'data' => new ReviewCollection($reviews),
            'message' => 'Список отзывов получен'
        ]);
    }

    public function store(StoreReviewRequest $request, int $hotelId, CreateReviewService $action): JsonResponse
    {
        $hotel = Hotel::find($hotelId);

        if (!$hotel) {
            return response()->json([
                'success' => false,
                'message' => 'Отель не найден'
            ], 404);
        }

        try {
            /** @var \App\Models\User $user */
            $user = $request->user();
            $review = $action->execute($user, $hotel, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Спасибо за ваш отзыв!',
                'data' => new ReviewResource($review)
            ], 201);

        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function show(Review $review): JsonResponse
    {
        $review->load(['user', 'hotel', 'responder']);

        return response()->json([
            'success' => true,
            'data' => new ReviewResource($review),
            'message' => 'Информация об отзыве получена'
        ]);
    }

    public function update(UpdateReviewRequest $request, Review $review, UpdateReviewService $action): JsonResponse
    {
        try {
            $review = $action->execute($review, $request->validated());

            return response()->json([
                'success' => true,
                'message' => 'Отзыв обновлен',
                'data' => new ReviewResource($review)
            ]);

        }
        catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при обновлении: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy(Review $review): JsonResponse
    {
        /** @var User $user */
        $user = request()->user();

        if ($review->user_id !== $user->id && !$user->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'У вас нет прав для удаления этого отзыва'
            ], 403);
        }

        try {
            $hotel = $review->hotel;
            $review->delete();

            // Обновляем рейтинг отеля
            app(UpdateHotelRatingService::class)->execute($hotel);

            return response()->json([
                'success' => true,
                'message' => 'Отзыв удален'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Ошибка при удалении: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * POST /api/v1/reviews/{review}/helpful - Отметить отзыв как полезный
     */
    public function markHelpful(Review $review, HelpfulVoteService $service): JsonResponse
    {
        /** @var User $user */
        $user = request()->user();

        $result = $service->toggle($review, $user);

        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'data' => [
                'helpful_votes' => $result['votes_count'],
                'user_voted' => $result['voted'],
            ]
        ]);
    }

    public function respond(RespondToReviewRequest $request, Review $review): JsonResponse
    {
        /** @var User $user */
        $user = request()->user();

        $review->update([
            'hotel_response' => $request->response,
            'responded_at' => now(),
            'responded_by' => $user->id,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Ответ на отзыв опубликован',
            'data' => new ReviewResource($review)
        ]);
    }

    public function report(ReportReviewRequest $request, Review $review): JsonResponse {
        // Здесь логика сохранения жалобы
        // Report::create([...]);

        return response()->json([
            'success' => true,
            'message' => 'Жалоба отправлена модератору'
        ]);
    }
}
