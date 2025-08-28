<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use SoftDeletes;

    public function detail_contract(){
        return $this->hasMany(DetailContract::class);
    }

    public function partners(){
        return $this->belongsToMany(ContractPartner::class)->withPivot([
            'amount',
            'type',
            'currency',
            'interest',
            'description'
        ]);
    }
}
