<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Course;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function checkIn(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $attendance = Attendance::updateOrCreate(
            [
                'user_id' => auth()->id(),
                'course_id' => $validated['course_id'],
                'attendance_date' => now()->toDateString()
            ],
            [
                'status' => 'present',
                'check_in_time' => now()->toTimeString()
            ]
        );

        return response()->json([
            'message' => 'Điểm danh thành công',
            'attendance' => $attendance
        ]);
    }

    public function checkOut(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
        ]);

        $attendance = Attendance::where([
            'user_id' => auth()->id(),
            'course_id' => $validated['course_id'],
            'attendance_date' => now()->toDateString()
        ])->first();

        if ($attendance) {
            $attendance->update([
                'check_out_time' => now()->toTimeString(),
                'status' => 'present'
            ]);

            return response()->json([
                'message' => 'Checkout thành công',
                'attendance' => $attendance
            ]);
        }

        return response()->json([
            'message' => 'Chưa điểm danh check-in'
        ], 400);
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
