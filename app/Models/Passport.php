<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Passport extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'employee_id',           // ⬅️ add this
        'applicant_name',
        'address',
        'phone',
        'date_of_birth',
        'passport_number',
        'nationality',
        'passport_picture',
        'issue_date',
        'expiry_date',
        'nid_number',
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'issue_date'    => 'date',
        'expiry_date'   => 'date',
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    // ⬅️ NEW: which employee received the passport
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
}
