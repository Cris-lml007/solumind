<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Partner extends Model
{
    use SoftDeletes;

    public function person(){
        return $this->belongsTo(Person::class);
    }

    public function contracts(){
        return $this->belongsToMany(ContractPartner::class)->withPivot([
            'amount',
            'type',
            'currency',
            'interest',
            'description'
        ]);
    }
}
