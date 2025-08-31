<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailContract extends Model
{
    use SoftDeletes;

    public $fillable = [
        'contract_id',
        'description',
        'purchase_price',
        'sale_price',
        'quantity',
        'bill',
        'interest',
        'operating',
        'comission',
        'bank',
        'unexpected'
    ];

    public function detailable(){
        return $this->morphTo();
    }
}
