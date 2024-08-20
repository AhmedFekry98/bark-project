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
    public function getAllProfiles()
    {
        try {
            $profiles = self::$model::all();
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
            $user = Auth::user();

            $updateData = collect($data)
                ->except(['image', 'address', 'location', 'socialmedia_links', 'restaurant_name'])
                ->toArray();


            // update extra data fields
            $extra = $user->extra;
            $updatedExtra = collect($data)->only(['address', 'location', 'open_time', 'close_time']);
            foreach ($updatedExtra as $key => $value) $extra[$key] = $value;
            $updateData['extra'] = $extra;

            $user->update($updateData);

            // update profile image
            if ($tdo->image) {
                $user->addMedia($tdo->image)
                    ->toMediaCollection('user');
            }

            // update restaurant raw data
            if ($user->role == 'restaurant') {
                $restauData = collect($data)
                    ->only(['restaurant_name', 'oopen_time', 'close_time'])
                    ->toArray();

                $user->restaurant->update($restauData);
            }

            // update restaurant caver.
            if ( $user->role == 'restaurant' && $tdo->restaurantImage ) {
                $user->restaurant->addMedia($tdo->restaurantImage)
                    ->toMediaCollection('restaurant-caver');
            }

            // update socialmedia links
            if ($user->role == 'restaurant' && $tdo->socialmediaLinks) {
                $links = $user->restaurant->socialmedia_links;
                foreach ($data['socialmedia_links'] as $key => $value) $links[$key] = $value;
                $user->restaurant->socialmedia_links = $links;
                $user->restaurant->save();
            }

            $profile = self::$model::find($user->id);

            return Result::done($profile);
        } catch (\Throwable $e) {
            return Result::error($e->getMessage());
        }
    }
}
