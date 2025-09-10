<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delivery extends Model
{
    use SoftDeletes;

    public function detail_contract(){
        return $this->belongsToMany(DetailContract::class,'delivery_detail_contracts')->withPivot(['quantity']);
    }

    public function contract(){
        return $this->belongsTo(Contract::class);
    }
}
