<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
//use Spatie\Permission\Models\Permission;

use App\Models\Course;

class RegisterNewController extends Controller
{
    public function registerShow(){
        $groups = Course::select('id','name')->get();
        $roles = Role::all();
        return view('backend.pages.users.create', compact('groups','roles'));
    }

    public function register(Request $request)
    {
        // Validate form
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|min:4|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'type' => 'required',
            //'allowed_days' => ['required', 'array'], // Validate rằng user đã chọn các ngày
        ]);

        //$allowedDays = json_encode($request->allowed_days);
        // Create user
        $user = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'status' => $request->status,
            'type' => $request->type,
            'password' => Hash::make($request->password),
            //'allowed_days' => $allowedDays, // Lưu danh sách ngày được chọn
        ]);

        if($user && !empty($request->groups)){
            $user->courses()->attach($request->groups);
        }
        
        if($user && !empty($request->roles)){
        $user->assignRole($request->roles);
        }

        if(Auth::user()->super){
            return redirect()->route('users.index')->with('success', 'Profile create successful.');
        }else
        return redirect()->route('users.login')->with('success', 'Registration successful! You can now log in.');
        // Redirect or login the user
        
    }
}
