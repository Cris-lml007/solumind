<?php

namespace App\Livewire;

use App\Models\Account;
use Livewire\Component;

class AccountForm extends Component
{
    public $name;
    public $account;

    public $listeners = ['restore' => 'restore'];

    public function mount($id = null){
        if ($id) {
            $this->account = Account::findOrFail($id);
            $this->name = $this->account->name;
        } else {
            $this->account = new Account();
        }
    }

    public function save(){
        $t = Account::where('name',$this->name)->withTrashed()->first();
        if ($t !=null) {
            if($t->trashed()){
                return $this->dispatch('active');
            }
        }
        if($t->id == 9999) return redirect()->route('dashboard.settings');
        $this->validate([
            'name' => 'required|string|max:255|unique:accounts,name,' . ($this->account->id ?? 'null'),
        ]);

        $this->account->name = $this->name;
        $this->account->save();

        return redirect()->route('dashboard.settings');
    }

    public function remove(){
        if($this->account->id == 9999) return redirect()->route('dashboard.settings');
        $this->account->delete();
        return redirect()->route('dashboard.settings');
    }

    public function restore(){
        Account::where('name',$this->name)->restore();
        return redirect()->route('dashboard.settings');
    }

    public function render()
    {
        return view('livewire.account-form');
    }
}
