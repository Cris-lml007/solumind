<?php

namespace App\Livewire;

use App\Models\Person;
use App\Models\User;
use App\Models\UserPermission;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Perfil')]
class Userform extends Component
{
    #[Validate('required')]
    public $name;
    #[Validate('required|email')]
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
    public $p7 = 1;
    public $p8 = 1;
    public $p9 = 1;
    public $p10 = 1;
    public $p11 = 1;
    public $p12 = 1;

    public User $user;
    public UserPermission $permissions;

    public $status = 0;

    public function mount($id = null){
        if(Auth::user()->id != $id)
            if(!Gate::allows('config-permission',3))
                abort('404');
        try {
            $this->user = User::findOrFail($id);

            $this->name = $this->user->name;
            $this->email = $this->user->email;
            // $this->password = $this->user->password;
            $this->person_id = $this->user->person_id;
            $this->is_active = $this->user->is_active == 1 ? true : false;
            if($this->user->permission == null){
                $this->permissions = new UserPermission();
            }else{
                $this->permissions = $this->user->permission;
                $this->user->id= $this->user->permission->user_id;
                $this->p1 = $this->user->permission->supplier;
                $this->p2 = $this->user->permission->product;
                $this->p3 = $this->user->permission->item;
                $this->p4 = $this->user->permission->delivery;
                $this->p5 = $this->user->permission->partner;
                $this->p6 = $this->user->permission->transaction;
                $this->p7 = $this->user->permission->client;
                $this->p8 = $this->user->permission->voucher;
                $this->p9 = $this->user->permission->ledger;
                $this->p10 = $this->user->permission->report;
                $this->p11 = $this->user->permission->config;
                $this->p12 = $this->user->permission->history;
            }

            $this->status = 1;
        } catch (\Throwable) {
            $this->user = new User();
            $this->permissions = new UserPermission();
        }
    }

    public function save(){
        if(Auth::user()->id != $this->user->id)
            if(!Gate::allows('config-permission',3))
                abort('404');
        Validator::make([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
            'password_confirmation' => $this->password_confirmation
        ],[
            'name' => 'required',
            'email' => ['required','email', Rule::unique('users')->ignore($this->user->id)],
            'password' => ($this->user->id == null ? ['required','confirmed:password_confirmation','min:8'] : []),
            'password_confirmation' => ($this->user->id == null ? 'required' : '')
        ],[
            'confirmed' => 'La contraseña no coincide'
        ]
        )->validate();
        $this->user->name = $this->name;
        $this->user->email = $this->email;
        if(!empty($this->password))
            $this->user->password = $this->password;
        $this->user->person_id = $this->person_id;
        $this->user->is_active = $this->is_active;
        $this->user->save();

        $this->permissions->user_id = $this->user->id ?? null;
        $this->permissions->supplier =  $this->p1;
        $this->permissions->product = $this->p2;
        $this->permissions->item = $this->p3;
        $this->permissions->delivery = $this->p4;
        $this->permissions->partner = $this->p5;
        $this->permissions->transaction = $this->p6;
        $this->permissions->client = $this->p7;
        $this->permissions->voucher = $this->p8;
        $this->permissions->ledger = $this->p9;
        $this->permissions->report = $this->p10;
        $this->permissions->config = $this->p11;
        $this->permissions->history = $this->p12;
        $this->permissions->save();
        $this->redirect(route('dashboard.settings'));
    }

    public function remove(){
        if(!Gate::allows('config-permission',3))
            abort('404');
        $this->user->delete();
        $this->redirect(route('dashboard.settings'));
    }

    public function render()
    {
        $persons = Person::all();
        return view('livewire.userform',compact(['persons']));
    }
}
