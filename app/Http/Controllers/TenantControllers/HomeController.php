<?php

namespace App\Http\Controllers\TenantControllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\Tenant\TouristSpot;
use Stancl\Tenancy\Facades\Tenancy;

class HomeController extends Controller
{
    public function showDashboard()
    {
        try {
            // Initialize tenancy to get the current tenant
            $tenant = tenancy()->tenant;
            $tenantName = $tenant->tenant_city;
    
            // Fetch all tourist spots
            $touristSpots = TouristSpot::all();
    
            // Pass the tenant name and tourist spots to the home view
            return view('tenantviews.tenanthome.home', compact('touristSpots', 'tenantName'));
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error: ' . $e->getMessage());
    
            return redirect()->back()->with('error', 'Unable to load tourist spots.');
        }
    }

}
