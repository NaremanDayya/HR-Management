<?php

namespace App\Http\Resources;

use App\Models\Project;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeRequestResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'employee_name' => $this->employee->name ?? '',
            'employee_id' => $this->employee_id,
            'manager_name' => $this->manager->name ?? '',
            'manager_id' => $this->manager_id,
            'status' => $this->status,
            'request_type' => $this->request_type,
            'description' => $this->description,
            'response_status' => $this->response_status,
            'response_date' => $this->response_date?->format('Y-m-d H:i'),
            'notes' => $this->notes,
            'created_at' => $this->created_at->format('Y-m-d H:i'),
        ];
    }
}
