<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Person;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Detalle Cliente')]
class ClientForm extends Component
{
  
    public $ci = '';
    public $name;
    public $email;
    public $phone;


    public $organization;
    public $nit;

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


        } catch (\Exception $e) {
            $this->ci = '';
            $this->name = '';
            $this->email = '';
            $this->phone = '';

            $this->organization = '';
            
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
        $this->person->ci = $this->ci;
        $this->person->name = $this->name;
        $this->person->email = $this->email;
        $this->person->phone = $this->phone;
        $this->person->save();

        $this->client->person_id = $this->person->id;
        $this->client->organization = $this->organization;
        $this->client->phone = $this->phone; 
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