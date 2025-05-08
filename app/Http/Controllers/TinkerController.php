<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\Subscription;
use Illuminate\Support\Str;
use Stancl\Tenancy\Facades\Tenancy;
use Illuminate\Support\Facades\Mail;
use App\Mail\TenantCreated;
use Stancl\Tenancy\Database\Models\Domain;
use App\Mail\DomainUpdatedMail; 

class TinkerController extends Controller
{
    public function execute(Request $request)
    {
        // Validate the request data
        $request->validate([
            'tenant_city' => 'required',
            'domain' => 'required',
            'user_name' => 'required',
            'user_email' => 'required|email',
            'subscription_plan' => 'required', // Ensure subscription plan is provided
        ]);

        try {
            // Start database transaction
            DB::beginTransaction();

            // Format tenant ID and domain
            $tenantCity = $request->input('tenant_city');
            $tenantId = '_' . $tenantCity;
            $domainName = $request->input('domain');
            $domain = $domainName . '.unidorm.localhost';

            // Log domain creation for debugging
            Log::info('Creating domain: ' . $domain);

            // Create the tenant
            $tenant = Tenant::create(['id' => $tenantId, 'tenant_city' => $tenantCity]);
            $tenant->domains()->create(['domain' => $domain]);

            // Initialize tenant context
            tenancy()->initialize($tenant);

            // Generate a random password
            $randomPassword = Str::random(10);

            // Create the tenant user
            $user = new TenantUser;
            $user->name = $request->input('user_name');
            $user->email = $request->input('user_email');
            $user->role = 'admin'; // Set the role to 'admin'
            $user->password = bcrypt($randomPassword);
            $user->save();

            // Send email to the user with their credentials
            $emailDomain = $domain . ':8000';
            $subscriptionPlan = $request->input('subscription_plan');
            Mail::to($user->email)->send(new TenantCreated($user->name, $user->email, $randomPassword, $emailDomain, $subscriptionPlan));

            // Create the subscription
            $subscription = new Subscription;
            $subscription->plan_type = $request->input('subscription_plan');

            // Set description and monthly price based on the selected plan type
            switch ($subscription->plan_type) {
                case 'Basic Plan':
                    $subscription->description = 'Manage up to 10 dorms';
                    $subscription->monthly_price = 19.99;
                    break;
                case 'Standard Plan':
                    $subscription->description = 'Manage up to 50 dorms';
                    $subscription->monthly_price = 49.99;
                    break;
                case 'Premium Plan':
                    $subscription->description = 'Manage unlimited dorms';
                    $subscription->monthly_price = 99.99;
                    break;
                default:
                    // Handle unknown plan types
                    $subscription->description = 'Plan details not available';
                    $subscription->monthly_price = 0.00;
            }

            // Save the subscription
            $subscription->save();

            // Commit the transaction
            DB::commit();

            // End tenancy context
            tenancy()->end();

            return response()->json(['success' => true, 'message' => 'Tenant, user, and subscription created successfully.', 'password' => $randomPassword]);
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();

            // Log the error message
            Log::error('Error: ' . $e->getMessage());

            return response()->json(['success' => false, 'message' => 'An error occurred while creating the tenant, user, or subscription.']);
        }
    }

    public function fetchTenants()
    {
        try {
            // Fetch all tenants with their associated domains
            $tenants = Tenant::with('domains')->get();

            // Return the tenants data
            return response()->json(['success' => true, 'data' => $tenants]);
        } catch (\Exception $e) {
            // Handle any errors
            $errorMessage = 'Error: ' . $e->getMessage();
            return response()->json(['success' => false, 'message' => $errorMessage]);
        }
    }

    public function deleteTenant($id)
    {
        try {
            // Find tenant by ID and delete it
            $tenant = Tenant::findOrFail($id);
            $tenant->delete();

            return response()->json(['success' => true, 'message' => 'Tenant deleted successfully.']);
        } catch (\Exception $e) {
            // Handle any errors
            $errorMessage = 'Error: ' . $e->getMessage();
            return response()->json(['success' => false, 'message' => $errorMessage]);
        }
    }

    public function updateTenant(Request $request, $id)
    {
        try {
            $decodedId = urldecode($id);
            $tenant = Tenant::with('domains')
                         ->findOrFail($decodedId);
    
            $validated = $request->validate([
                'domains' => 'required|array',
                'domains.*' => [
                    'required',
                    'string',
                    'max:255',
                    function ($attribute, $value, $fail) use ($tenant) {
                        $exists = Domain::where('domain', $value)
                                    ->where('tenant_id', '!=', $tenant->id)
                                    ->exists();
                        
                        if ($exists) {
                            $fail("The domain $value is already taken by another tenant.");
                        }
                    }
                ]
            ]);
    
            DB::transaction(function () use ($tenant, $validated) {
                $currentDomains = $tenant->domains->pluck('domain')->toArray();
                $newDomains = $validated['domains'];
                

                // Domains to add
                $domainsToAdd = array_diff($newDomains, $currentDomains);
                foreach ($domainsToAdd as $domain) {
                    $tenant->domains()->create(['domain' => $domain]);
                    
                    // Initialize tenant context
                    tenancy()->initialize($tenant);
                    
                    try {
                        // Get ALL users for this tenant
                        $users = \App\Models\User::all();
                        
                        if ($users->isNotEmpty()) {
                            // Send to all users
                            Mail::to($users->pluck('email')->toArray())
                                ->send(new DomainUpdatedMail($domain, $tenant));
                        } else {
                            // Fallback to system admin if no users exist
                            Log::warning("No users found for tenant {$tenant->id} when adding domain {$domain}");
                            
                            if (config('mail.fallback_admin')) {
                                Mail::to(config('mail.fallback_admin'))
                                    ->send(new DomainUpdatedMail($domain, $tenant, true));
                            }
                        }
                    } catch (\Exception $e) {
                        Log::error("Failed to send domain update email for {$domain}: " . $e->getMessage());
                    } finally {
                        tenancy()->end();
                    }
                }

                
                // Domains to remove
                $domainsToRemove = array_diff($currentDomains, $newDomains);
                if (!empty($domainsToRemove)) {
                    $tenant->domains()->whereIn('domain', $domainsToRemove)->delete();
                }
            });
    
            return response()->json([
                'success' => true,
                'message' => 'Tenant updated successfully',
                'tenant' => $tenant->fresh('domains')
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getTenant($id)
{
    try {
        // URL decode the ID first
        $decodedId = urldecode($id);
        
        // Find tenant by ID with its domains
        $tenant = Tenant::with('domains')
                       ->where('id', $decodedId)
                       ->firstOrFail();
        
        return response()->json([
            'success' => true,
            'tenant' => [
                'id' => $tenant->id,
                'domains' => $tenant->domains->map(function ($domain) {
                    return ['domain' => $domain->domain];
                })
            ]
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Tenant not found'
        ], 404);
    }
}
}
 