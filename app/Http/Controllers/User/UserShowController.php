<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserShowController extends Controller
{
    public function show()
    {
        // Phân trang với mỗi trang có 10 người dùng
        $user = Auth::user();
        return view('backend.pages.users.show', compact('user'));
    }

}

