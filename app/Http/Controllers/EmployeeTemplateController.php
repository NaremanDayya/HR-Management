<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Employee;
use App\Models\User;
use App\Models\EmployeeWorkHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\FacadesLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class EmployeeTemplateController extends Controller
{
    public function downloadCsv()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $supervisors = User::where('role', 'supervisor')->pluck('name')->toArray();
        $areaManagers = User::where('role', 'area_manager')->pluck('name')->toArray();
        $projects = \App\Models\Project::pluck('name')->toArray();
        // Set headers
        $headers = [
            'A1' => 'الاسم الكامل',
            'B1' => 'اسم صاحب الحساب',
            'C1' => 'اسم البنك',
            'D1' => 'المهنة في الهوية',
            'E1' => 'رقم الهوية',
            'F1' => 'الجنسية',
            'G1' => 'تاريخ الميلاد',
            'H1' => 'العمر',
            'I1' => 'الجنس',
            'J1' => 'مقر الإقامة',
            'K1' => 'الحي السكني',
            'L1' => 'نوع المركبة',
            'M1' => 'موديل المركبة',
            'N1' => 'رقم لوحة المركبة',
            'O1' => 'نوع الشهادة',
            'P1' => 'الحالة الاجتماعية',
            'Q1' => 'عدد أفراد الأسرة',
            'R1' => 'البريد الإلكتروني',
            'S1' => 'رقم الآيبان',
            'T1' => 'رقم الجوال',
            'U1' => 'نوع الجوال',
            'V1' => 'الدور الوظيفي',
            'W1' => 'كلمة المرور',
            'X1' => 'منطقة العمل',
            'Y1' => 'تاريخ الإنضمام',
            'Z1' => 'مقاس التي شيرت',
            'AA1' => 'مقاس البنطال',
            'AB1' => 'مقاس الحذاء',
            'AC1' => 'هل لدى الموظف شهادة صحية',
            'AD1' => 'المشروع',
            'AE1' => 'المشرف',
            'AF1' => 'مدير المنطقة',
            'AG1' => 'الراتب',
            'AH1' => 'مستوى اللغة الإنجليزية',
        ];

        foreach ($headers as $cell => $header) {
            $sheet->setCellValue($cell, $header);
        }

        // Add dropdown validations
        $this->addDropdownValidation($sheet, 'I', 'الجنس', ['ذكر', 'أنثى']);
        $this->addDropdownValidation($sheet, 'U', 'نوع الجوال', ['أندرويد', 'ايفون']);
        $this->addDropdownValidation($sheet, 'V', 'الدور الوظيفي', ['مشرف', 'مدير مشروع', 'مصفف أرفف', 'مدير منطقة']);
        $this->addDropdownValidation($sheet, 'O', 'نوع الشهادة', ['الثانوية العامة', 'دبلوم', 'بكالوريوس', 'ماجستير', 'دكتوراة']);
        $this->addDropdownValidation($sheet, 'P', 'الحالة الاجتماعية', ['أعزب', 'متزوج', 'مطلق', 'أرمل']);
        $this->addDropdownValidation($sheet, 'AH', 'مستوى اللغة الإنجليزية', ['أساسي', 'متوسط', 'متقدم']);
        $this->addDropdownValidation($sheet, 'AC', 'هل لدى الموظف شهادة صحية', ['نعم', 'لا']);
        $this->addDropdownValidation($sheet, 'AD', 'المشروع', $projects);
        $this->addDropdownValidation($sheet, 'AE', 'المشرف', $supervisors);
        $this->addDropdownValidation($sheet, 'AF', 'مدير المنطقة', $areaManagers);
        $exampleProject = !empty($projects) ? $projects[0] : 'مشروع تجريبي';
        $exampleSupervisor = !empty($supervisors) ? $supervisors[0] : 'مشرف تجريبي';
        $exampleAreaManager = !empty($areaManagers) ? $areaManagers[0] : 'مدير منطقة تجريبي';

        // Add example data
        $examples = [
            [
                'A2' => 'أحمد محمد',
                'B2' => 'أحمد محمد',
                'C2' => 'البنك الأهلي',
                'D2' => 'مهندس',
                'E2' => '1234567890',
                'F2' => 'سعودي',
                'G2' => '1990-05-15',
                'H2' => '34',
                'I2' => 'male',
                'J2' => 'الرياض',
                'K2' => 'الملز',
                'L2' => 'سيارة',
                'M2' => '2020',
                'N2' => 'أ ب ج 1234',
                'O2' => 'bachelor',
                'P2' => 'married',
                'Q2' => '4',
                'R2' => 'ahmed@example.com',
                'S2' => 'SA0380000000608010167519',
                'T2' => '0551234567',
                'U2' => 'android',
                'V2' => 'shelf_stacker',
                'W2' => 'password123',
                'X2' => 'المنطقة الشرقية',
                'Y2' => '2024-01-15',
                'Z2' => 'L',
                'AA2' => '32',
                'AB2' => '42',
                'AC2' => '1',
                'AD2' => $exampleProject,
                'AE2' => $exampleSupervisor,
                'AF2' => $exampleAreaManager,
                'AG2' => '5000',
                'AH2' => 'intermediate'
            ],
            [
                'A3' => 'فاطمة أحمد',
                'B3' => 'فاطمة أحمد',
                'C3' => 'الراجحي',
                'D3' => 'معلمة',
                'E3' => '2345678901',
                'F3' => 'سعودية',
                'G3' => '1992-03-10',
                'H3' => '32',
                'I3' => 'أنثى',
                'J3' => 'جدة',
                'K3' => 'الصفا',
                'L3' => '-',
                'M3' => '-',
                'N3' => '-',
                'O3' => 'ماجستير',
                'P3' => 'أعزب',
                'Q3' => '1',
                'R3' => 'fatima@example.com',
                'S3' => 'SA0380000000608010167520',
                'T3' => '0552345678',
                'U3' => 'iphone',
                'V3' => 'supervisor',
                'W3' => 'password123',
                'X3' => 'المنطقة الغربية',
                'Y3' => '2024-02-01',
                'Z3' => 'M',
                'AA3' => '30',
                'AB3' => '38',
                'AC3' => '0',
                'AD3' => !empty($projects[1] ?? null) ? $projects[1] : $exampleProject,
                'AE3' => !empty($supervisors[1] ?? null) ? $supervisors[1] : $exampleSupervisor,
                'AF3' => !empty($areaManagers[1] ?? null) ? $areaManagers[1] : $exampleAreaManager,
                'AG3' => '6000',
                'AH3' => 'متقدم'
            ]
        ];

        foreach ($examples as $example) {
            foreach ($example as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }
        }

        // Auto-size columns for better readability
        foreach (range('A', 'H') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Create writer and save to temporary file
        $writer = new Xlsx($spreadsheet);
        $fileName = 'تمبلت بيانات الموظفين.xlsx';

        ob_start();
        $writer->save('php://output');
        $content = ob_get_clean();

        return response()->streamDownload(function () use ($content) {
            echo $content;
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    private function addDropdownValidation($sheet, $column, $title, $options)
    {
        // Apply dropdown validation to first 100 rows
        for ($row = 2; $row <= 100; $row++) {
            $cell = $column . $row;

            $validation = $sheet->getCell($cell)->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
            $validation->setAllowBlank(false);
            $validation->setShowInputMessage(true);
            $validation->setShowErrorMessage(true);
            $validation->setShowDropDown(true);
            $validation->setErrorTitle('خطأ في الإدخال');
            $validation->setError('القيمة المدخلة غير صالحة. يرجى الاختيار من القائمة.');
            $validation->setPromptTitle('اختيار ' . $title);
            $validation->setPrompt('يرجى الاختيار من القائمة المنسدلة.');
            $validation->setFormula1('"' . implode(',', $options) . '"');
        }
    }
    public function showTemplatePage()
    {
        return view('livewire.employee-template-download');
    }
    public function import(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'employee_file' => 'required|file|mimes:csv,txt,xlsx,xls|max:10240'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()->all()
            ], 422);
        }

        try {
            $file = $request->file('employee_file');
            $extension = $file->getClientOriginalExtension();
            $path = $file->getRealPath();

            if (in_array($extension, ['xlsx', 'xls'])) {
                $spreadsheet = IOFactory::load($path);
                $sheet = $spreadsheet->getActiveSheet();

                $headers = [];
                $dataRows = [];

                // Get headers from first row
                foreach ($sheet->getRowIterator(1, 1) as $row) {
                    foreach ($row->getCellIterator() as $cell) {
                        $headers[] = $cell->getValue();
                    }
                    break;
                }

                // Get data rows starting from row 2
                foreach ($sheet->getRowIterator(2) as $row) {
                    $rowData = [];
                    foreach ($row->getCellIterator() as $cell) {
                        $rowData[] = $cell->getValue();
                    }

                    // Skip empty rows
                    if (!empty(array_filter($rowData, function($value) {
                        return $value !== null && $value !== '' && $value !== '-';
                    }))) {
                        $dataRows[] = $rowData;
                    }
                }
            } else {
                $csvData = array_map('str_getcsv', file($path));
                $headers = $this->removeBom($csvData[0]);
                array_shift($csvData);
                $dataRows = $csvData;
            }

            $imported = 0;
            $errors = [];

            DB::beginTransaction();

            foreach ($dataRows as $rowIndex => $row) {
                // Skip empty rows
                if (empty(array_filter($row, function($value) {
                    return $value !== null && $value !== '' && $value !== '-';
                }))) {
                    continue;
                }

                if (count($row) != count($headers)) {
                    $errors[] = "الصف " . ($rowIndex + 2) . ": عدد الأعمدة غير متطابق. المتوقع: " . count($headers) . ", الموجود: " . count($row);
                    continue;
                }

                $rowData = array_combine($headers, $row);

                // Transform CSV data
                $employeeData = $this->transformCsvData($rowData, $rowIndex + 2);

                // Validate the data
                $validation = $this->validateEmployeeData($employeeData, $rowIndex + 2);

                if ($validation->fails()) {
                    $errors[] = "الصف " . ($rowIndex + 2) . ": " . implode(', ', $validation->errors()->all());
                    continue;
                }

                try {
                    $this->createEmployee($employeeData);
                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = "الصف " . ($rowIndex + 2) . ": " . $e->getMessage();
                    continue;
                }
            }

            if (count($errors) > 0) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'تم استيراد بعض البيانات ولكن هناك أخطاء',
                    'imported_count' => $imported,
                    'error_count' => count($errors),
                    'errors' => $errors
                ], 422);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'تم استيراد جميع الموظفين بنجاح',
                'imported_count' => $imported
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء استيراد الملف: ' . $e->getMessage()
            ], 500);
        }
    }

    private function removeBom($headers)
    {
        return array_map(function($header) {
            return preg_replace('/^\xEF\xBB\xBF/', '', $header);
        }, $headers);
    }

    private function transformCsvData($csvData, $rowNumber)
    {
        Log::info("Raw dates for row {$rowNumber}", [
            'birthday_raw' => $csvData['تاريخ الميلاد'] ?? 'empty',
            'joining_date_raw' => $csvData['تاريخ الإنضمام'] ?? 'empty',
            'birthday_type' => gettype($csvData['تاريخ الميلاد'] ?? 'null'),
            'joining_date_type' => gettype($csvData['تاريخ الإنضمام'] ?? 'null'),
        ]);
        // Handle date formats properly
        $birthday = $this->parseDate($csvData['تاريخ الميلاد'] ?? '');
        $joiningDate = $this->parseDate($csvData['تاريخ الإنضمام'] ?? '');
        $age = $birthday ? $birthday->age : 0;

        if (!$birthday) {
            Log::warning("Failed to parse birthday for row {$rowNumber}: " . ($csvData['تاريخ الميلاد'] ?? 'empty'));
        }

        if (!$joiningDate) {
            Log::warning("Failed to parse joining date for row {$rowNumber}: " . ($csvData['تاريخ الإنضمام'] ?? 'empty'));
        }

        // Handle vehicle model
        $vehicleModel = $csvData['موديل المركبة'];
        if ($vehicleModel === null || $vehicleModel === '' || $vehicleModel === '-') {
            $vehicleModel = 'غير محدد';
        } else {
            $vehicleModel = (string)$vehicleModel;
        }

        // Handle IBAN - clean it properly
        $iban = $csvData['رقم الآيبان'] ?? '';
        $iban = preg_replace('/[^0-9]/', '', $iban);

        // If IBAN starts with SA, remove it and ensure 22 digits
        if (strpos($csvData['رقم الآيبان'], 'SA') === 0) {
            $iban = substr(preg_replace('/[^0-9]/', '', $csvData['رقم الآيبان']), 2);
        }

        // Pad IBAN to 22 digits if needed
        if (strlen($iban) < 22) {
            $iban = str_pad($iban, 22, '0', STR_PAD_RIGHT);
        } elseif (strlen($iban) > 22) {
            $iban = substr($iban, 0, 22);
        }

        // Handle phone number - remove leading 0 if present and ensure 9 digits
        $phoneNumber = $csvData['رقم الجوال'] ?? '';
        $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);
        if (strlen($phoneNumber) === 10 && strpos($phoneNumber, '0') === 0) {
            $phoneNumber = substr($phoneNumber, 1);
        }

        // Handle ID card - ensure it's exactly 10 digits
        $idCard = $csvData['رقم الهوية'] ?? '';
        $idCard = preg_replace('/[^0-9]/', '', $idCard);
        if (strlen($idCard) > 10) {
            $idCard = substr($idCard, 0, 10);
        }

        // Handle numeric fields
        $pantsSize = $this->cleanNumericField($csvData['مقاس البنطال'] ?? 0);
        $shoesSize = $this->cleanNumericField($csvData['مقاس الحذاء'] ?? 0);
        $membersNumber = $this->cleanNumericField($csvData['عدد أفراد الأسرة'] ?? 0);

        // Handle salary
        $salary = $this->cleanNumericField($csvData['الراتب'] ?? 0, true);

        // Handle Tshirt size - ensure it's a string
        $tshirtSize = $csvData['مقاس التي شيرت'] ?? '';
        if (is_numeric($tshirtSize)) {
            $tshirtSize = (string)$tshirtSize;
        }

        // Handle health card - convert to integer for database
        $healthCardValue = $this->extractHealthCardValue($csvData['هل لدى الموظف شهادة صحية']);

        return [
            'name' => $csvData['الاسم الكامل'] ?? '',
            'email' => $csvData['البريد الإلكتروني'] ?? '',
            'password' => $csvData['كلمة المرور'] ?? 'Password123',
            'job' => $csvData['المهنة في الهوية'] ?? '',
            'joining_date' => $joiningDate ? $joiningDate->format('Y-m-d') : '2000-01-01',
            'birthday' => $birthday ? $birthday->format('Y-m-d') : '2000-01-01',
            'bank_name' => $csvData['اسم البنك'] ?? '',
            'age' => $age,
            'owner_account_name' => $csvData['اسم صاحب الحساب'] ?? '',
            'iban' => $iban,
            'vehicle_ID' => $csvData['رقم لوحة المركبة'] ?? 'غير محدد',
            'vehicle_type' => $csvData['نوع المركبة'] ?? 'غير محدد',
            'vehicle_model' => $vehicleModel,
            'residence' => $csvData['مقر الإقامة'] ?? '',
            'residence_neighborhood' => $csvData['الحي السكني'] ?? '',
            'Tshirt_size' => $tshirtSize,
            'pants_size' => $pantsSize,
            'Shoes_size' => $shoesSize,
            'gender' => $this->mapGender($csvData['الجنس'] ?? ''),
            'health_card' => $this->mapHealthStatus($healthCardValue),
            'work_area' => $csvData['منطقة العمل'] ?? '',
            'id_card' => $idCard,
            'phone_number' => $phoneNumber,
            'phone_type' => $this->mapPhoneType($csvData['نوع الجوال'] ?? ''),
            'nationality' => $csvData['الجنسية'] ?? '',
            'role' => $this->mapRole($csvData['الدور الوظيفي'] ?? ''),
            'salary' => $salary,
            'english_level' => $this->mapEnglishLevel($csvData['مستوى اللغة الإنجليزية'] ?? ''),
            'certificate_type' => $this->mapCertificateType($csvData['نوع الشهادة'] ?? ''),
            'marital_status' => $this->mapMaritalStatus($csvData['الحالة الاجتماعية'] ?? ''),
            'members_number' => $membersNumber,
            'project_name' => $csvData['المشروع'] ?? null,
            'supervisor_name' => $csvData['المشرف'] ?? null,
            'area_manager_name' => $csvData['مدير المنطقة'] ?? null,
            'project' => $csvData['المشروع'] ?? null,
            'supervisor' => $csvData['المشرف'] ?? null,
            'area_manager' => $csvData['مدير المنطقة'] ?? null,
        ];
    }

    private function cleanNumericField($value, $isFloat = false)
    {
        if ($value === null || $value === '' || $value === '-') {
            return 0;
        }

        if (is_string($value)) {
            $value = preg_replace('/[^0-9.]/', '', $value);
        }

        return $isFloat ? (float)$value : (int)$value;
    }

    private function parseDate($dateValue)
    {
        if (empty($dateValue) || $dateValue === '-') {
            return null;
        }

        try {
            // If it's already a DateTime object (from Excel)
            if ($dateValue instanceof \DateTime) {
                return Carbon::instance($dateValue);
            }

            // If it's an Excel serialized date (integer)
            if (is_numeric($dateValue)) {
                return Carbon::createFromTimestamp((($dateValue - 25569) * 86400));
            }

            $dateString = (string)$dateValue;

            // Remove any extra spaces
            $dateString = trim($dateString);

            // Try common formats
            $formats = [
                'd/m/Y', // 15/05/1990
                'Y-m-d', // 1990-05-15
                'Y/m/d', // 1990/05/15
                'd-m-Y', // 15-05-1990
                'm/d/Y', // 05/15/1990
                'd/m/y', // 15/05/90
            ];

            foreach ($formats as $format) {
                $date = Carbon::createFromFormat($format, $dateString);
                if ($date !== false) {
                    return $date;
                }
            }

            // Last resort - try natural parsing
            return Carbon::parse($dateString);
        } catch (\Exception $e) {
            Log::error("Failed to parse date: {$dateValue}", ['error' => $e->getMessage()]);
            return null;
        }
    }
    private function extractHealthCardValue($value)
    {
        if ($value === '1' || $value === 1 || $value === 'نعم' || $value === 'yes' || $value === 'true') {
            return 1; // Integer for database
        }
        return 0; // Integer for database
    }

    private function validateEmployeeData($data, $rowNumber)
    {
        $rules = [
            'name' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $blacklisted = Employee::where('name', $value)
                        ->whereHas('user', function ($query) {
                            $query->where('account_status', 'deactivated');
                        })
                        ->whereIn('stop_reason', ['سوء اداء', 'سوء أداء'])
                        ->exists();

                    if ($blacklisted) {
                        $fail('الموظف مدرج في القائمة السوداء بسبب سوء الأداء');
                    }
                },
            ],
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'job' => 'required|string|max:255',
            'joining_date' => 'required|date',
            'birthday' => 'required|date',
            'bank_name' => 'required|string|max:255',
            'age' => 'required|integer|min:10|max:100',
            'owner_account_name' => 'required|string|max:255',
            'iban' => [
                'required',
                'string',
                'digits:22',
                'unique:employees,iban'
            ],
            'vehicle_ID' => [
                'required',
                'string',
                'max:255',
                function ($attribute, $value, $fail) {
                    $exists = Employee::where('vehicle_info->vehicle_ID', $value)->exists();
                    if ($exists) {
                        $fail('رقم اللوحة مستخدم مسبقاً');
                    }
                },
            ],
            'vehicle_type' => 'required|string|max:255',
            'vehicle_model' => 'required|string|max:255',
            'residence' => 'required|string|max:255',
            'residence_neighborhood' => 'required|string|max:255',
            'Tshirt_size' => 'nullable|string',
            'pants_size' => 'nullable|integer',
            'Shoes_size' => 'nullable|integer',
            'gender' => 'required|in:female,male',
            'health_card' => 'required|in:0,1',
            'work_area' => 'required|string|max:255',
            'id_card' => 'required|string|size:10|regex:/^[12][0-9]{9}$/|unique:users,id_card',
            'phone_number' => [
                'required',
                'string',
                'digits:9',
                function ($attribute, $value, $fail) {
                    $exists = User::where('contact_info->phone_number', $value)->exists();
                    if ($exists) {
                        $fail('رقم الجوال مستخدم مسبقاً');
                    }
                },
            ],
            'phone_type' => 'required|in:android,iphone',
            'nationality' => 'required|string|max:100',
            'role' => 'required|string|max:100|in:supervisor,project_manager,shelf_stacker,area_manager,employee,manager',
            'salary' => 'required|numeric|min:0|max:999999.99',
            'english_level' => 'required|in:basic,intermediate,advanced',
            'certificate_type' => 'required|string|max:100|in:high_school,diploma,bachelor,master,phd',
            'marital_status' => 'required|in:single,married,divorced,widowed',
            'members_number' => [
                'nullable',
                'integer',
                'min:0',
                'max:20',
                function ($attribute, $value, $fail) use ($data) {
                    if (($data['marital_status'] !== 'single') && empty($value)) {
                        $fail('عدد أفراد الأسرة مطلوب للحالة الاجتماعية غير الأعزب');
                    }
                },
            ],
            'supervisor' => [
                'nullable',
                function ($attribute, $value, $fail) use ($data) {
                    if ($data['role'] == 'shelf_stacker' && empty($value)) {
                        $fail('المشرف مطلوب لموظف الرفوف');
                        return;
                    }

                    if (!empty($value)) {
                        $supervisor = User::where('name', $value)->where('role', 'supervisor')->first();
                        if (!$supervisor) {
                            $fail('المشرف المحدد غير صالح أو لا يملك الدور المناسب.');
                        }
                    }
                },
            ],
            'area_manager' => [
                'nullable',
                function ($attribute, $value, $fail) use ($data) {
                    if ($data['role'] == 'supervisor' && empty($value)) {
                        $fail('مدير المنطقة مطلوب للمشرف');
                        return;
                    }

                    if (!empty($value)) {
                        $areaManager = User::where('name', $value)->where('role', 'area_manager')->first();
                        if (!$areaManager) {
                            $fail('مدير المنطقة المحدد غير صالح أو لا يملك الدور المناسب.');
                        }
                    }
                },
            ],
        ];

        $messages = [
            'name.required' => 'حقل الاسم الكامل مطلوب',
            'email.required' => 'حقل البريد الإلكتروني مطلوب',
            'email.email' => 'صيغة البريد الإلكتروني غير صحيحة',
            'email.unique' => 'البريد الإلكتروني مستخدم مسبقاً',
            'password.required' => 'كلمة المرور مطلوبة',
            'password.min' => 'كلمة المرور يجب أن تكون 8 أحرف على الأقل',
            'job.required' => 'حقل المهنة مطلوب',
            'joining_date.required' => 'حقل تاريخ الانضمام مطلوب',
            'joining_date.date' => 'صيغة تاريخ الانضمام غير صحيحة',
            'birthday.required' => 'حقل تاريخ الميلاد مطلوب',
            'birthday.date' => 'صيغة تاريخ الميلاد غير صحيحة',
            'bank_name.required' => 'حقل اسم البنك مطلوب',
            'age.required' => 'حقل العمر مطلوب',
            'age.min' => 'العمر يجب أن يكون 10 سنوات على الأقل',
            'age.max' => 'العمر يجب أن يكون 100 سنة على الأكثر',
            'owner_account_name.required' => 'حقل اسم صاحب الحساب مطلوب',
            'iban.required' => 'حقل رقم الآيبان مطلوب',
            'iban.digits' => 'رقم الآيبان يجب أن يكون 22 رقماً',
            'iban.unique' => 'رقم الآيبان مستخدم مسبقاً',
            'vehicle_ID.required' => 'حقل رقم اللوحة مطلوب',
            'vehicle_type.required' => 'حقل نوع المركبة مطلوب',
            'vehicle_model.required' => 'حقل موديل المركبة مطلوب',
            'residence.required' => 'حقل مقر الإقامة مطلوب',
            'residence_neighborhood.required' => 'حقل الحي السكني مطلوب',
            'gender.required' => 'حقل الجنس مطلوب',
            'health_card.required' => 'حقل الشهادة الصحية مطلوب',
            'work_area.required' => 'حقل منطقة العمل مطلوب',
            'id_card.required' => 'حقل رقم الهوية مطلوب',
            'id_card.size' => 'رقم الهوية يجب أن يكون 10 أرقام',
            'id_card.regex' => 'صيغة رقم الهوية غير صحيحة',
            'id_card.unique' => 'رقم الهوية مستخدم مسبقاً',
            'phone_number.required' => 'حقل رقم الجوال مطلوب',
            'phone_number.digits' => 'رقم الجوال يجب أن يكون 9 أرقام',
            'phone_type.required' => 'حقل نوع الجوال مطلوب',
            'nationality.required' => 'حقل الجنسية مطلوب',
            'role.required' => 'حقل الدور الوظيفي مطلوب',
            'salary.required' => 'حقل الراتب مطلوب',
            'salary.numeric' => 'الراتب يجب أن يكون رقماً',
            'salary.min' => 'الراتب لا يمكن أن يكون أقل من 0',
            'salary.max' => 'الراتب لا يمكن أن يتجاوز 999,999.99',
            'english_level.required' => 'حقل مستوى اللغة الإنجليزية مطلوب',
            'certificate_type.required' => 'حقل نوع الشهادة مطلوب',
            'marital_status.required' => 'حقل الحالة الاجتماعية مطلوب',
            'members_number.integer' => 'عدد أفراد الأسرة يجب أن يكون رقماً صحيحاً',
            'members_number.min' => 'عدد أفراد الأسرة لا يمكن أن يكون أقل من 0',
            'members_number.max' => 'عدد أفراد الأسرة لا يمكن أن يتجاوز 20',
        ];

        return Validator::make($data, $rules, $messages);
    }

    private function createEmployee($data)
    {
        // First check if user already exists
        $existingUser = User::where('email', $data['email'])
            ->orWhere('id_card', $data['id_card'])
            ->first();

        if ($existingUser) {
            throw new \Exception('المستخدم موجود مسبقاً بالبريد الإلكتروني أو رقم الهوية');
        }

        // Check if employee with IBAN already exists
        $existingEmployee = Employee::where('iban', 'SA' . $data['iban'])->first();
        if ($existingEmployee) {
            throw new \Exception('رقم الآيبان مستخدم مسبقاً');
        }

        $user = User::create([
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'name' => $data['name'],
            'id_card' => $data['id_card'],
            'birthday' => $data['birthday'],
            'nationality' => $data['nationality'],
            'account_status' => 'active',
            'gender' => $data['gender'],
            'personal_image' => null,
            'role' => $data['role'],
            'contact_info' => [
                'phone_number' => $data['phone_number'],
                'phone_type' => $data['phone_type'],
                'residence' => $data['residence'],
                'area' => $data['work_area'],
                'residence_neighborhood' => $data['residence_neighborhood'],
            ],
            'size_info' => [
                'Tshirt_size' => $data['Tshirt_size'],
                'pants_size' => $data['pants_size'],
                'Shoes_size' => $data['Shoes_size'],
            ],
        ]);
        $projectId = $this->getProjectIdByName($data['project_name'] ?? $data['project']);
        $supervisorId = $this->getSupervisorIdByName($data['supervisor_name'] ?? $data['supervisor']);
        $areaManagerId = $this->getAreaManagerIdByName($data['area_manager_name'] ?? $data['area_manager']);

        $employee = Employee::create([
            'user_id' => $user->id,
            'name' => $data['name'],
            'job' => $data['job'],
            'joining_date' => $data['joining_date'],
            'vehicle_info' => [
                'vehicle_type' => $data['vehicle_type'],
                'vehicle_model' => $data['vehicle_model'],
                'vehicle_ID' => $data['vehicle_ID'],
            ],
            'health_card' => $data['health_card'],
            'work_area' => $data['work_area'],
            'salary' => $data['salary'],
            'english_level' => $data['english_level'],
            'certificate_type' => $data['certificate_type'],
            'marital_status' => $data['marital_status'],
            'members_number' => $data['members_number'],
            'owner_account_name' => $data['owner_account_name'],
            'iban' => 'SA' . $data['iban'],
            'bank_name' => $data['bank_name'],
            'project_id' => $projectId,
            'supervisor_id' => $supervisorId,
            'area_manager_id' => $areaManagerId,
        ]);
        EmployeeWorkHistory::create([
            'employee_id' => $employee->id,
            'start_date' => $employee->joining_date,
            'end_date' => null,
            'status' => 'active',
        ]);


        // Add to credentials CSV
        $this->addToCredentialsCSV($data);

        return $employee;
    }
    private function getSupervisorIdByName($supervisorName)
    {
        if (empty($supervisorName)) {
            return null;
        }

        try {
            // Assuming supervisors are also in the User table with role 'supervisor'
            $supervisor = \App\Models\User::where('name', $supervisorName)
                ->where('role', 'supervisor')
                ->first();
            $supervisorId = $supervisor?->employee?->id;

            return $supervisorId ? $supervisorId : null;
        } catch (\Exception $e) {
            Log::error("Error looking up supervisor: {$supervisorName}", ['error' => $e->getMessage()]);
            return null;
        }
    }

    private function getAreaManagerIdByName($areaManagerName)
    {
        if (empty($areaManagerName)) {
            return null;
        }

        try {
            // Assuming area managers are in the User table with role 'area_manager'
            $areaManager = \App\Models\User::where('name', $areaManagerName)
                ->where('role', 'area_manager')
                ->first();
            $areaManagerId = $areaManager?->employee?->id;
            return $areaManagerId ? $areaManagerId : null;
        } catch (\Exception $e) {
            Log::error("Error looking up area manager: {$areaManagerName}", ['error' => $e->getMessage()]);
            return null;
        }
    }
    private function getProjectIdByName($projectName)
    {
        if (empty($projectName)) {
            return null;
        }

        try {
            // Assuming you have a Project model
            $project = \App\Models\Project::where('name', $projectName)->first();

            if ($project) {
                return $project->id;
            }

            // If project not found, you can either:
            // Option 1: Create a new project automatically
            // $newProject = \App\Models\Project::create(['name' => $projectName]);
            // return $newProject->id;

            // Option 2: Log warning and return null (safer)
            Log::warning("Project not found: {$projectName}");
            return null;

        } catch (\Exception $e) {
            Log::error("Error looking up project: {$projectName}", ['error' => $e->getMessage()]);
            return null;
        }
    }
    private function getValidForeignKey($value)
    {
        if (empty($value) || $value === null || $value === '') {
            return null;
        }

        // Convert to integer and ensure it's positive
        $id = (int)$value;

        if ($id <= 0) {
            return null;
        }

        return $id;
    }
    private function addToCredentialsCSV($data)
    {
        $credentials = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ];

        $csvPath = storage_path('app/exports/employees_credentials.csv');
        $directory = dirname($csvPath);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        if (!file_exists($csvPath)) {
            file_put_contents($csvPath, "\xEF\xBB\xBF" . "إسم الموظف,البريد الإلكتروني,كلمة المرور\n");
        }

        file_put_contents($csvPath, implode(',', $credentials) . "\n", FILE_APPEND | LOCK_EX);
    }

    // Mapping helper methods (keep these as they are correct)
    private function mapGender($gender)
    {
        $genderMap = [
            'male' => 'male',
            'ذكر' => 'male',
            'female' => 'female',
            'أنثى' => 'female',
            'رجل' => 'male',
            'امرأة' => 'female',
        ];

        $cleanValue = trim(strtolower($gender));
        return $genderMap[$cleanValue] ?? 'male';
    }
    private function mapHealthStatus($healthStatus)
    {
        $healthStatusMap = [
            '1' => '1',
            'نعم' => '1',
            '0' => '0',
            'لا' => '0',

        ];

        $cleanValue = trim(strtolower($healthStatus));
        return $healthStatusMap[$cleanValue] ?? '0';
    }

    private function mapPhoneType($phoneType)
    {
        $phoneTypeMap = [
            'android' => 'android',
            'أندرويد' => 'android',
            'iphone' => 'iphone',
            'آيفون' => 'iphone',
            'ايفون' => 'iphone',
            'ios' => 'iphone',
        ];

        $cleanValue = trim(strtolower($phoneType));
        return $phoneTypeMap[$cleanValue] ?? 'android';
    }

    private function mapRole($role)
    {
        $roleMap = [
            'supervisor' => 'supervisor',
            'مشرف' => 'supervisor',
            'project_manager' => 'project_manager',
            'مدير مشروع' => 'project_manager',
            'shelf_stacker' => 'shelf_stacker',
            'مصفف أرفف' => 'shelf_stacker',
            'area_manager' => 'area_manager',
            'مدير منطقة' => 'area_manager',
            'employee' => 'employee',
            'موظف' => 'employee',
        ];

        $cleanValue = trim(strtolower($role));
        return $roleMap[$cleanValue] ?? 'employee';
    }

    private function mapEnglishLevel($level)
    {
        $levelMap = [
            'basic' => 'basic',
            'مبتدئ' => 'basic',
            'beginner' => 'basic',
            'intermediate' => 'intermediate',
            'متوسط' => 'intermediate',
            'advanced' => 'advanced',
            'متقدم' => 'advanced',
        ];

        $cleanValue = trim(strtolower($level));
        return $levelMap[$cleanValue] ?? 'basic';
    }

    private function mapCertificateType($certificate)
    {
        $certificateMap = [
            'high_school' => 'high_school',
            'الثانويةالعامة' => 'high_school',
            'diploma' => 'diploma',
            'دبلوم' => 'diploma',
            'bachelor' => 'bachelor',
            'بكالوريوس' => 'bachelor',
            'master' => 'master',
            'ماجستير' => 'master',
            'phd' => 'phd',
            'دكتوراه' => 'phd',
        ];

        $cleanValue = trim(strtolower($certificate));
        return $certificateMap[$cleanValue] ?? 'high_school';
    }

    private function mapMaritalStatus($status)
    {
        $statusMap = [
            'single' => 'single',
            'أعزب' => 'single',
            'married' => 'married',
            'متزوج' => 'married',
            'divorced' => 'divorced',
            'مطلق' => 'divorced',
            'widowed' => 'widowed',
            'أرمل' => 'widowed',
        ];

        $cleanValue = trim(strtolower($status));
        return $statusMap[$cleanValue] ?? 'single';
    }
}

