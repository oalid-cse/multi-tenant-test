<?php

namespace App\Helpers;

use App\Models\Tenant;

class TenantHelper
{
    public static function getCurrentTenant()
    {
        $host = request()->getHost();
        $subdomain = explode('.', $host)[0];

        // Find tenant
        $tenant = Tenant::where('subdomain', $subdomain)->first();
        if (empty($tenant)) {
            abort(404, "Invalid Tenant!");
        }

        return $tenant;

    }
}
