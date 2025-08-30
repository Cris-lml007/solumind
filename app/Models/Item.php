<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
}
