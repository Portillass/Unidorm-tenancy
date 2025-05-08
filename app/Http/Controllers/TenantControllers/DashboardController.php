<?php

namespace App\Http\Controllers\TenantControllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Tenant;
use Stancl\Tenancy\Facades\Tenancy;

class DashboardController extends Controller
{
    public function showDashboard()
    {
        try {
            // Initialize tenancy to get the current tenant
            $tenant = tenancy()->tenant;
            $tenantName = $tenant->tenant_city; // Assuming 'name' is a field in your Tenant model

            // Pass the tenant name to the view
            return view('tenantviews.tenantdash.tenantdashboard', compact('tenantName'));
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Unable to load dashboard.');
        }
    }
}
