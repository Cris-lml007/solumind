<?php

namespace App\Exports;

use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class DiaryBookExport implements FromView, ShouldAutoSize
{

    public $search;
    public $title;
    public $paginate;


    public function __construct($search = null,$title) {
        $this->search = $search;
        $this->title = $title;
    }

    /**
    // * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
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
              ->get();
        }else{
            $data = Transaction::whereDate('date',Carbon::now())->orderBy('date','desc')->get();
        }
        // $data = Transaction::limit(20)->get();
        $title = $this->title;
        return view('exports.diarybook',compact(['data','title']));
    }
}
