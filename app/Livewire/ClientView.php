<?php

namespace App\Livewire;

use App\Models\Client;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class ClientView extends Component
{
    use WithPagination;

    public $search = '';

    #[On('tablePageClient')]
    public function tablePage($page){
        $this->setPage($page);
    }

    #[On('tableSearchClient')]
    public function tableSearch($search){
        $this->search = $search;
        $this->render();
    }

    public function render()
    {
        if(!Gate::allows('client-read'))
            abort('404');
        $heads = ['ID', 'CI', 'NIT', 'Nombre Cliente', 'Contacto Principal', 'Acciones'];

        if($this->search != '' && $this->search != null){
            $data = Client::where('id','like','%'.$this->search.'%')
                ->orWhere('nit','like','%'.$this->search.'%')
                ->orWhere('name','like','%'.$this->search.'%')
                ->orWhereHas('person',function(Builder $builder){
                    $builder->where('ci','like','%'.$this->search.'%')
                            ->orWhere('name','like','%'.$this->search.'%')
                            ->orWhere('email','like','%'.$this->search.'%')
                            ->orWhere('phone','like','%'.$this->search.'%');
                })->paginate();
        }else{
            $data = Client::paginate();
        }
        return view('livewire.client-view', compact(['heads', 'data']));
    }
}
