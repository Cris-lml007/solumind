<?php

namespace App\Livewire;

use App\Exports\DiaryBookExport;
use App\Exports\LedgerExport;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\WithPagination;
use Maatwebsite\Excel\Facades\Excel;

class LedgerView extends Component
{

    use WithPagination;

    public $filterStartDate;
    public $filterEndDate;

    public $filterDate;
    public $filterIncome;
    public $filterExpense;
    public $filterDescription;
    public $filterContract;
    public $filterFondo;
    public $filterAccount;
    public $filterId;

    public $q = 15;
    public $all= 0;

    public $data;

    public function updatedQ(){
        $this->render();
    }

    public function ir(){
        $this->render();
    }

    public function updatedFilterDate(){
        $this->render();
    }
    public function updatedFilterIncome(){
        $this->render();
    }
    public function updatedFilterExpense(){
        $this->render();
    }
    public function updatedFilterDescription(){
        $this->render();
    }
    public function updatedFilterContract(){
        $this->render();
    }

    public function updatedFilterAccount(){
        $this->render();
    }

    public function updatedFilterId(){
        $this->render();
    }


    public function exportExcel(){
        // return response()->streamDownload(function(){
        $list = [
            'filterStartDate' => $this->filterStartDate,
            'filterEndDate' => $this->filterEndDate,
            'filterDate' => $this->filterDate,
            'filterIncome' => $this->filterIncome,
            'filterExpense' => $this->filterExpense,
            'filterDescription' => $this->filterDescription,
            'filterContract' => $this->filterContract,
            'filterAccount' => $this->filterAccount,
            'q' => $this->q,
            'filterId' => $this->filterId
        ];
        return Excel::download(new LedgerExport($list,'Libro Mayor'),now().'.xlsx');
        // },now().'.xlsx');
    }



    public function export(){
        $data0 = Transaction::when($this->filterStartDate && $this->filterEndDate, function($q) {
            $q->whereBetween('date', [$this->filterStartDate, $this->filterEndDate]);
        })->when($this->filterDate != '', function ($q) {
            $q->where('date','like','%'.$this->filterDate.'%');
        })->when($this->filterIncome != '', function ($q) {
            $q->where(fn($sub) => $sub->where('type',1)->where('amount','like','%'.$this->filterIncome.'%'));
        })
          ->when($this->filterExpense != '', function ($q) {
              $q->where(fn($sub) => $sub->where('type',2)->where('amount','like','%'.$this->filterExpense.'%'));
          })
          ->when($this->filterDescription != '', function ($q) {
              $q->where('description','like','%'.$this->filterDescription.'%');
          })
          ->when($this->filterContract != '', function ($q) {
              $q->whereHas('contract', fn($c) => $c->where('cod','like','%'.$this->filterContract.'%'));
          })
          ->when($this->filterAccount != '', function ($q) {
              $q->whereHas('account', fn($a) => $a->where('name','like','%'.$this->filterAccount.'%'));
          })
          ->when($this->filterId != '', function ($q) {
              $q->where('id', $this->filterId);
          })
          ->orderBy('date','desc')->paginate($this->q);
        $pdf = Pdf::setOptions([
            'isHtmlParseEnabled' => true,
            'isRemoteEnabled' => true,
        ])->loadView('pdf.diarybook',[
            'data' => $data0,
            'user' => Auth::user(),
            'title' => 'Libro Mayor',
            'search' => [
                $this->filterDate,
                $this->filterStartDate,
                $this->filterEndDate,
                $this->filterIncome,
                $this->filterExpense,
                $this->filterDescription,
                $this->filterContract,
                $this->filterAccount,
                $this->filterId
            ]
        ]);
        $pdf->setPaper('letter', 'landscape');
        $pdf->render();
        return response()->streamDownload(function() use ($pdf){
            echo $pdf->stream();
        }, now().'.pdf');
    }


    public function render()
    {
        if(!Gate::allows('ledger-read'))
            abort('404');
        $data0 = Transaction::when($this->filterStartDate && $this->filterEndDate, function($q) {
            $q->whereBetween('date', [$this->filterStartDate, $this->filterEndDate]);
        })->when($this->filterDate != '', function ($q) {
            $q->where('date','like','%'.$this->filterDate.'%');
        })->when($this->filterIncome != '', function ($q) {
            $q->where(fn($sub) => $sub->where('type',1)->where('amount','like','%'.$this->filterIncome.'%'));
        })
          ->when($this->filterExpense != '', function ($q) {
              $q->where(fn($sub) => $sub->where('type',2)->where('amount','like','%'.$this->filterExpense.'%'));
          })
          ->when($this->filterDescription != '', function ($q) {
              $q->where('description','like','%'.$this->filterDescription.'%');
          })
          ->when($this->filterContract != '', function ($q) {
              $q->whereHas('contract', fn($c) => $c->where('cod','like','%'.$this->filterContract.'%'));
          })
          ->when($this->filterAccount != '', function ($q) {
              $q->whereHas('account', fn($a) => $a->where('name','like','%'.$this->filterAccount.'%'));
          })
          ->when($this->filterId != '', function ($q) {
              $q->where('id', $this->filterId);
          })
          ->orderBy('date','desc')
          ->paginate($this->q);

        $this->all = Transaction::count();

        $data1 = DB::table('transactions')
            ->join('accounts','accounts.id','=','transactions.account_id')
            ->select(
                'account_id',
                'accounts.name',
                DB::raw("SUM(CASE WHEN transactions.type = 1 THEN amount ELSE 0 END) -
                SUM(CASE WHEN transactions.type = 2 THEN amount ELSE 0 END) as balance")
            )
            ->where('transactions.deleted_at',null)
            ->groupBy('account_id', 'accounts.name')
            ->get();
        return view('livewire.ledger-view', compact(['data0','data1']));
    }
}
