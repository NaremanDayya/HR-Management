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
        $id = self::where('key', $key)->value('id');
        
        if ($id === null) {
            throw new \Exception("Request type with key '{$key}' not found. Please run: php artisan db:seed --class=RequestTypeSeeder");
        }
        
        return $id;
    }
}
