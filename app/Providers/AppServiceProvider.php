<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;  // ← pindah ke sini, di atas class

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // ← directive masuk ke DALAM method boot()
        Blade::directive('active', function ($route) {
            return "<?php echo request()->routeIs({$route}) ? 'active' : ''; ?>";
        });
    }
}