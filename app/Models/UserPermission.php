<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserPermission extends Model
{
    protected $fillable = [
        'user_id',
        'product',
        'supplier',
        'partner',
        'item',
        'transaction',
        'delivery',
        'client',
        'voucher',
        'ledger',
        'report',
        'config',
        'history'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
