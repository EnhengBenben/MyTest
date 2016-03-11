<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LocalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        if($this->app->environment('local'))
        {
            \Event::listen('illuminate.query', function($sql, $bindings, $time)
            {
                $sql = str_replace(array('%', '?', "\n"), array('%%', '%s', ''), $sql);
                $sql = vsprintf($sql, $bindings);
                \Log::info($sql, ['time' => $time.'ms']);
            });

            $this->app->register('Barryvdh\LaravelIdeHelper\IdeHelperServiceProvider');
//            $this->app->register('Way\Generators\GeneratorsServiceProvider');
//            $this->app->register('Xethron\MigrationsGenerator\MigrationsGeneratorServiceProvider');
        }
    }
}
