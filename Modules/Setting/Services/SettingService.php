<?php

namespace Modules\Setting\Services;

use App\ErrorHandlling\Result;
use Graphicode\Standard\TDO\TDO;
use Modules\Setting\Entities\Setting;

class SettingService
{
    private static $model = Setting::class;

    public function getSetting()
    {
        try {
            $setting = self::$model::first();
            if (! $setting) {
                $setting = self::$model::factory()->create();
            }

            return Result::done($setting);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }

    public function updateSetting(TDO $tdo)
    {
        try {
            $result = $this->getSetting();
            if ($result->isError() ) return $result;
            $setting = $result->data;

            $settingData = $tdo->all(
                asSnake: true
            );

            $setting->update($settingData);
            $setting = self::$model::first();

            return Result::done($setting);
        } catch (\Exception $e) {
            return Result::error($e->getMessage());
        }
    }
}
