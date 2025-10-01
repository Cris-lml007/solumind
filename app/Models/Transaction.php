<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Number;

class Transaction extends Model
{
    use SoftDeletes;

    public $fillable = [
        'contract_id',
        'contract_partner_id',
        'delivery_id',
        'type',
        'description',
        'account_id',
        'amount',
        'date'
    ];

    public function contract(){
        return $this->belongsTo(Contract::class);
    }

    public function account(){
        return $this->belongsTo(Account::class);
    }

    public function contract_partner(){
        return $this->belongsTo(ContractPartner::class);
    }

    public function delivery(){
        return $this->belongsTo(Delivery::class);
    }

    public function setAmountAttribute($value){
        $this->attributes['amount'] = Number::parse($value);
    }

    protected function casts(): array{
        return [
            'amount' => 'decimal:2',
            'date' => 'date'
        ];
    }
}
