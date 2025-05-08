<?php

namespace App\Http\Controllers\TenantControllers;

use App\Http\Controllers\Controller;
use App\Models\Tenant\Order;
use App\Models\Tenant\TouristSpot;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $tenant = tenancy()->tenant;
            $tenantName = $tenant->tenant_city; 
            
            $query = Order::with('touristspot')
                ->select('name', 'phone')
                ->selectRaw('MAX(created_at) as latest_order_date')
                ->groupBy('name', 'phone')
                ->orderBy('latest_order_date', 'desc');
    
            if ($request->has('search')) {
                $searchTerm = $request->input('search');
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'like', "%$searchTerm%")
                      ->orWhere('phone', 'like', "%$searchTerm%")
                      ->orWhere('status', 'like', "%$searchTerm%")
                      ->orWhereHas('touristspot', function($q) use ($searchTerm) {
                          $q->where('name', 'like', "%$searchTerm%");
                      });
                });
            }
    
            $customers = $query->get()
                ->map(function ($group) {
                    return Order::with('touristspot')
                        ->where('name', $group->name)
                        ->where('phone', $group->phone)
                        ->latest()
                        ->first();
                });
                
            return view('tenantviews.tenantdash.tenanttrackorder', compact('tenantName', 'customers'));
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load dashboard.');
        }
    }
    
    public function getCustomerOrders($name)
    {
        $orders = Order::with('touristspot')
            ->where('name', $name)
            ->orderBy('created_at', 'desc')
            ->get();
    
        return response()->json($orders);
    }

    public function search(Request $request)
    {
        $searchTerm = $request->input('search');
        
        $customers = Order::with('touristspot')
            ->where(function($query) use ($searchTerm) {
                $query->where('name', 'like', "%$searchTerm%")
                      ->orWhere('phone', 'like', "%$searchTerm%")
                      ->orWhere('order_type', 'like', "%$searchTerm%")
                      ->orWhere('status', 'like', "%$searchTerm%")
                      ->orWhereHas('touristspot', function($q) use ($searchTerm) {
                          $q->where('name', 'like', "%$searchTerm%");
                      });
            })
            ->latest()
            ->get()
            ->unique('name'); 
        $tenant = tenancy()->tenant;
        $tenantName = $tenant->tenant_city;

        return view('tenantviews.tenantdash.tenanttrackorder', compact('tenantName', 'customers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $touristSpots = TouristSpot::all();
        return view('tenant.orders.create', compact('touristSpots'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'touristspot_id' => 'required|exists:touristspot,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'quantity' => 'nullable|integer|min:1',
            'order_type' => 'required|string|max:255',
            'subscriptionType' => 'required|string|max:255',
            'total_price' => 'nullable|numeric|min:0',
            'status' => 'sometimes|string|in:pending,confirmed,completed,cancelled' 
        ]);

        // Set default status if not provided
        $validated['status'] = $validated['status'] ?? 'pending';

        Order::create($validated);

        return redirect()->back()->with('success', 'Order submitted!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        return view('tenant.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        $touristSpots = TouristSpot::all();
        return view('tenant.orders.edit', compact('order', 'touristSpots'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'touristspot_id' => 'required|exists:tourist_spots,id',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'quantity' => 'required|integer|min:1',
            'order_type' => 'required|string|max:255',
            'subscriptionType' => 'required|string|max:255',
            'total_price' => 'required|numeric|min:0',
            'status' => 'required|string|in:pending,confirmed,completed,cancelled' // Added status validation
        ]);

        $order->update($validated);

        return redirect()->route('orders.index')->with('success', 'Order updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Order $order)
    {
        $order->delete();
        return redirect()->route('orders.index')->with('success', 'Order deleted successfully.');
    }

    /**
     * Update order status
     */
    public function updateStatus(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
    
        $validated = $request->validate([
            'status' => 'required|string|in:pending,confirmed,completed,cancelled'
        ]);
    
        $order->update(['status' => $validated['status']]);

        Log::info('Updating order status for ID: ' . $order->id);
    
        return response()->json(['success' => true, 'message' => 'Status updated successfully']);
    }
    
}