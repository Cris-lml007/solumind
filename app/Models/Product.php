<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Number;

class Product extends Model
{
    use SoftDeletes;

    public function supplier(){
        return $this->belongsTo(Supplier::class,'supplier_id','id');
    }

    public function detail_contracts(){
        return $this->morphMany(DetailContract::class, 'detailable');
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function setPriceAttribute($value){
        $this->attributes['price'] = Number::parse($value);
    }
}
