<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Room extends Model
{
    use HasFactory;
    protected $fillable = ['name','allowed_days','start_time','end_time'];

    public function  course() {
        return $this->belongsTo(Course::class);
    }

    public Function  courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

    public function  logged() {
        return $this->hasMany(Logged::class);
    }
}
