<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PassportOfficer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name','phone','email','address','status','notes',
    ];
}
