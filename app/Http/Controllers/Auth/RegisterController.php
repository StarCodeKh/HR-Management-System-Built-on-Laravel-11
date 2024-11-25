<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Carbon\Carbon;
use Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    /** Show the registration page */
    public function register()
    {
        $roles = DB::table('role_type_users')->get();
        return view('auth.register', compact('roles'));
    }

    /** Store New User */
    public function storeUser(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'role_name' => 'required|string',
            ]);
    
            // Create the user
            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role_name' => $request->role_name,
                'avatar' => $request->input('image', 'default_avatar.jpg'),
                'join_date' => Carbon::now()->format('Y-m-d'),
            ]);
    
            // Success message and redirect
            session()->flash('success', 'Account created successfully :)');
            return redirect()->route('login');
        } catch (\Exception $e) {
            Log::error('Error in storeUser: ' . $e->getMessage());
            session()->flash('error', 'Failed to Create Account. Please try again.');
            return redirect()->back()->withInput();
        }
    }
    
    
}