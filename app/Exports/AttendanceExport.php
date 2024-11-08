<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use App\Models\Attendance;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AttendanceExport implements FromCollection, WithHeadings, WithMapping
{
    protected $filters;

    public function __construct(array $filters)
    {
        $this->filters = $filters;
    }

    public function collection()
    {
        $query = Attendance::query();

        if(isset($this->filters['search']) && !empty($this->filters['search']))
        $query->whereRelation('user', 'name', 'like', '%' . $this->filters['search'] . '%');
        if (isset($this->filters['course_id']) && !empty($this->filters['course_id']))
        $query->where('course_id', $this->filters['course_id']);
        if (isset($this->filters['room_id']) && !empty($this->filters['room_id']))
        $query->where('room_id', $this->filters['room_id']);
        $query->whereBetween('attendance_date', [
            $this->filters['start_date'] ?? now()->startOfMonth(),
            $this->filters['end_date'] ?? now()->endOfMonth()
        ]);
        $attendances = $query->get();

        return $attendances;
    }


    public function headings(): array
    {
        return [
            'User Name',
            'UID',
            'Course',
            'Rooms',
            'Date',
            'Status'
        ];
    }

    public function map($attendance): array
    {
        return [
            $attendance->user->name ?? 'N/A',
            $attendance->user->username ?? 'N/A',
            $attendance->course->name ?? 'N/A',
            $attendance->room->name ?? 'N/A',
            $attendance->attendance_date,
            ($attendance->check_in_time && $attendance->check_out_time) ? 'Complete' : 'Incomplete',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]], // Header row styling

            'A1:I1' => [
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'color' => ['argb' => 'FFE9E9E9']
                ]
            ],
        ];
    }
}
