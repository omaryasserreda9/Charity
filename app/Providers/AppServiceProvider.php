<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        Blade::directive('vite', function ($expression) {

            return "<?php
            echo '<script type=\"module\" src=\"http://localhost:5173/@vite/client\"></script>';
            foreach ($expression as \$file) {
                echo '<script type=\"module\" src=\"http://localhost:5173/' . \$file . '\"></script>';
            }
        ?>";
        });
    }
}
