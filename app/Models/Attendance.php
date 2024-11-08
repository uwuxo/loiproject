<?php

// app/Models/Attendance.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attendance extends Model
{
    protected $fillable = [
        'user_id', 'course_id', 'attendance_date', 
        'status', 'check_in_time', 'check_out_time', 'note', 'room_id',
        'attendance_token'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function scopeFilter($query, array $filters)
    {
        $query->when($filters['course_id'] ?? false, function ($query, $course_id) {
            return $query->where(function ($query) use ($course_id) {
                $query->where('course_id', $course_id);
            });
        });

        $query->when($filters['room_id'] ?? false, function ($query, $room_id) {
            return $query->where('room_id', $room_id);
        });

        $query->when($filters['attendance_date'] ?? false, function ($query, $date) {
            return $query->whereBetween('attendance_date', [
                $filters['start_date'] ?? now()->startOfMonth(),
                $filters['end_date'] ?? now()->endOfMonth()
            ]);
        });
    }
}
