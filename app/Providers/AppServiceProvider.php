<?php

namespace App\Providers;

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
        // ✅ Mencegah error MySQL "Specified key was too long" (pada collation utf8mb4)
        Schema::defaultStringLength(191);

        // ✅ Pakai Bootstrap 5 untuk pagination
        Paginator::useBootstrapFive();

        // ✅ (Opsional) Set timezone aplikasi
        date_default_timezone_set('Asia/Jakarta');

        // ✅ (Opsional) Format locale (misalnya untuk Carbon tanggal)
        setlocale(LC_TIME, 'id_ID.UTF-8');
    }
}
