<?php

namespace App\Livewire;

use App\Models\Person;
use App\Models\User;
use App\Models\UserPermission;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Userform extends Component
{
    #[Validate('required')]
    public $name;
    #[Validate('required|email|unique:users,email')]
    public $email;
    #[Validate('required')]
    #[Validate('min:8')]
    #[Validate('confirmed:password_confirmation', message:'La contraseña no coincide')]
    public $password;
    #[Validate('required')]
    public $password_confirmation;
    public $person_id;
    public $is_active = true;

    public $p1 = 1;
    public $p2 = 1;
    public $p3 = 1;
    public $p4 = 1;
    public $p5 = 1;
    public $p6 = 1;

    public User $user;
    public UserPermission $permissions;

    public function mount($id = null){
        try {
            $this->user = User::findOrFail($id);
            $this->permissions = $this->user->permission;

            $this->name = $this->user->name;
            $this->email = $this->user->email;
            // $this->password = $this->user->password;
            $this->person_id = $this->user->person_id;
            $this->is_active = $this->user->is_active;

            $this->user->id= $this->permissions->user_id;
            $this->p1 = $this->permissions->supplier;
            $this->p2 = $this->permissions->item;
            $this->p3 = $this->permissions->partner;
            $this->p4 = $this->permissions->product;
            $this->p5 = $this->permissions->delivery;
            $this->p6 = $this->permissions->transaction;

        } catch (\Throwable) {
            $this->user = new User();
            $this->permissions = new UserPermission();
        }
    }

    public function save(){
        $this->validate();
        $this->user->name = $this->name;
        $this->user->email = $this->email;
        $this->user->password = $this->password;
        $this->user->person_id = $this->person_id;
        $this->user->is_active = $this->is_active;
        $this->save();

        $this->permissions->user_id = $this->user->id;
        $this->permissions->supplier =  $this->p1;
        $this->permissions->item = $this->p2;
        $this->permissions->partner = $this->p3;
        $this->permissions->product = $this->p4;
        $this->permissions->delivery = $this->p5;
        $this->permissions->transaction = $this->p6;
        $this->save();
        $this->redirect(route('dashboard.settings'));
    }

    public function remove(){
        $this->user->delete();
        $this->redirect(route('dashboard.settings'));
    }

    public function render()
    {
        $persons = Person::all();
        return view('livewire.userform',compact(['persons']));
    }
}
