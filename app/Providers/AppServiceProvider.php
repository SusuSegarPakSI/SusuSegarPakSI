<?php

namespace App\Providers {

    use Illuminate\Support\ServiceProvider;
    use Illuminate\Support\Facades\Blade;

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
        public function boot(): void
        {
            Blade::if('role', function (string ...$roles) {
                return auth()->check() && in_array(auth()->user()->role, $roles);
            });
        }
    }
}

namespace {
    if (!function_exists('formatRupiah')) {
        function formatRupiah($value) {
            return 'Rp ' . number_format((float) $value, 0, ',', '.');
        }
    }
}
