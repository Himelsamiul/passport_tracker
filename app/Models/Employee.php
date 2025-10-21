<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
   protected $fillable = ['name', 'designation', 'phone', 'email'];


   public function passportProcessings()
   {
       return $this->hasMany(PassportProcessing::class);
   }
}
