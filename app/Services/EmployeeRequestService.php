<?php

namespace App\Services;

use App\Models\EmployeeRequest;

class EmployeeRequestService
{
    public function filterRequests(array $filters = [])
    {
        return EmployeeRequest::with(['employee.user', 'employee.project', 'manager', 'requestType'])
            ->when(!empty($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(!empty($filters['project_id']), function ($query) use ($filters) {
                $query->whereHas('employee.project', function ($q) use ($filters) {
                    $q->where('id', $filters['project_id']);
                });
            })
            ->when(!empty($filters['request_type_id']), function ($query) use ($filters) {
                $query->where('request_type_id', $filters['request_type_id']);
            })
            ->when(!empty($filters['role']), function ($query) use ($filters) {
                $query->whereHas('employee.user', function ($q) use ($filters) {
                    $q->where('role', $filters['role']);
                });
            })

            ->when(!empty($filters['request_type']), function ($query) use ($filters) {
                $query->where('request_type', $filters['request_type']);
            })
            ->when(!empty($filters['search']), function ($query) use ($filters) {
                $search = $filters['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('description', 'like', "%$search%")
                        // Search employee name (through user relationship)
                        ->orWhereHas('employee.user', function ($q) use ($search) {
                            $q->where('name', 'like', "%$search%");
                        })
                        // Search employee job
                        ->orWhereHas('employee', function ($q) use ($search) {
                            $q->where('job', 'like', "%$search%");
                        })
                        // Search project name
                        ->orWhereHas('employee.project', function ($q) use ($search) {
                            $q->where('name', 'like', "%$search%");
                        })
                        // Search request type label
                        ->orWhereHas('requestType', function ($q) use ($search) {
                            $q->where('label', 'like', "%$search%");
                        })
                        // Search project manager name
                        ->orWhereHas('manager', function ($q) use ($search) {
                            $q->where('name', 'like', "%$search%");
                        });
                });
            })
            ->latest()
            ->get();
    }
}
