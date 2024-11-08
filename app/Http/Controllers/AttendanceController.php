<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\AttendanceExport;
use Maatwebsite\Excel\Facades\Excel;

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
        $attendances = [];
        if ($request->has('course_id') && $request->has('room_id') && $request->has('start_date') && $request->has('end_date')) {
            $validated = $request->validate([
                'course_id' => 'required|exists:courses,id',
                'room_id' => 'required|exists:rooms,id',
                'start_date' => 'date',
                'end_date' => 'date|after_or_equal:start_date'
            ]);

            $attendances = Attendance::where('course_id', $validated['course_id'])
            ->where('room_id', $validated['room_id'])
            ->whereBetween('attendance_date', [
                $validated['start_date'] ?? now()->startOfMonth(),
                $validated['end_date'] ?? now()->endOfMonth()
            ])
            ->get();
        }

        $courses = Course::select('id', 'name')->get();
        $rooms = Room::select('id', 'name')->get();

        

        //return response()->json($attendances);

        return view('backend.pages.attendances.index', [
            'attendances' => $attendances,
            'courses' => $courses,
            'rooms' => $rooms,
            'validated' => $validated ?? []
        ]);
    }

    public function export(Request $request)
    {
        $validated = $request->validate([
            'course_id' => 'required|exists:courses,id',
            'room_id' => 'required|exists:rooms,id',
            'start_date' => 'date',
            'end_date' => 'date|after_or_equal:start_date'
        ]);

        $filename = sprintf(
            'attendance_report_%s_to_%s.xlsx',
            Carbon::parse($validated['start_date'] ?? now()->startOfMonth())->format('Y-m-d'),
            Carbon::parse($validated['end_date'] ?? now()->endOfMonth())->format('Y-m-d')
        );

        return Excel::download(new AttendanceExport($validated), $filename);
    }
}
