<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class LedgerExport implements FromView, ShouldAutoSize
{

    public $list;

    public $filterStartDate;
    public $filterEndDate;
    public $filterDate;
    public $filterIncome;
    public $filterExpense;
    public $filterDescription;
    public $filterContract;
    public $filterAccount;
    public $q = 15;

    public $filterId;


    public function __construct($list = null){
        $this->list = $list;
    }


    /**
     * @return \Illuminate\Support\Collection
     */
    public function view(): View
    {
        $this->filterStartDate = $this->list['filterStartDate'] ?? null;
        $this->filterEndDate = $this->list['filterEndDate'] ?? null;
        $this->filterDate = $this->list['filterDate'] ?? '';
        $this->filterIncome = $this->list['filterIncome'] ?? '';
        $this->filterExpense = $this->list['filterExpense'] ?? '';
        $this->filterDescription = $this->list['filterDescription'] ?? '';
        $this->filterContract = $this->list['filterContract'] ?? '';
        $this->filterAccount = $this->list['filterAccount'] ?? '';
        $this->filterId = $this->list['filterId'] ?? '';
        $this->q = $this->list['q'] ?? 15;


        $data = Transaction::when($this->filterStartDate && $this->filterEndDate, function($q) {
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
          ->orderBy('date','asc')->paginate($this->q);

        return view('exports.ledger', compact('data'));
    }
}
