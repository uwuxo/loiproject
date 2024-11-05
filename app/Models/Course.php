<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Carbon\Carbon;

class Course extends Model
{
    use HasFactory;
    protected $fillable = ['name','status','start_date','end_date','description','schedule'];
    protected $casts = [
        'schedule' => 'array',
    ];

    public function  users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    public function  attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function  loggeds()
    {
        return $this->hasMany(Logged::class);
    }

    // public function rooms(){
    //     return $this->hasMany(Room::class);
    // }
    public Function  rooms(): BelongsToMany
    {
        return $this->belongsToMany(Room::class);
    }

    public function validateScheduleConflict($excludeCourseId = null, $room = null, $roomCourses = null)
    {
        // Lấy tất cả các khóa học trừ khóa học hiện tại (nếu có)
        $query = self::query();
        if ($excludeCourseId) {
            $query->where('id', '!=', $excludeCourseId);
            if (!empty($room)) {
                $query->whereRelation('rooms', function($query) use ($room) {
                    $query->whereIn('id', $room);
                });
            }
        }
        if ($roomCourses) {
            $query->whereIn('id', $roomCourses);
        }
        
        $existingCourses = $query->get();

        foreach ($existingCourses as $course) {
            // Kiểm tra xem các khóa học có trùng ngày không
            if ($this->hasDateOverlap($course)) {
                // Nếu có trùng ngày, kiểm tra chi tiết từng ngày trong tuần
                if ($this->hasTimeOverlap($course)) {
                    return [
                        'hasConflict' => true,
                        'conflictWith' => [
                            'id' => $course->id,
                            'name' => $course->name,
                            'start_date' => Carbon::parse($course->start_date)->format('Y-m-d'),
                            'end_date' => Carbon::parse($course->end_date)->format('Y-m-d')
                        ]
                    ];
                }
            }
        }

        return ['hasConflict' => false];
    }

    private function hasDateOverlap($course)
    {
        return ($this->start_date <= $course->end_date && 
                $this->end_date >= $course->start_date);
    }

    private function hasTimeOverlap($course)
    {
        foreach ($this->schedule as $day => $newTimes) {
            if (isset($course->schedule[$day])) {
                $newStartTime = strtotime($newTimes['start_time']);
                $newEndTime = strtotime($newTimes['end_time']);
                $existingStartTime = strtotime($course->schedule[$day]['start_time']);
                $existingEndTime = strtotime($course->schedule[$day]['end_time']);

                if ($this->isTimeOverlapping(
                    $newStartTime, 
                    $newEndTime,
                    $existingStartTime, 
                    $existingEndTime
                )) {
                    return true;
                }
            }
        }
        return false;
    }

    private function isTimeOverlapping($start1, $end1, $start2, $end2)
    {
        return ($start1 < $end2 && $end1 > $start2);
    }

    // public function validateScheduleConflict($newCourse)
    // {
    //     $existingCourses = Course::all();

    //     foreach ($existingCourses as $course) {
    //         // Kiểm tra xem các khóa học có trùng ngày không
    //         if (
    //             ($newCourse->start_date >= $course->start_date && 
    //              $newCourse->start_date <= $course->end_date) ||
    //             ($newCourse->end_date >= $course->start_date && 
    //              $newCourse->end_date <= $course->end_date)
    //         ) {
    //             // Kiểm tra chi tiết lịch học
    //             foreach ($newCourse->schedule as $day => $times) {
    //                 if (isset($course->schedule[$day])) {
    //                     $newStartTime = strtotime($times['start_time']);
    //                     $newEndTime = strtotime($times['end_time']);
    //                     $existingStartTime = strtotime($course->schedule[$day]['start_time']);
    //                     $existingEndTime = strtotime($course->schedule[$day]['end_time']);

    //                     if (
    //                         ($newStartTime >= $existingStartTime && $newStartTime <= $existingEndTime) ||
    //                         ($newEndTime >= $existingStartTime && $newEndTime <= $existingEndTime)
    //                     ) {
    //                         return false; // Có conflict
    //                     }
    //                 }
    //             }
    //         }
    //     }

    //     return true; // Không có conflict
    // }
    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = Carbon::parse($value)->format('Y-m-d H:i:s');
    }
}
