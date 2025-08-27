<?php

namespace App\Livewire;

use App\Models\Person;
use App\Models\Supplier;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Detalle Proveedor')]
class SupplierForm extends Component
{

    public $listeners = ['remove' => 'remove'];

    #[Validate('required|integer|min_digits:6|max_digits:10')]
    public $ci = '';
    #[Validate('required|string')]
    public $name;
    public $email;
    #[Validate('required|integer|min_digits:7|max_digits:8')]
    public $cellular;

    #[Validate('required|integer')]
    public $nit;
    public $business_name;
    public $business_email;
    public $business_cellular;
    public $category;

    #[Locked]
    public $is_update = false;

    #[Locked]
    public Supplier $supplier;
    #[Locked]
    public Person $person;

    public function mount($id = null){
        try {
            $this->supplier = Supplier::where('id', $id)->firstOrFail();
            $this->person = Person::where('id', $this->supplier->person_id)->firstOrFail();
            $this->ci = $this->supplier->person->ci;
            $this->name = $this->supplier->person->name;
            $this->email = $this->supplier->person->email;
            $this->cellular = $this->supplier->person->phone;

            $this->nit = $this->supplier->nit;
            $this->business_name = $this->supplier->organization;
            $this->business_email = $this->supplier->email;
            $this->business_cellular = $this->supplier->phone;

            $this->is_update = true;
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
            $this->is_update = false;

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
        $this->validate();
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

    public function remove(){
        $this->supplier->delete();
        $this->redirect(route('dashboard.supplier'));
    }

    public function render()
    {
        return view('livewire.supplier-form');
    }
}
