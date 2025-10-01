<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Number;

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
        'unexpected',
        'purchase_total'
    ];

    public function detailable(){
        return $this->morphTo();
    }

    public function deliveries(){
        return $this->belongsToMany(Delivery::class,'delivery_detail_contracts')->withPivot(['quantity']);
    }

    public function setPurchasePriceAttribute($value){
        $this->attributes['purchase_price'] = Number::parse($value);
    }

    public function setSalePriceAttribute($value){
        $this->attributes['sale_price'] = Number::parse($value);
    }

    public function setPurchaseTotalAttribute($value){
        $this->attributes['purchase_total'] = Number::parse($value);
    }
}
