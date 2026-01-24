<?php

namespace App\Livewire;

use App\Models\Person;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Validate;
use Livewire\Component;

class PersonForm extends Component
{
    #[Validate('required|unique:people,ci')]
    public $ci;
    #[Validate('required')]
    public $name;
    public $phone;
    public $email;

    public Person $person;
    public $status = 0;

    public function mount($id = null){
        if(!Gate::allows('config-permission',3))
            abort('404');
        try {
            $this->person = Person::findOrFail($id);
            $this->ci = $this->person->ci;
            $this->name = $this->person->name;
            $this->phone = $this->person->phone;
            $this->email = $this->person->email;
            $this->status = 1;
        } catch (\Throwable $e) {
            $this->person = new Person();
        }
    }

    public function save(){
        if(!Gate::allows('config-permission',3))
            abort('404');
        $this->validate();
        $this->person->ci = $this->ci;
        $this->person->name = $this->name;
        $this->person->email = $this->email;
        $this->person->phone = $this->phone;
        $this->person->save();
        $this->redirect(route('dashboard.settings'), navigate: true);
    }

    public function remove(){
        if(!Gate::allows('config-permission',3))
            abort('404');
        $this->person->delete();
        $this->redirect(route('dashboard.settings'), navigate: true);
    }


    public function render()
    {
        if(!Gate::allows('config-permission',3))
            abort('404');
        return view('livewire.person-form');
    }
}
