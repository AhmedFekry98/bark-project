<?php

namespace Modules\Badge\Services;

use App\ErrorHandlling\Result;
use Graphicode\Standard\TDO\TDO;
use Modules\Auth\Entities\User;
use Modules\Badge\Entities\Badge;

class BadgeService
{
    public static $model = Badge::class;

    public function getAllBadges(): Result
    {
        $badges = self::$model::with('media')
            ->withCount('providers')
            ->get();

        return Result::done($badges);
    }

    public function storeBadge(TDO $tdo): Result
    {
        $badge = self::$model::create([
            'label' => $tdo->label ?? ''
        ]);

        if ($tdo->icon) {
            $badge->addMedia($tdo->icon)
                ->toMediaCollection('icon');
        }

        return Result::done($badge);
    }

    public function assignBadges(TDO $tdo): Result
    {
        $user = User::find($tdo->userId);
        if (! $user || $user->role != 'provider') {
            return Result::error("userId most be valid user id with role provider");
        }

        $ids_of_badges = explode(',', trim($tdo->badges ?? ''));
        $badges =  self::$model::whereIn('id', $ids_of_badges)->get(['id']);

        $status = $user->badges()->sync(
            array_merge($user->badges->pluck(['id'])->toArray(), $badges->pluck(['id'])->toArray())
        );

        return Result::done($status);
    }

    public function updateBadge(string|int $badgeId, TDO $tdo): Result
    {
        $badge = self::$model::find($badgeId);

        if (! $badge) {
            return Result::error("No record with id $badgeId");
        }

        if ($tdo->label) {
            $badge->label;
            $badge->save();
        }

        if ($tdo->icon) {
            $badge->addMedia($tdo->icon)
                ->toMediaCollection('icon');
        }

        return Result::done($badge);
    }

    public function deleteBadge(string|int $badgeId): Result
    {
        $badge = self::$model::find($badgeId);

        if (! $badge) {
            return Result::error("No record with id $badgeId");
        }

        return Result::done($badge->delete());
    }
}
