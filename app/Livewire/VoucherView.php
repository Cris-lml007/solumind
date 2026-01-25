<?php

namespace App\Livewire;

use App\Models\Contract;
use App\Models\Transaction;
use App\StatusContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\WithPagination;

class VoucherView extends Component
{
    use WithPagination;


    public $status_tab = 1;

    public function setTab($id){
        $this->status_tab = $id;
    }


    public $searchComprobante;

    #[On('tableSearchComprobante')]
    public function tableSearchComprobante($search){
        dd($this->status_tab);
        $this->searchComprobante = $search;
        $this->render();
    }


    #[On('tablePageComprobante')]
    public function tablePageComprobante($page){
        $this->setPage($page, pageName: 'comprobante');
    }
    public $searchProforma;

    #[On('tableSearchProforma')]
    public function tableSearchProforma($search){
        $this->searchProforma = $search;
        $this->render();
    }


    #[On('tablePageProforma')]
    public function tablePageProforma($page){
        $this->setPage($page, pageName: 'proforma');
    }
    public $searchContrato;

    #[On('tableSearchContrato')]
    public function tableSearchContrato($search){
        $this->searchContrato = $search;
        $this->render();
    }


    #[On('tablePageContrato')]
    public function tablePageContrato($page){
        $this->setPage($page, pageName: 'contrato');
    }



    public function render()
    {
        if(!Gate::allows('voucher-read'))
            abort('404');
        $config = ['order' => [0,'desc'],'columns' => [null, null, null, null, null, ['orderable' => false, 'searchable' => false]]];

        if($this->searchComprobante != null && $this->searchComprobante != ''){
            $comprobante = Transaction::where('id','like','%'.$this->searchComprobante.'%')
                ->orWhere('date','like','%'.$this->searchComprobante.'%')
                ->orWhere('description','like','%'.$this->searchComprobante.'%')
                ->paginate(10, pageName: 'comprobante');
        }else{
            $comprobante = Transaction::paginate(10, pageName: 'comprobante');
        }
        if($this->searchProforma != null && $this->searchProforma != ''){
            $proforma = Contract::Where('status','<',StatusContract::CONTRACT->value)
                ->where(function(Builder $q){
                    $q->where('id','like','%'.$this->searchProforma.'%')
                      ->orWhere('cod','like','%'.$this->searchProforma.'%')
                      ->orWhereHas('client',function(Builder $builder){
                          $builder->whereHas('person',function(Builder $b){
                              $b->where('name','like','%'.$this->searchProforma.'%');
                          })->orWhere('organization','like','%'.$this->searchProforma.'%');
                      })->orWhere('create_at','like','%'.$this->searchProforma.'%');
                })->paginate(10, pageName: 'proforma');
        }else{
            $proforma = Contract::Where('status','<',StatusContract::CONTRACT->value)->paginate(10,pageName: 'proforma');
        }
        if($this->searchContrato != null && $this->searchContrato != ''){
            $contrato = Contract::Where('status','>=',StatusContract::CONTRACT->value)
                ->where(function(Builder $q){
                    $q->where('id','like','%'.$this->searchContrato.'%')
                      ->orWhere('cod','like','%'.$this->searchContrato.'%')
                      ->orWhereHas('client',function(Builder $builder){
                          $builder->whereHas('person',function(Builder $b){
                              $b->where('name','like','%'.$this->searchContrato.'%');
                          });
                      })->orWhere('time_delivery','like','%'.$this->searchContrato.'%');
                })->paginate(10, pageName: 'contrato');
        }else{
            $contrato = Contract::where('status','>=',StatusContract::CONTRACT->value)->paginate(10, pageName: 'contrato');
        }


        $data = [
            'comprobantes' => $comprobante,
            'proformas' => $proforma,
            'contratos' => $contrato
        ];

        $heads = [
            'comprobantes' => ['ID', 'Fecha', 'Tipo', 'Descripción', 'Monto (Bs)', 'Acciones'],
            'proformas' => ['ID', 'Codigo', 'Cliente', 'Fecha Emisión', 'Estado', 'Acciones'],
            'contratos' => ['ID', 'Codigo', 'Cliente', 'Plazo de Entrega', 'Estado', 'Acciones']
        ];
        return view('livewire.voucher-view', compact(['heads','data','config']));
    }
}
