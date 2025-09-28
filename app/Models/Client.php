<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;

    public function person(){
        return $this->belongsTo(Person::class);
    }

    public function account(){
        return $this->morphOne(Account::class, 'accountable');
    }
}
