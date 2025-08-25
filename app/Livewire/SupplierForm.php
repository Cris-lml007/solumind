<?php

namespace App\Livewire;

use App\Models\Person;
use App\Models\Supplier;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detalle Proveedor')]
class SupplierForm extends Component
{
    public $ci = '';
    public $name;
    public $email;
    public $cellular;

    public $nit;
    public $business_name;
    public $business_email;
    public $business_cellular;
    public $category;

    #[Locked]
    public Supplier $supplier;
    #[Locked]
    public Person $person;

    public function mount($nit = null){
        try {
            $this->supplier = Supplier::where('nit', $nit)->firstOrFail();
            $this->person = Person::where('id', $this->supplier->person_id)->firstOrFail();
            $this->ci = $this->supplier->person->ci;
            $this->name = $this->supplier->person->name;
            $this->email = $this->supplier->person->email;
            $this->cellular = $this->supplier->person->phone;

            $this->nit = $this->supplier->nit;
            $this->business_name = $this->supplier->organization;
            $this->business_email = $this->supplier->email;
            $this->business_cellular = $this->supplier->phone;
        } catch (\Exception $e) {
            $this->ci = '';
            $this->name = '';
            $this->email = '';
            $this->cellular = '';

            $this->nit = '';
            $this->business_name = '';
            $this->business_email = '';
            $this->business_cellular = '';
            $this->category = '';

            $this->supplier = new Supplier();
            $this->person = new Person();
        }
    }

    public function updatedCi(){
        try {
            $this->person = Person::where('ci', $this->ci)->firstOrFail();
            $this->name = $this->person->name;
            $this->email = $this->person->email;
            $this->cellular = $this->person->phone;
        } catch (\Exception $e) {
            $this->person = new Person();
            $this->name = '';
            $this->email = '';
            $this->cellular = '';
        }
    }

    public function save(){
        $this->person->ci = $this->ci;
        $this->person->name = $this->name;
        $this->person->email = $this->email;
        $this->person->phone = $this->cellular;
        $this->person->save();

        $this->supplier->nit = $this->nit;
        $this->supplier->organization = $this->business_name;
        $this->supplier->email = $this->business_email;
        $this->supplier->phone = $this->business_cellular;
        $this->supplier->person_id = $this->person->id;
        $this->supplier->save();
        $this->redirect(route('dashboard.supplier'));
    }

    public function render()
    {
        return view('livewire.supplier-form');
    }
}
