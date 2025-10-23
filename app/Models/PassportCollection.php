<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PassportCollection extends Model
{
    use HasFactory;

    protected $fillable = ['passport_id','employee_id','collected_at','notes'];

    protected $casts = [
        'collected_at' => 'datetime',
    ];

    public function passport() { return $this->belongsTo(Passport::class); }
    public function employee() { return $this->belongsTo(Employee::class); }
}
