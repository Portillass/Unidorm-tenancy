<?php

namespace App\Http\Controllers\TenantControllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscription;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    public function index()
    {
        try {
            $subscriptions = Subscription::all();
            return response()->json($subscriptions);
        } catch (\Exception $e) {
            Log::error($e);
            return response()->json(['error' => 'Unable to fetch subscriptions.'], 500);
        }
    }
}
