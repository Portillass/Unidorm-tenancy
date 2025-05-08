<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PotentialClient;

class PotentialClientController extends Controller
{
    public function subscribe(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'city' => 'required|string|max:255',
            'payment_method' => 'required|string|max:255',
            'plan_type' => 'required|string|max:255',
        ]);

        try {
            // Create a new potential client record in the database
            $potentialClient = PotentialClient::create($validatedData);

            // Return a success response with the created potential client data
            return response()->json(['message' => 'Subscription successful', 'data' => $potentialClient], 200);
        } catch (\Exception $e) {
            // Return an error response if something goes wrong
            return response()->json(['message' => 'Error: '.$e->getMessage()], 500);
        }
    }

    public function index()
    {
        // Fetch all potential clients from the database
        $potentialClients = PotentialClient::paginate(10); // Example: paginate with 10 records per page

        // Pass potential clients data to the view
        return view('admin_dashboard.potential_clients', ['potentialClients' => $potentialClients]);
    }

    public function destroy(PotentialClient $potentialClient)
    {
        $potentialClient->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Potential client deleted successfully'
        ]);
    }
    
}
