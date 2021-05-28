<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // make your own query file
      if(env('APP_DEBUG')) {
        \DB::listen(function($query){
            \File::append(
                storage_path('logs/query.log'),
                $query->sql . '[' . implode(', ', $query->bindings) . ']' . PHP_EOL
            );
        });
      }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
