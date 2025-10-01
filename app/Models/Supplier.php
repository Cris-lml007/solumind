<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplier extends Model
{
    use SoftDeletes;

    public function person(){
        return $this->belongsTo(Person::class);
    }

    public function products(){
        return $this->hasMany(Product::class);
    }

    public function account(){
        return $this->morphOne(Account::class, 'accountable');
    }

    public function setPhoneAttribute($value){
        $this->attributes['phone'] = empty($value) ? null : $value;
    }

    protected function casts(): array{
        return [
            'phone' => 'integer'
        ];
    }
}
