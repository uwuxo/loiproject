<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Attendance;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $user = $request->user();
        $this->checkOut($user, $request->bearerToken());
        $request->user()->currentAccessToken()->delete();
    }

    public function checkOut($user, $token)
    {
        $attendance = Attendance::where([
            'user_id' => $user->id,
            //'course_id' => $course,
            //'room_id' => $room,
            'attendance_token' => $token,
            'attendance_date' => now()->toDateString()
        ])->first();

        if ($attendance) {
            $attendance->update([
                'check_out_time' => now()->toTimeString(),
                'status' => 'present'
            ]);

            return true;
        }

        return false;
    }
}
