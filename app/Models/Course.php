<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Course extends Model
{
    use HasFactory;
    protected $fillable = ['name','status','start_date','end_date','description'];

    public function  users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    // public function rooms(){
    //     return $this->hasMany(Room::class);
    // }
    public Function  rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class);
    }

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
