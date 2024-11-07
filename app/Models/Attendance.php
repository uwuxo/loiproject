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
}
