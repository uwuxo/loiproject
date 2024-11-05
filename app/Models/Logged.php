<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Logged extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'course_id',
        'user_id',
        'tokenable_id'
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
