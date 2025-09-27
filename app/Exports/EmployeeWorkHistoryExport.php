<?php

namespace App\Exports;

use App\Models\EmployeeWorkHistory;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class EmployeeWorkHistoryExport implements FromQuery, WithHeadings, WithMapping, WithStyles
{
    private $filters;

    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    public function query()
    {
        $query = EmployeeWorkHistory::with('employee.user');

        if (!empty($this->filters['employee_id'])) {
            $query->where('employee_id', $this->filters['employee_id']);
        }

        if (!empty($this->filters['start_date'])) {
            $query->whereDate('start_date', '>=', $this->filters['start_date']);
        }

        if (!empty($this->filters['end_date'])) {
            $query->whereDate('end_date', '<=', $this->filters['end_date']);
        }

        if (!empty($this->filters['status'])) {
            if ($this->filters['status'] === 'active') {
                $query->whereNull('end_date');
            } else {
                $query->whereNotNull('end_date');
            }
        }

        return $query->orderBy('start_date', 'desc');
    }

    public function headings(): array
    {
        return [
            'اسم الموظف',
            'تاريخ البدء',
            'تاريخ الانتهاء',
            'المدة (أيام)',
            'المدة (نص)',
            'الحالة',
            'سبب الإيقاف',
            'تاريخ التسجيل'
        ];
    }

    public function map($history): array
    {
        return [
            $history->employee->user->name ?? 'غير محدد',
            $history->start_date->format('Y-m-d'),
            $history->end_date ? $history->end_date->format('Y-m-d') : 'مستمر',
            $history->work_days,
            $history->duration_text,
            $history->is_active ? 'نشط' : 'منتهي',
            $history->stop_reason ?? 'غير محدد',
            $history->created_at->format('Y-m-d H:i')
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']], 'fill' => ['fillType' => 'solid', 'startColor' => ['rgb' => '740e0e']]],
            'A:H' => ['alignment' => ['horizontal' => 'center']],
        ];
    }
}
