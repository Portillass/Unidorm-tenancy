<?php

namespace App\Http\Controllers\TenantControllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCredentialsEmail;

class TenantLoginController extends Controller
{
    public function tenantlogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            return redirect()->intended(route('tenantdashboard'))
                ->with('success', 'Login successful!');
        }

        return back()
            ->withInput()
            ->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ]);
    }


    // Add this method to your TenantLoginController
public function viewUsers()
{
    $tenant = tenancy()->tenant;
    $tenantName = $tenant->tenant_city; 
    
    $users = User::all(); // Or any specific query you need
    return view('tenantviews.tenantdash.tenantuser', compact('tenantName','users'));
}

    public function tenantlogout()
    {
        Auth::logout();
        return redirect()->route('tenanthome');
    }

    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'role' => 'nullable|string|in:admin,staff',
        ]);

        // Generate a random password
        $generatedPassword = Str::random(12);

        $user = new User([
            'name' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($generatedPassword),
            'role' => $request->role,

        ]);

        $user->save();

        // Send the credentials to the provided email
        Mail::to($request->email)->send(new UserCredentialsEmail($request->username, $request->email, $generatedPassword, $request->role));

        return redirect()->route('tenantdashboard')->with('success', 'User added successfully. Credentials sent to the provided email.');
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => "required|string|email|max:255|unique:users,email,$id",
            'role' => 'nullable|string|in:admin,staff',
        ]);

        $user = User::findOrFail($id);
        $user->name = $request->username;
        $user->email = $request->email;
        $user->role = $request->role;
        $user->save();

        return response()->json(['message' => 'User updated successfully.']);
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(['message' => 'User deleted successfully.']);
    }

}
