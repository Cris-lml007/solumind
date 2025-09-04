<?php

namespace App\Livewire;

use App\Models\Account;
use App\Models\Contract;
use App\Models\ContractPartner;
use App\Models\Partner;
use App\Models\Person;
use App\Models\Transaction;
use App\TypeTransaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PartnerForm extends Component
{
    public $listeners = ['remove' => 'remove'];

    #[Validate('required|integer|min_digits:6|max_digits:10')]
    public $ci;
    #[Validate('required')]
    public $name;
    #[Validate('required|integer|min_digits:7|max_digits:8')]
    public $cellular;
    public $email;

    public $organization;
    public $post;

    public Partner $partner;
    public Person $person;

    public $contractPartner;
    public $pay_description;
    public $pay_amount;

    public function mount($id = null){
        try{
            $this->partner = Partner::where('id',$id)->firstOrFail();
            $this->ci = $this->partner->person->ci;
            $this->name = $this->partner->person->name;
            $this->cellular = $this->partner->person->phone;
            $this->email = $this->partner->person->email;

            $this->organization = $this->partner->organization;
            $this->post = $this->partner->post;
        }catch(\Exception){
            $this->partner = new Partner();
            $this->person = new Person();
        }
    }

    public function updatedCi(){
        $p = Person::where('ci',$this->ci)->first();
        if($p != null){
            $this->person = $p;
            $this->name = $this->person->name;
            $this->cellular = $this->person->phone;
            $this->email = $this->person->email;
        }else{
            $this->name = null;
            $this->cellular = null;
            $this->email = null;
        }
    }

    public function save(){
        $this->validate();
        $this->person->ci = $this->ci;
        $this->person->name = $this->name;
        $this->person->phone = $this->cellular;
        $this->person->email = $this->email;
        $this->person->save();

        $this->partner->organization = $this->organization;
        $this->partner->post = $this->post;
        $this->partner->person_id = $this->person->id;
        $this->partner->save();

        $this->redirect(route('dashboard.partner'));
    }

    public function remove(){
        $this->partner->delete();
        $this->redirect(route('dashboard.partner'));
    }

    public function payUtility(){
        $contractPartner = ContractPartner::find($this->contractPartner);
        $utotal = $contractPartner->contract->detail_contract()->sum(DB::raw('sale_price * quantity')) - $contractPartner->contract->detail_contract()->sum('purchase_total');
        // dd($contractPartner->interest);
        Validator::make([
            'description' => $this->pay_description,
            'amount' => $this->pay_amount
        ],[
            'description' => 'required',
            'amount' => 'required|integer|min:1|max:'. (($utotal * ($contractPartner->interest / 100)) - $contractPartner->transactions()->sum('amount'))
        ])->validate();
        Transaction::create([
            'date' => now(),
            'contract_id' => $contractPartner->contract_id,
            'contract_partner_id' => $contractPartner->id,
            'type' => TypeTransaction::EXPENSE->value,
            'description' => $this->pay_description,
            'amount' => $this->pay_amount,
            'account_id' => 9999
        ]);
        return $this->redirect(route('dashboard.partner.form',$this->partner->id));
    }

    public function render()
    {
        $data_t = $this->partner->contracts;
        $config_t = ['columns' => [null, null, null, null, null, ['orderable' => false, 'searchable' => false]]];
        $heads_t = ['Contrato', 'Inversión (Bs)', 'Utilidad (%)', 'Retirado (Bs)', 'Saldo (Bs)','Acciones'];
        return view('livewire.partner-form',compact(['heads_t','config_t','data_t']));
    }
}
