<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules()
    {
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        $rules = [
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date'//'required|date|after_or_equal:start_date',
        ];

        foreach ($days as $day) {
            $rules["schedule.$day.start_time"] = [
                'nullable',
                function ($attribute, $value, $fail) use ($day) {
                    $endTime = $this->input("schedule.$day.end_time");
                    if ($value && !$endTime) {
                        $fail('The end time is required for '.$day);
                    }
                }
            ];

            $rules["schedule.$day.end_time"] = [
                'nullable',
                function ($attribute, $value, $fail) use ($day) {
                    $startTime = $this->input("schedule.$day.start_time");
                    if (!$startTime && $value) {
                        $fail('The start time is required for '.$day);
                    }
                    if ($startTime && $value && $value <= $startTime) {
                        $fail('The end time must be after the start time for '.$day);
                    }
                }
            ];
        }

        return $rules;
    }
}
