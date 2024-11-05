<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Room;
use App\Models\Course;
use App\Models\Logged;

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
        // $loggedInUsers = User::whereHas('tokens', function ($query) {
        //     $query->where('expires_at', '>=', now())->orWhereNull('expires_at');
        // })->get();
        //$total = $loggedInUsers->count();
        $loggeds = Logged::count();
        $courses = Course::paginate(20);
        
        return view('logged',compact('loggeds', 'courses'));
    }

    public function getAttendanceReport(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'start_date' => 'date',
            'end_date' => 'date|after_or_equal:start_date'
        ]);

        $attendances = Attendance::where('course_id', $validated['course_id'])
            ->whereBetween('attendance_date', [
                $validated['start_date'] ?? now()->startOfMonth(),
                $validated['end_date'] ?? now()->endOfMonth()
            ])
            ->get();

        return response()->json($attendances);
    }
}
