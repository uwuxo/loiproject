<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
    protected $fillable = ['name','allowed_days','start_time','end_time'];

    public function  course() {
        return $this->belongsTo(Course::class);
    }

    public function  logged() {
        return $this->hasMany(Logged::class);
    }
}
