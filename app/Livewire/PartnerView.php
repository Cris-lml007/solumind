<?php

namespace App\Livewire;

use App\Models\Partner;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class PartnerView extends Component
{
    use WithPagination;
    public $search;

    #[On('tableSearchPartner')]
    public function tableSearch($search){
        $this->search = $search;
        $this->render();
    }


    #[On('tablePagePartner')]
    public function tablePage($page){
        $this->setPage($page);
    }

    public function render()
    {
        if(!Gate::allows('partner-read'))
            abort('404');
        $heads = ['ID','CI','Nombre Completo','Contacto Principal','Acciones'];
        $config = ['columns' => [null, null, null,null, ['orderable' => false, 'searchable' => false]]];

        if($this->search != null && $this->search != ''){
            $data = Partner::where('id','like','%'.$this->search.'%')
                ->orWhereHas('person',function(Builder $builder){
                    $builder->where('ci','like','%'.$this->search.'%')
                            ->orWhere('name','like','%'.$this->search.'%')
                            ->orWhere('email','like','%'.$this->search.'%')
                            ->orWhere('phone','like','%'.$this->search.'%');
                })->orWhere('organization','like','%'.$this->search.'%')
                ->paginate();
        }else{
            $data = Partner::paginate();
        }
        return view('livewire.partner-view', compact(['heads','data']));
    }
}
