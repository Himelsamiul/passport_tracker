<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_id',
        'contact_person',
        'phone',
        'email',
        'address',
        'status',
        'notes',
    ];

    public function category()
{
    return $this->belongsTo(\App\Models\Category::class);
}
public function passportProcessings() { return $this->hasMany(\App\Models\PassportProcessing::class); }

}
