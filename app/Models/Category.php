<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    // Table name (optional — Laravel will detect automatically)
    protected $table = 'categories';

    // Fields that can be filled via forms
    protected $fillable = [
        'name',
        'status',
    ];

    public function agencies()
{
    return $this->hasMany(\App\Models\Agency::class);
}

}
