<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    public $fillable = [
        'name',
        'accountable_id',
        'accountable_type'
    ];

    public function accountable(){
        return $this->morphTo();
    }
}
