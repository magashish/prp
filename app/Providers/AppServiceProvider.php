<?php

namespace App\Providers;

use App\Models\Setting;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}

    public function boot(): void
    {
        // Override mail recipient config with DB settings when available
        try {
            $admin   = Setting::where('key', 'admin_email')->value('value');
            $parking = Setting::where('key', 'parking_company_email')->value('value');

            if ($admin)   config(['mail.admin_email'           => $admin]);
            if ($parking) config(['mail.parking_company_email' => $parking]);
        } catch (\Throwable $e) {
            // settings table may not exist yet (e.g. before migration)
        }
    }
}
