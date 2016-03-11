<?php

namespace App\Providers;

use App\Http\Menu;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('layouts.master_admin', function ($view) {
            $menus = Menu::getMenu();
            $view->with('menus', $menus);//menus for view page
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
