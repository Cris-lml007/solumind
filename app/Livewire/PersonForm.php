<?php

namespace App\Livewire;

use App\Models\Person;
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

    public function mount($id = null){
        try {
            $this->person = Person::findOrFail($id);
            $this->ci = $this->person->ci;
            $this->name = $this->person->name;
            $this->phone = $this->person->phone;
            $this->email = $this->person->email;
        } catch (\Throwable $e) {
            $this->person = new Person();
        }
    }

    public function save(){
        $this->validate();
        $this->person->ci = $this->ci;
        $this->person->name = $this->name;
        $this->person->email = $this->email;
        $this->person->phone = $this->phone;
        $this->person->save();
        $this->redirect(route('dashboard.settings'));
    }

    public function remove(){
        $this->person->delete();
        $this->redirect(route('dashboard.settings'));
    }


    public function render()
    {
        return view('livewire.person-form');
    }
}
