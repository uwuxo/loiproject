<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class LoginNewController extends Controller
{
        /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    
    public function login(){
        return view('users.login');
    }

    public function loginOnPage(Request $request)
    {
        // Validate dữ liệu form
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Cố gắng đăng nhập người dùng
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user= Auth::user();
            if($user->hasRole('super-admin'))
            return redirect()->route('dashboard');
            else{
                return redirect()->route('show.user');
            }
        }

        // Đăng nhập không thành công, quay lại với lỗi
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');        
    }
}
