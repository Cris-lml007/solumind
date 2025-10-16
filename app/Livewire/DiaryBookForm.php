<?php

namespace App\Livewire;

use App\Models\Account;
use App\Models\Contract;
use App\Models\DetailContract;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Number;
use Illuminate\Validation\Rule;
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
    public $contracts = [];

    public Transaction $transaction;

    public $listeners = ['remove' => 'remove'];

    public $status = 0;
    public $balance = 0;

    public function updatedSearchContract(){
        if(!empty($this->search_contract)){
            $this->contracts = Contract::where('cod','like',$this->search_contract.'%')->get();
        }else{
            $this->contracts = Contract::all();
        }
    }

    public function mount($id = null){
        if(!Gate::allows('transaction-read'))
            abort('404');
        try{
            $this->contracts = Contract::where('status',3)->orWhere('status',1)->get();
            $this->transaction = Transaction::findOrFail($id);
            $this->date =  Carbon::parse($this->transaction->date)->toDateString();
            $this->description = $this->transaction->description;
            $this->import = $this->transaction->amount;
            $this->type = $this->transaction->type;
            $this->contract_id = $this->transaction->contract_id;
            $this->account_id = $this->transaction->account_id;
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

    public function updatedImport(){
        if(Contract::where('id',$this->contract_id)->exists()){
            $this->balance = DetailContract::where('contract_id',$this->contract_id)->sum(DB::raw('sale_price*quantity')) -
                Transaction::where('contract_id',$this->contract_id)->where('type',2)->sum('amount');
        }else{
            $this->balance = 0;
        }
        // dd($this->balance,Number::parse($this->import ?? 0),Number::parse($this->import ?? 0) <= $this->balance);
    }

    public function updatedContractId(){
        $this->updatedImport();
    }


    public function save(){
        if(!Gate::allows('transaction-permission',3))
            abort('404');
        $this->validate();
        // dd($this->balance-$this->import);
        // Validator::make(['balance' => $this->balance],['balance' => 'gte:'.($this->import)])->validate();
        $this->transaction->description = $this->description;
        $this->transaction->amount = $this->import;
        $this->transaction->type = $this->type;
        $this->transaction->contract_id = $this->contract_id == 'null' ? null : $this->contract_id;
        $this->transaction->account_id = $this->account_id;
        $this->transaction->date = $this->date;
        $this->transaction->save();
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
