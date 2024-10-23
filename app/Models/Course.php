<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Course extends Model
{
    use HasFactory;
    protected $fillable = ['name','status','start_date','end_date','description'];

    public function  users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function rooms(){
        return $this->hasMany(Room::class);
    }
}
