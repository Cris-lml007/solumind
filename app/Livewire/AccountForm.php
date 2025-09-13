<?php

namespace App\Livewire;

use App\Models\Account;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Validate;
use Livewire\Component;

class AccountForm extends Component
{
    #[Validate('required|unique:accounts,name')]
    public $name;
    public $account;

    public $listeners = ['restore' => 'restore'];

    public function mount($id = null){
        if(!Gate::allows('config-permission',3))
            abort('404');
        if ($id) {
            $this->account = Account::findOrFail($id);
            $this->name = $this->account->name;
        } else {
            $this->account = new Account();
        }
    }

    public function save(){
        if(!Gate::allows('config-permission',3))
            abort('404');
        $this->validate();
        $t = Account::where('name',$this->name)->withTrashed()->first();
        if ($t !=null) {
            if($t->trashed()){
                return $this->dispatch('active');
            }
            if($t->id == 1) return redirect()->route('dashboard.settings');
            $this->validate([
                'name' => 'required|string|max:255|unique:accounts,name,' . ($this->account->id ?? 'null'),
            ]);
        }

        $this->account->name = $this->name;
        $this->account->save();

        return redirect()->route('dashboard.settings');
    }

    public function remove(){
        if(!Gate::allows('config-permission',3))
            abort('404');
        if($this->account->id == 1) return redirect()->route('dashboard.settings');
        $this->account->delete();
        return redirect()->route('dashboard.settings');
    }

    public function restore(){
        if(!Gate::allows('config-permission',3))
            abort('404');
        Account::where('name',$this->name)->restore();
        return redirect()->route('dashboard.settings');
    }

    public function render()
    {
        if(!Gate::allows('config-permission',3))
            abort('404');
        return view('livewire.account-form');
    }
}
