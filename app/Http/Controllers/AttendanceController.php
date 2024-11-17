<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Course;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Exports\AttendanceExport;
use App\Models\User;
use Maatwebsite\Excel\Facades\Excel;

class AttendanceController extends Controller
{

    public function getRoom(Request $request) {

        $course = Course::find($request->course_id);

        if(auth()->user()->type == 'teacher'){
            $course = User::find(auth()->user()->id)->courses()->find($request->course_id);
        }

        $rooms = $course->rooms()->get();

        return response()->json($rooms);
    }
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
        if ($request->has('start_date') && $request->has('end_date')) {
            $validated = $request->validate([
                'search' => 'nullable|string',
                'course_id' => 'nullable|exists:courses,id',
                'room_id' => 'nullable|exists:rooms,id',
                'start_date' => 'required|date',
                'end_date' => 'required|date|after_or_equal:start_date',
                'range' => 'nullable'
            ]);

            $query =  Attendance::query();

            if ($validated['search']) {
                $query->whereRelation('user', 'name', 'like', '%' . $validated['search'] . '%');
            }

            if ($validated['course_id']) {
                $query->where('course_id', $validated['course_id']);
            }

            if ($validated['room_id']) {
                $query->where('room_id', $validated['room_id']);
            }

            $query->whereBetween('attendance_date', [
                $validated['start_date'] ?? now()->startOfMonth(),
                $validated['end_date'] ?? now()->endOfMonth()
            ]);

            $attendances = $query->get();
        }

        $courses = Course::select('id', 'name')->get();
        $rooms = Room::select('id', 'name')->get();
        if(auth()->user()->type == 'teacher'){
            $courses = User::find(auth()->user()->id)->courses()->select('id', 'name')->get();
        }

        

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
            'search' => 'nullable|string',
            'course_id' => 'nullable|exists:courses,id',
            'room_id' => 'nullable|exists:rooms,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'range' => 'nullable'
        ]);

        $filename = sprintf(
            'attendance_report_%s_to_%s.xlsx',
            Carbon::parse($validated['start_date'] ?? now()->startOfMonth())->format('Y-m-d'),
            Carbon::parse($validated['end_date'] ?? now()->endOfMonth())->format('Y-m-d')
        );

        return Excel::download(new AttendanceExport($validated), $filename);
    }
}
