<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Number;

class Item extends Model
{
    use SoftDeletes;

    public function detail_items(){
        return $this->hasMany(DetailItem::class);
    }

    public function detail_contracts(){
        return $this->morphMany(DetailContract::class, 'detailable');
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function setPriceAttribute($value){
        $this->attributes['price'] = is_null($value) ? null : Number::parse($value);
    }
}
