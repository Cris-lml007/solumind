<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContractPartner extends Model
{
    use SoftDeletes;

    public function partner(){
        return $this->belongsTo(Partner::class);
    }

    public function contract(){
        return $this->belongsTo(Contract::class);
    }
}
