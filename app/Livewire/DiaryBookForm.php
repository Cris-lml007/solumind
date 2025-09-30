<?php

namespace App\Livewire;

use App\Models\Account;
use App\Models\Contract;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
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
    #[Validate('required|integer',as:'importe')]
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



    public function save(){
        if(!Gate::allows('transaction-permission',3))
            abort('404');
        $this->validate();
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
