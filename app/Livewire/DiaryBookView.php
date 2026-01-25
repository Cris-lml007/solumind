<?php

namespace App\Livewire;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class DiaryBookView extends Component
{

    use WithPagination;

    public $search;

    #[On('tableSearchDiaryBook')]
    public function tableSearch($search){
        $this->search = $search;
        $this->render();
    }


    #[On('tablePageDiaryBook')]
    public function tablePage($page){
        $this->setPage($page);
    }



    public function render()
    {
        if(!Gate::allows('transaction-read'))
            abort('404');
        $heads = ['ID', 'Fecha','Ingreso (Bs)','Egreso (Bs)','Descripción', 'Contrato','a Fondo', 'a Cuenta'];

        if($this->search != null && $this->search != ''){
            $data = Transaction::where('id','like','%'.$this->search.'%')
                ->orWhere('date','like','%'.$this->search.'%')
                ->orWhere('description','like','%'.$this->search.'%')
                ->orWherehas('contract',function(Builder $builder){
                    $builder->where('cod','like','%'.$this->search.'%');
                })->orWherehas('account',function(Builder $builder){
                    $builder->where('name','like','%'.$this->search.'%');
                })->orderBy('date','desc')
                  ->paginate();
        }else{
            $data = Transaction::orderBy('date','desc')->paginate();
        }
        return view('livewire.diary-book-view', compact(['data','heads']));
    }
}
