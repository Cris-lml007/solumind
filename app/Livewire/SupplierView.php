<?php

namespace App\Livewire;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class SupplierView extends Component
{
    use WithPagination;

    public $search;

    #[On('tableSearchSupplier')]
    public function tableSearch($search){
        $this->search = $search;
        $this->render();
    }


    #[On('tablePageSupplier')]
    public function tablePage($page){
        $this->setPage($page);
    }


    public function render()
    {
        if(!Gate::allows('supplier-read'))
            abort('404');
        $heads = ['ID', 'NIT', 'Nombre Proveedor', 'Contacto Principal', 'Acciones'];
        $config = ['columns' => [null, null, null, null, ['orderable' => false, 'searchable' => false]]];

        if($this->search != null && $this->search != ''){
            $data = Supplier::where('id','like','%'.$this->search.'%')
                ->orWhere('nit','like','%'.$this->search.'%')
                ->orWhereHas('person',function(Builder $builder){
                    $builder->Where('name','like','%'.$this->search.'%')
                            ->orWhere('email','like','%'.$this->search.'%')
                            ->orWhere('phone','like','%'.$this->search.'%');
                })->paginate();
        }else{
            $data = Supplier::paginate();
        }
        return view('livewire.supplier-view', compact(['heads', 'config','data']));
    }
}
