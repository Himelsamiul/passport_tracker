<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Passport extends Model
{
    use HasFactory;

    protected $fillable = [
        'agent_id',
        'passport_officer_id',   // NEW
        'employee_id',
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
        'notes',                 // NEW
        'status',
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'issue_date'    => 'date',
        'expiry_date'   => 'date',
    ];

    // Relations
    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    // Which employee received the passport
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    // The “middle person”/officer
    public function passportOfficer()
    {
        return $this->belongsTo(PassportOfficer::class);
    }
}
