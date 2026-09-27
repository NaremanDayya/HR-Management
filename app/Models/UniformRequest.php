<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UniformRequest extends Model
{
    protected $fillable = [
        'employee_id', 'type', 'tshirt_count', 'hat_count', 'id_card_count', 'tool_bag_count',
        'status', 'deduct_cost', 'cost_amount', 'notes', 'reviewed_by', 'reviewed_at',
    ];

    protected $casts = [
        'deduct_cost' => 'boolean',
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

    public function totalItems(): int
    {
        return $this->tshirt_count + $this->hat_count + $this->id_card_count + $this->tool_bag_count;
    }
}
