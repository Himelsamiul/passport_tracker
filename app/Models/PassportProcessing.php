<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PassportProcessing extends Model
{
    use HasFactory;

    protected $fillable = ['passport_id','employee_id','agency_id','status','notes'];

    public function passport() { return $this->belongsTo(Passport::class); }
    public function employee() { return $this->belongsTo(Employee::class); }
    public function agency()   { return $this->belongsTo(Agency::class); }
}
