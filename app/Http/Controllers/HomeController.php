<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        return view('home', compact('user'));
    }

    public function detail($id){
        $course = auth()->user()->courses()->find($id);
        return view('detail', compact('course'));
    }

    public function loggedIn(){
        $loggedInUsers = User::whereHas('tokens', function ($query) {
            $query->where('expires_at', '>=', now())->orWhereNull('expires_at');
        })->get();
        return view('logged',compact('loggedInUsers'));
    }
}
