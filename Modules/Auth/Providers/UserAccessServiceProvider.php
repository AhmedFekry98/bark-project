<?php

namespace Modules\Auth\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use Laravel\Sanctum\Http\Middleware\CheckAbilities;
use Laravel\Sanctum\Http\Middleware\CheckForAnyAbility;
use Modules\Auth\Http\Middleware\RoleCheck;

class UserAccessServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->app->alias(CheckAbilities::class, 'abilities');
        $this->app->alias(CheckForAnyAbility::class, 'ability');
        $this->app->alias(RoleCheck::class, 'role');

        //  ? register the main permisions on system.
        $this->loadAndRegisterPermisions();
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }

    public function loadAndRegisterPermisions()
    {

        // $permisions = config('system.permisions');


        // foreach ($permisions as $ability => $check) {

        //     if ($check === null) {
        //         $check = fn () => true;
        //     }

        //     Gate::define($ability, $check);
        // }
    }
}
