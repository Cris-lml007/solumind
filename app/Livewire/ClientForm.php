<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Person;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Detalle Cliente')]
class ClientForm extends Component
{

    public $listeners = ['remove' => 'remove'];

    #[Validate('required|integer|min_digits:6|max_digits:10')]
    public $ci = '';
    #[Validate('required|string')]
    public $name;
    public $email;
    #[Validate('required|integer|min_digits:7|:max_digits:8')]
    public $phone;


    public $organization;
    public $nit;
    public $bussiness_phone;
    public $bussiness_email;

    #[Locked]
    public Client $client;
    #[Locked]
    public Person $person;


    public function mount($id = null)
    {
        try {

            $this->client = Client::findOrFail($id);
            $this->person = $this->client->person;
            $this->ci = $this->person->ci;
            $this->name = $this->person->name;
            $this->email = $this->person->email;
            $this->phone = $this->person->phone;
            $this->organization = $this->client->organization;
            $this->bussiness_email = $this->client->email;
            $this->bussiness_phone = $this->client->phone;
            $this->nit = $this->client->nit;


        } catch (\Exception $e) {
            $this->ci = '';
            $this->name = '';
            $this->email = '';
            $this->phone = '';

            $this->organization = '';
            $this->bussiness_email = '';
            $this->bussiness_phone = '';

            $this->client = new Client();
            $this->person = new Person();
        }
    }


    public function updatedCi()
    {
        try {
            $this->person = Person::where('ci', $this->ci)->firstOrFail();

            $this->name = $this->person->name;
            $this->email = $this->person->email;
            $this->phone = $this->person->phone;
        } catch (\Exception $e) {

            $this->person = new Person();
            $this->name = '';
            $this->email = '';
            $this->phone = '';
        }
    }


    public function save()
    {
        $this->validate();
        $this->person->ci = $this->ci;
        $this->person->name = $this->name;
        $this->person->email = $this->email;
        $this->person->phone = $this->phone;
        $this->person->save();

        $this->client->person_id = $this->person->id;
        $this->client->organization = $this->organization;
        $this->client->nit = $this->nit;
        $this->client->phone = $this->phone;
        $this->client->email = $this->bussiness_email;
        $this->client->phone = $this->bussiness_phone;
        $this->client->save();
        $this->redirect(route('dashboard.client'));
    }


    public function remove(){
        $this->client->delete();
        $this->redirect(route('dashboard.client'));

    }

    public function render()
    {
        return view('livewire.client-form');
    }
}
