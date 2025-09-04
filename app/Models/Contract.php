<?php

namespace App\Models;

use App\StatusContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contract extends Model
{
    use SoftDeletes;

    public function detail_contract(){
        return $this->hasMany(DetailContract::class);
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }

    public function partners(){
        return $this->belongsToMany(Partner::class,'contract_partners')->wherePivotNull('deleted_at')->withPivot(['amount','type','interest','description']);
        //             ->withPivot([
        //     'amount',
        //     'type',
        //     'currency',
        //     'interest',
        //     'description'
        // ]);
    }

    public function inversions(){
        return $this->hasMany(ContractPartner::class);
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }


    protected function casts(): array
    {
        return [
            'status' => StatusContract::class,
        ];
    }
}
