<?php



namespace App\Providers;



use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Schema;



class AppServiceProvider extends ServiceProvider

{

    public function register()

    {

        //

    }



    public function boot()
    {
        Schema::defaultStringLength(191);

        Blade::directive('vite', function ($expression) {
            return "<?php
            \$manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
            \$assets = {$expression};

            foreach (\$assets as \$asset) {
                if (!isset(\$manifest[\$asset])) {
                    continue;
                }

                \$file = asset('build/' . \$manifest[\$asset]['file']);

                if (substr(\$file, -4) === '.css') {
                    echo '<link rel=\"stylesheet\" href=\"'.\$file.'\">';
                } else {
                    echo '<script type=\"module\" src=\"'.\$file.'\"></script>';
                }
            }
        ?>";
        });
    }
}
