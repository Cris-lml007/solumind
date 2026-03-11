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
        $income = Transaction::whereDate('date', Carbon::now())->where('type',1)->sum('amount');
        $expense = Transaction::whereDate('date', Carbon::now())->where('type',2)->sum('amount');
        $amount = $income - $expense;

        if($this->search != null && $this->search != ''){

            $terms = explode(' ', $this->search);

            $data = Transaction::whereDate('date', Carbon::now())
                ->where(function(Builder $query) use ($terms){

                    foreach ($terms as $term) {

                        $query->where(function(Builder $b) use ($term){

                            $b->orWhere('id','like','%'.$term.'%')
                              ->orWhere('description','like','%'.$term.'%')
                              ->orWhereHas('contract', function(Builder $builder) use ($term){
                                  $builder->where('cod','like','%'.$term.'%');
                              })
                              ->orWhereHas('account', function(Builder $builder) use ($term){
                                  $builder->where('name','like','%'.$term.'%');
                              });

                        });

                    }

                })
                ->orderBy('date','desc')
                ->paginate();

        }else{

            $data = Transaction::whereDate('date', Carbon::now())
                ->orderBy('date','desc')
                ->paginate();

        }
        return view('livewire.diary-book-view', compact(['data','heads','amount','income','expense']));
    }
}
