<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use PhpParser\Builder\Function_;
use Spatie\Permission\Traits\HasRoles;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'status',
        'type',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    
    public Function  courses(): BelongsToMany
    {
        return $this->belongsToMany(Course::class);
    }

    public Function  rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public Function  logged(){
        return $this->hasMany(Logged::class);
    }

    public function canLoginRoom($room)
    {
        $now = Carbon::now();
        $today = strtolower($now->format('l')); // get current day of week
        
        // Kiểm tra tất cả các khóa học của user
        $courses = $this->courses()->where('status', 1)->whereRelation('rooms', function($query) use ($room) {
            $query->where('name', $room);
                 //->where('status', 1);
        })->get();
        if(!empty($courses)) {
            foreach ($courses as $course) {
                // Kiểm tra ngày trong khoảng khóa học
                if ($now->between($course->start_date, $course->end_date)) {
                    // Kiểm tra lịch học của ngày hiện tại
                    $schedule = $course->schedule[$today] ?? null;
                    //return $schedule;
                    if ($schedule['start_time']) {
                        $startTime = Carbon::createFromTimeString($schedule['start_time']);
                        $endTime = Carbon::createFromTimeString($schedule['end_time']);
                        
                        // Nếu thời gian hiện tại nằm trong khoảng thời gian của bất kỳ khóa học nào
                        if ($now->between($startTime, $endTime)) {
                            $token = $this->createToken('laraval_api_token')->plainTextToken;
                            $this->logged()->create([
                                'user_id' => $this->id,
                                'course_id' => $course->id,
                                'tokenable_id' => $this->id,
                                'room_id' => $course->rooms()->where('name', $room)->first()->id,
                                'created_at' => Carbon::now()
                            ]);
                            $this->checkIn($this, $course->id, $course->rooms()->where('name', $room)->first()->id, $token);
                            return $token;
                        }
                    }
                }
            }
        }
        
        return false;
    }

    public function checkIn($user, $course, $room = null, $token = null)
    {
        $attendance = Attendance::updateOrCreate(
            [
                'user_id' => $user->id,
                'course_id' => $course,
                'room_id' => $room,
                'attendance_token' => $token,
                'attendance_date' => now()->toDateString()
            ],
            [
                'status' => 'present',
                'check_in_time' => now()->toTimeString()
            ]
        );

        return true;
    }

    
}
