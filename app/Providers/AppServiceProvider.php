<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot()
{
    if (env('APP_DEBUG')) { // On active uniquement en mode debug
        DB::listen(function ($query) {
            // Formate la requête SQL en remplaçant les "?" par les bindings
            $sql = vsprintf(str_replace('?', '"%s"', $query->sql), $query->bindings);
            $logLine = '[' . now() . '] ' . $sql . ' (' . $query->time . " ms)" . PHP_EOL;
            // Écrire dans storage/logs/sql.log
            file_put_contents(storage_path('logs/sql.log'), $logLine, FILE_APPEND);
        });
    }
}
}
