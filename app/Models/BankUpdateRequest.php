<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankUpdateRequest extends Model
{
    protected $fillable = [
        'employee_id',
        'current_iban',
        'current_bank_name',
        'current_owner_account_name',
        'new_iban',
        'new_bank_name',
        'new_owner_account_name',
        'id_card_image',
        'notes',
        'status',
        'rejection_reason',
        'reviewed_by',
        'reviewed_at',
    ];

    protected $casts = [
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

    public function getIdCardImageUrlAttribute(): string
    {
        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->id_card_image);
    }
}
