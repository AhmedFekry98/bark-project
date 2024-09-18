<?php

namespace Modules\Review\Services;

use App\ErrorHandlling\Result;
use Graphicode\Standard\TDO\TDO;
use Modules\Auth\Entities\User;
use Modules\Review\Entities\Review;

class ReviewService
{
    private static $model = Review::class;

    public function getProvidersWithRating()
    {
        try {
            $providers = User::query()
                ->whereHas('reviews')
                ->whereHas('roles', fn($q) => $q->where('name', 'provider'))
                ->withAvg('reviews', 'stars')
                ->with('reviews', fn ($q) => $q->latest()->limit(1))
                ->get();


            return Result::done($providers);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function storeReview(TDO $tdo)
    {
        try {

            // query check already keeped reviewed before.
            $reviewedBefore = self::$model::query()
                ->where('reviewable_id', $tdo->reviewableId)
                ->where('reviewer_id', auth()->id())
                ->exists();

            // keep review if not reviewed before.
            // if ($reviewedBefore) {
            //     return Result::error("You keeped your review before");
            // }


            $creationData = $tdo->all(
                asSnake: true
            );

            $creationData['reviewer_id'] =  auth()->id();

            $review = self::$model::create($creationData);


            return Result::done($review);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }


    public function updateReview(TDO $tdo, mixed $reviewId)
    {
        try {
            $review = self::$model::find($reviewId);
            if (! $review) return Result::error("No review with id $reviewId");

            // owner only can update review.
            if ($review->reviewer_id != auth()->id()) {
                return Result::error("You not have perission to update this review");
            }

            $updateData = $tdo->all(
                asSnake: true
            );


            $review->update($updateData);


            return Result::done($review);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }
}
