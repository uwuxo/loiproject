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
        return Attendance::where('course_id', $this->filters['course_id'])
        ->where('room_id', $this->filters['room_id'])
        ->whereBetween('attendance_date', [
            $this->filters['start_date'] ?? now()->startOfMonth(),
            $this->filters['end_date'] ?? now()->endOfMonth()
        ])
            //->with(['course', 'room']) // Assuming you have these relationships
            ->get();
    }


    public function headings(): array
    {
        return [
            'Course',
            'Rooms',
            'Date',
            'Status'
        ];
    }

    public function map($attendance): array
    {
        return [
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
