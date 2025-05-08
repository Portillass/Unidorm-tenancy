<?php

namespace App\Http\Controllers\TenantControllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant;
use Stancl\Tenancy\Facades\Tenancy;

class ProfileController extends Controller
{
    public function show()
    {
        try {
            // Initialize tenancy to get the current tenant
            $tenant = tenancy()->tenant;
            $tenantName = $tenant->tenant_city; // Assuming 'name' is a field in your Tenant model

            // Pass the tenant name to the view
            return view('tenantviews.tenantdash.tenantprofile', compact('tenantName'));
        } catch (\Exception $e) {
            // Log the error
            Log::error('Error: ' . $e->getMessage());

            return redirect()->back()->with('error', 'Unable to load dashboard.');
        }
    }
    
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'current_password' => 'nullable',
            'new_password' => 'nullable|string|min:8|confirmed',
        ]);

        $user = auth()->user();
        $user->name = $request->name;
        $user->email = $request->email;

        if ($request->filled('current_password') && $request->filled('new_password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is incorrect']);
            }
            $user->password = Hash::make($request->new_password);
        }

        $user->save();

        return redirect()->route('tenant.profile')->with('success', 'Profile updated successfully');
    }
}
