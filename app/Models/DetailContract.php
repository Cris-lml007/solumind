<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DetailContract extends Model
{
    use SoftDeletes;

    public function detailable(){
        return $this->morphTo();
    }
}
