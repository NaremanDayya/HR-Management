<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestType extends Model
{
    protected $table = "requests_type";
    protected $fillable = ['key', 'label'];

    public function employeeRequest()
    {
        return $this->belongsTo(EmployeeRequest::class);
    }
    public static function getIdByKey(string $key): int
    {
        return self::where('key', $key)->value('id');
    }
}
