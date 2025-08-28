<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class Client extends Model
{
    use SoftDeletes;

    public function person(){
        return $this->belongsTo(Person::class);
    }

  
}