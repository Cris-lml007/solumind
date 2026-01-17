<?php

namespace App\Livewire;

use App\AssignedTransaction;
use App\Models\Account;
use App\Models\Contract;
use App\Models\ContractPartner;
use App\Models\DetailContract;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Number;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Libro Diario - Formulario')]
class DiaryBookForm extends Component
{

    #[Validate('required|date',as:'fecha')]
    public $date;
    #[Validate('required', message: 'Selecione un tipo')]
    #[Validate('integer', message: 'Seleccione un tipo')]
    #[Validate('between:1,2', message: 'Debe ser un Ingreso o Egreso')]
    public $type;
    #[Validate('required|numeric',as:'importe')]
    public $import;
    public $contract_id;
    #[Validate('required|string')]
    public $description;
    #[Validate('required', message: 'Selecione una cuenta')]
    #[Validate('integer', message: 'Selecione una cuenta')]
    public $account_id;

    public $search_contract;
    public $search_account;
    public $contracts = [];
    public $accounts = [];

    public Transaction $transaction;

    public $listeners = ['remove' => 'remove'];

    public $status = 0;
    public $balance = 0;

    public $assigned = 0;
    public $partner_id = null;
    public $partners = [];

    public function updatedSearchContract(){
        if(!empty($this->search_contract)){
            $this->contracts = Contract::where('cod','like',$this->search_contract.'%')->get();
        }else{
            $this->contracts = Contract::all();
        }
    }

    public function updatedSearchAccount(){
        if(!empty($this->search_account)){
            $this->accounts = Account::where('name','like','%'.$this->search_account.'%')->get();
        }else{
            $this->accounts = Account::all();
        }
    }

    public function mount($id = null){
        if(!Gate::allows('transaction-read'))
            abort('404');
        try{
            $this->contracts = Contract::where('status',3)->orWhere('status',1)->get();
            $this->accounts = Account::all();
            $this->transaction = Transaction::findOrFail($id);
            $this->date =  Carbon::parse($this->transaction->date)->toDateString();
            $this->description = $this->transaction->description;
            $this->import = $this->transaction->amount;
            $this->type = $this->transaction->type;
            $this->contract_id = $this->transaction->contract_id;
            $this->account_id = $this->transaction->account_id;
            $this->assigned = $this->transaction->assigned->value;
            $this->status = 1;
        }catch(\Exception){
            $this->transaction = new Transaction();
        }
    }


    public function updateWithPassword($password)
    {
        if(!Gate::allows('transaction-permission',3))
            abort('404');
        // Valida la contraseña (ejemplo: contra la del usuario actual)
        if (!Hash::check($password, Auth::user()->password)) {
            return ['success' => false, 'message' => 'Contraseña incorrecta'];
        }

        $this->transaction->save();
        return ['success' => true];
    }

    public function remove(){
        if(!Gate::allows('transaction-permission',3))
            abort('404');
        $this->transaction->delete();
        $this->redirect(route('dashboard.diary_book'));
    }

    public function updatedPartnerId(){
        if($this->partner_id != null && Contract::where('id',$this->contract_id)->exists()){
            $contract = Contract::find($this->contract_id);
            $utility = $contract->detail_contract()->sum(DB::raw('sale_price * quantity')) - $contract->detail_contract()->sum('purchase_total');
            $value = $utility * ((float) ($contract->partners()->where('partner_id',$this->partner_id)->first()->pivot->interest ?? 0) / 100);
            $this->balance = $value - Transaction::where('contract_id',$this->contract_id)->where('type',2)->where('contract_partner_id',$this->partner_id)->sum('amount');

            $this->account_id = $contract->partners()->where('partner_id',$this->partner_id)->first()->account->id;
            // dd($this->account_id);
        }else{
            $this->updatedContractId();
        }
    }

    public function updatedAssigned(){
        if(Contract::where('id',$this->contract_id)->exists() && $this->assigned != 0){
            $contract = Contract::find($this->contract_id);
            switch($this->assigned){
            case 1:
                $value = $contract->detail_contract->sum(function ($item) {
                    return Number::parse($item->sale_price) * ((float) ($item->bill ?? 0) / 100) * (float) $item->quantity;
                });
                break;
            case 2:
                $value = $contract->detail_contract->sum(function ($item) {
                    return Number::parse($item->sale_price) * ((float) $item->operating / 100) * (float) $item->quantity;
                });
                break;
            case 3:
                $value = $contract->detail_contract->sum(function ($item) {
                    return Number::parse($item->sale_price) * ((float) ($item->comission ?? 0) / 100) * (float) $item->quantity;
                });
                break;
            case 4:
                $value = $contract->detail_contract->sum(function ($item) {
                    return Number::parse($item->sale_price) * ((float) ($item->bank ?? 0) / 100) * (float) $item->quantity;
                });
                break;
            case 5:
                $value = $contract->detail_contract->sum(function ($item) {
                    return Number::parse($item->purchase_price) * ((float) ($item->interest ?? 0) / 100) * (float) $item->quantity;
                });
                break;
            case 6:
                $value = $contract->detail_contract->sum(function ($item) {
                    return Number::parse($item->sale_price) * ((float) ($item->unexpected ?? 0) / 100) * (float) $item->quantity;
                });
                break;
            case 7:
                $value = $contract->detail_contract()->sum(DB::raw('purchase_total'));
                break;
            case 8:
                $this->partners = $contract->partners;
                $value = 0;
                break;
            case 9:
                $value = $contract?->detail_contract()?->sum(DB::raw('sale_price*quantity')) - $contract->transactions()->where('type',1)->where('assigned',AssignedTransaction::PAYMENT)->sum('amount');
            }
            $this->balance = $value - Transaction::where('contract_id',$this->contract_id)->where('type',2)->where('assigned',$this->assigned)->sum('amount');
        }else{
            $this->updatedContractId();
        }
    }

    //public function updatedImport(){
        // dd($this->balance,Number::parse($this->import ?? 0),Number::parse($this->import ?? 0) <= $this->balance); //esto es solo validacion
    //}

    public function updatedContractId(){
        if(Contract::where('id',$this->contract_id)->exists()){
            $this->balance = DetailContract::where('contract_id',$this->contract_id)->sum(DB::raw('sale_price*quantity')) -
                Transaction::where('contract_id',$this->contract_id)->where('type',2)->sum('amount');
        }else{
            $this->balance = 0;
        }
        //$this->updatedImport();
    }


    public function save(){
        if(!Gate::allows('transaction-permission',3))
            abort('404');
        $this->validate();
        // dd($this->balance-$this->import);
        if($this->type == 2 && $this->contract_id != null)
            Validator::make(['balance' => $this->balance],['balance' => 'gte:'.($this->import)])->validate();
        $this->transaction->description = $this->description;
        $this->transaction->amount = $this->import;
        $this->transaction->type = $this->type;
        $this->transaction->contract_id = $this->contract_id == 'null' ? null : $this->contract_id;
        $this->transaction->account_id = $this->account_id;
        $this->transaction->date = $this->date;
        $this->transaction->assigned = $this->assigned;
        $this->transaction->save();

        if($this->assigned == 8){
            $this->transaction->contract_partner_id = ContractPartner::where('contract_id',$this->contract_id)->where('partner_id',$this->partner_id)->first()->id;
            $this->transaction->save();
        }
        $this->redirect(route('dashboard.diary_book'));
    }

    public function render()
    {
        if(!Gate::allows('transaction-read'))
            abort('404');
        $data = [
            'accounts' => Account::all()
        ];
        return view('livewire.diary-book-form',compact(['data']));
    }
}
