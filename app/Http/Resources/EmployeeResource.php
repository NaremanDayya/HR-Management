<?php

namespace App\Http\Resources;

use App\Models\Project;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    public function toArray($request)
    {
        $emp = $this;

        return [
            'id' => $emp->id,
            'name' => $emp->user->name,
            'salary' => $emp->salary,
            'english_level' => $emp->getEnglishLevel(),
            'certificate_type' => $emp->getCertificateType(),
            'marital_status' => $emp->getMaritalStatus(),
            'members_number' => $emp->members_number,
            'alerts_number' => $emp->alerts->count(),
            'deductions_number' => $emp->deductions->count(),
            'advances_number' => $emp->advances->count(),
            'increases_number' => $emp->increases->count(),
            'joining_date' => $emp->joining_date->format('Y-m-d'),
            'work_duration' => $emp->getWorkDuration($emp->joining_date),
            'job' => $emp->job,
            'vehicle_type' => $emp->vehicle_info['vehicle_type'] ?? null,
            'vehicle_model' => $emp->vehicle_info['vehicle_model'] ?? null,
            'vehicle_ID' => $emp->vehicle_info['vehicle_ID'] ?? null,
            'phone_type' => $emp->user->contact_info['phone_type'] ?? null,
            'phone_number' => $emp->user->contact_info['phone_number'] ?? null,
            'residence' => $emp->user->contact_info['residence'] ?? null,
            'area' => $emp->user->contact_info['area'] ?? null,
            'residence_neighborhood' => $emp->user->contact_info['residence_neighborhood'] ?? null,
            'Tshirt_size' => $emp->user->size_info['Tshirt_size'] ?? null,
            'Shoes_size' => $emp->user->size_info['Shoes_size'] ?? null,
            'pants_size' => $emp->user->size_info['pants_size'] ?? null,
            'health_card' => $emp->health_card,
            'work_area' => $emp->work_area,
            'id_card' => $emp->user->id_card ?? null,
            'nationality' => $emp->user->nationality ?? null,
            'gender' => $emp->user->getGender(),
            'role' => $emp->user->role,
            'birthday' => $emp->user->birthday ? $emp->user->birthday->format('Y-m-d') : null,
            'age' => $emp->user->getAge(),
            'stop_reason' => $emp->stop_reason,
            'whats_app_link' => $emp->user->generateWhatsappLink($emp->user->contact_info['phone_number']),
            'account_status' => $emp->user->account_status ?? null,
            'personal_image' => $emp->user?->personal_image,
            'project' => $emp->project?->name,
            'managed_projects' => $this->managedProjects->pluck('name')->toArray(),
            'replaced_old_employee_id' => optional($emp->replacedBy?->oldEmployee)->id ?? false,
            'replacements_count' => $emp->replacements()->count(),
            'temporary_assignments' => $emp->temporaryAssignments->count(),
            'new_emp_replacements_count' => $emp->replacedBy?->oldEmployee?->replacements()->count(),
            'supervisor_name' => $emp->supervisor?->name,
            'area_manager_name' => $emp->areaManager?->name,
            'iban' => $emp->iban,
            'owner_account_name' => $emp->owner_account_name,
            'bank_name' => $emp->bank_name,
            'email' => $emp->user->email
        ];
    }
}
