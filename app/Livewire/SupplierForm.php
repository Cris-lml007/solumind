<?php

namespace App\Livewire;

use App\Models\Account;
use App\Models\Person;
use App\Models\Supplier;
use Illuminate\Support\Facades\Gate;
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
    #[Validate('required|string',as: 'nombre')]
    public $name;
    public $email;
    #[Validate('required|integer|min_digits:7|max_digits:8',as: 'celular')]
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
        if(!Gate::allows('supplier-read'))
            abort('404');
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
        } catch (\Exception) {
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
        } catch (\Exception) {
            $this->person = new Person();
            $this->name = '';
            $this->email = '';
            $this->cellular = '';
        }
    }

    public function save(){
        if(!Gate::allows('supplier-permission',3))
            abort('404');
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

        Account::where('accountable_type',Supplier::class)
            ->where('accountable_id',$this->supplier->id)
            ->where('name','Proveedor: '. (empty($this->supplier->organization) ? $this->person->name : $this->supplier->organization))
            ->firstOrCreate([
                'name' => 'Proveedor: '. (empty($this->supplier->organization) ? $this->person->name : $this->supplier->organization),
                'accountable_type' => Supplier::class,
                'accountable_id' => $this->supplier->id
            ]);
        $this->redirect(route('dashboard.supplier'));
    }

    public function remove(){
        if(!Gate::allows('supplier-permission',3))
            abort('404');
        $this->supplier->delete();
        $this->redirect(route('dashboard.supplier'));
    }

    public function render()
    {
        if(!Gate::allows('supplier-read'))
            abort('404');
        return view('livewire.supplier-form');
    }
}
