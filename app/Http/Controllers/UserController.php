<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index()
    {
        // Phân trang với mỗi trang có 10 người dùng
        $users = User::paginate(10);

        return view('users.index', compact('users'));
    }

    public function registerShow(){
        return view('users.register');
    }

    public function register(Request $request)
    {
        // Validate form
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Redirect or login the user
        return redirect()->route('users.index')->with('success', 'Registration successful! You can now log in.');
    }

    public function login(){
        return view('users.login');
    }

    public function loginOnPage(Request $request)
    {
        // Kiểm tra xem ngày hiện tại có phải là Thứ Hai, Thứ Tư, Thứ Sáu không
        $allowedDays = [1, 3, 5]; // 1: Thứ Hai, 3: Thứ Tư, 5: Thứ Sáu (Carbon định nghĩa)
        $currentDay = Carbon::now()->dayOfWeek; // Lấy ngày trong tuần (0: Chủ Nhật, 1: Thứ Hai, 2: Thứ Ba, ...)

        if (!in_array($currentDay, $allowedDays)) {
            // Nếu ngày hiện tại không phải là ngày được phép
            return back()->withErrors([
                'day' => 'You can only log in on Monday, Wednesday, and Friday.',
            ]);
        }

        // Validate dữ liệu form
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        // Cố gắng đăng nhập người dùng
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            // Đăng nhập thành công, chuyển hướng đến trang mong muốn
            return redirect()->route('users.index');
        }

        // Đăng nhập không thành công, quay lại với lỗi
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');        
    }
}

