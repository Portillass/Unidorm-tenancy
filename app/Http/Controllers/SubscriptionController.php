<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PotentialClient;

class SubscriptionController extends Controller
{
    public function subscribeToPlan(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:potential_clients,email',
            'city' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
            'plan_type' => 'required|string|max:255',
        ]);
    
        try {
            // Handle subscription logic here (e.g., create a new potential client record)
            $potentialClient = PotentialClient::create([
                'name' => $request->input('name'),
                'email' => $request->input('email'),
                'city' => $request->input('city'),
                'payment_method' => $request->input('payment_method'),
                'plan_type' => $request->input('plan_type'),
                // No need to include status and tenant_id as they are not in the form
            ]);
    
            // Return a JSON response indicating success with a 200 status code
            return response()->json([
                'success' => true,
                'message' => 'Subscription successful!',
                'data' => $potentialClient, // Return the created potential client data
            ], 200);
    
        } catch (\Exception $e) {
            // Return a JSON response indicating failure with a 500 status code
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }
}
