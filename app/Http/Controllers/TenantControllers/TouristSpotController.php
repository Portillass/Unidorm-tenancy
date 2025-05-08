<?php

namespace App\Http\Controllers\TenantControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Tenant\TouristSpot;
use Illuminate\Support\Facades\Log;

class TouristSpotController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'location' => 'required|string',
                'description' => 'nullable|string',
                'category' => 'nullable|string',
                'opening_hours' => 'nullable|string',
                'entry_fee' => 'nullable|string',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg,JPEG,PNG,JPG,GIF,SVG|max:2048'
            ]);

            if ($request->hasFile('image')) {
                $data['image'] = $this->storeImage($request);
                if (!$data['image']) {
                    return redirect()->back()->with('error', 'Failed to upload image.')->withInput();
                }
            }

            TouristSpot::create($data);

            return redirect()->back()->with('success', 'Item added successfully!');
        } catch (\Exception $e) {
            Log::error('Error storing tourist spot: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to add Item. Please try again.')->withInput();
        }
    }

    public function index(Request $request)
    {
        try {
            $tenant = tenancy()->tenant;
            $tenantName = $tenant->tenant_city; 

            $search = $request->input('search');

            $touristSpots = TouristSpot::when($search, function ($query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('location', 'LIKE', "%{$search}%")
                    ->orWhere('description', 'LIKE', "%{$search}%")
                    ->orWhere('category', 'LIKE', "%{$search}%")
                    ->orWhere('opening_hours', 'LIKE', "%{$search}%")
                    ->orWhere('entry_fee', 'LIKE', "%{$search}%");
            })->get();

            return view('tenantviews.tenantdash.tenanttourlist', compact('touristSpots', 'tenantName'));
        } catch (\Exception $e) {
            Log::error('Error fetching tourist spots: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load Item. Please try again later.');
        }
    }

    public function edit(TouristSpot $touristSpot)
    {
        try {
            return view('tenantviews.tenantdash.edittouristspot', compact('touristSpot'));
        } catch (\Exception $e) {
            Log::error('Error loading edit form: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Unable to load the edit form.');
        }
    }

    public function update(Request $request, TouristSpot $touristSpot)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string',
                'location' => 'required|string',
                'description' => 'nullable|string',
                'category' => 'nullable|string',
                'opening_hours' => 'nullable|string',
                'entry_fee' => 'nullable|numeric',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048'
            ]);

            if ($request->hasFile('image')) {
                $data['image'] = $this->storeImage($request);
                if (!$data['image']) {
                    return redirect()->back()->with('error', 'Failed to upload image.')->withInput();
                }
            }

            $touristSpot->update($data);

            return redirect()->route('tenantlist')->with('success', 'Item updated successfully!');
        } catch (\Exception $e) {
            Log::error('Error updating tourist spot: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to update Item . Please try again.')->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $touristSpot = TouristSpot::findOrFail($id);
            $touristSpot->delete();

            return redirect()->back()->with('success', 'Tourist spot deleted successfully!');
        } catch (\Exception $e) {
            Log::error('Error deleting tourist spot: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while deleting the tourist spot. Please try again.');
        }
    }

    public function show($id)
    {
        try {
            $touristSpot = TouristSpot::findOrFail($id);
            return response()->json($touristSpot);
        } catch (\Exception $e) {
            Log::error('Error fetching tourist spot details: ' . $e->getMessage());
            return response()->json(['error' => 'Item not found'], 404);
        }
    }

    private function storeImage(Request $request): ?string
    {
        try {
            $image = $request->file('image');
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $path = public_path('storage/visitor/image/' . $filename);

            $image->move(public_path('storage/visitor/image'), $filename);

            return $filename;
        } catch (\Exception $e) {
            Log::error('Image upload failed: ' . $e->getMessage());
            return null;
        }
    }
}