<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BankUpdateRequest extends Model
{
    protected $fillable = [
        'employee_id',
        'full_name',
        'account_status',
        'id_card_number',
        'mobile_number',
        'city',
        'current_iban',
        'current_bank_name',
        'current_owner_account_name',
        'new_iban',
        'new_bank_name',
        'new_owner_account_name',
        'id_card_images',
        'notes',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
        'id_card_images' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function getIdCardImageUrlsAttribute(): array
    {
        return collect($this->id_card_images ?? [])
            ->map(fn ($path) => Storage::disk('public')->url($path))
            ->all();
    }
}
