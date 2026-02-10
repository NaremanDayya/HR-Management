<?php

namespace App\Livewire;

use App\Exports\EmployeeTemplateExport;
use Livewire\Component;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeTemplateDownload extends Component
{
    public function downloadTemplate()
    {
        return Excel::download(new EmployeeTemplateExport, 'employee_import_template.csv', \Maatwebsite\Excel\Excel::CSV, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function downloadExcelTemplate()
    {
        return Excel::download(new EmployeeTemplateExport, 'employee_import_template.xlsx');
    }

    public function render()
    {
        return view('livewire.employee-template-download');
    }
}
