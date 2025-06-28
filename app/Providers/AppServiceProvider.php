<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        require_once app_path('Helpers/maskCpfBlade.php');
        
        Blade::directive('maskCpf', function ($cpf) {
            return "<?php echo (strlen($cpf) === 14) ? $cpf : (strlen($cpf) === 11 ? substr($cpf,0,3).'.'.substr($cpf,3,3).'.'.substr($cpf,6,3).'-'.substr($cpf,9,2) : $cpf); ?>";
        });
    }
}
