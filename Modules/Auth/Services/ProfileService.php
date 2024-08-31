<?php


namespace  Modules\Auth\Services;

use App\ErrorHandlling\Result;
use Graphicode\Standard\TDO\TDO;
use Illuminate\Support\Facades\Auth;
use Modules\Auth\Entities\User;
use Modules\Restaurants\Entities\Restaurant;

use function PHPSTORM_META\map;

class ProfileService
{
    private static $model = User::class;

    /**
     * Get all profiles.
     */
    public function getAllProfiles(string $role)
    {
        try {
            $profiles = self::$model::query()
                ->whereHas('roles', function ($q) use ($role) {
                    $q->where('name', $role);
                })
                ->get();
            return Result::done($profiles);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    /**
     * Get profile for authenticated user
     */
    public function currentProfile()
    {
        $profile = Auth::user();
        return Result::done($profile);
    }

    /**
     * update user pfrofile.
     */
    public function update(TDO $tdo)
    {
        try {
            $data = $tdo->all(asSnake: true);
            $user = auth()->user();

            $updateData = collect($data)
                ->except(['image', 'professions'])
                ->toArray();



            $user->update($updateData);

            // update profile image
            if ($tdo->image) {
                $user->addMedia($tdo->image)
                    ->toMediaCollection('user');
            }

            if ($user->role == 'provider' && $tdo->professions) {
                $user->professions()->sync($tdo->professions);
            }

            $user = self::$model::find($user->id);

            return Result::done($user);
        } catch (\Throwable $e) {
            return Result::error($e->getMessage());
        }
    }

    public function deleteProfile(string $id)
    {
        try {
            $profile = self::$model::find($id);

            if (! $profile ) {
                return Result::error("No profile with id $id");
            }

            return Result::done($profile->delete());
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }
}
