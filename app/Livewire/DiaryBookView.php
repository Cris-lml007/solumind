<?php

namespace App\Livewire;

use App\Exports\DiaryBookExport;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

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

    public function exportExcel(){
        // return response()->streamDownload(function(){
        return Excel::download(new DiaryBookExport($this->search,'Libro Diario'),now().'.xlsx');
        // },now().'.xlsx');
    }



    public function render()
    {
        if(!Gate::allows('transaction-read'))
            abort('404');
        $heads = ['ID', 'Fecha','Ingreso (Bs)','Egreso (Bs)','Descripción', 'Contrato','a Fondo', 'a Cuenta'];

        if($this->search != null && $this->search != ''){
            $data = Transaction::whereDate('date',Carbon::now())->where(function(Builder $b){
                $b->orWhere('id','like','%'.$this->search.'%')
                ->orWhere('description','like','%'.$this->search.'%')
                ->orWherehas('contract',function(Builder $builder){
                    $builder->where('cod','like','%'.$this->search.'%');
                })->orWherehas('account',function(Builder $builder){
                    $builder->where('name','like','%'.$this->search.'%');
                });
            })->orderBy('date','desc')
              ->paginate();
                // ->orWhere('date','like','%'.$this->search.'%')
        }else{
            $data = Transaction::whereDate('date',Carbon::now())->orderBy('date','desc')->paginate();
        }
        return view('livewire.diary-book-view', compact(['data','heads']));
    }
}
