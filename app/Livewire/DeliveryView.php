<?php

namespace App\Livewire;

use App\Models\Delivery;
use App\Models\DeliveryDetailContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithPagination;

class DeliveryView extends Component
{
    use WithPagination;

    public $listeners = ['tablePageDelivery','tableSearchDelivery'];

    public $search = '';

    public function valideWithPassword($password)
    {
        if(!Gate::allows('transaction-permission',3))
            abort('404');
        // Valida la contraseña (ejemplo: contra la del usuario actual)
        if (!Hash::check($password, Auth::user()->password)) {
            return ['success' => false, 'message' => 'Contraseña incorrecta'];
        }
        return ['success' => true];
    }

    public function remove($id){
        $this->dispatch('deliveryDeleted', ['id' => $id]);
    }

    public function removeDelivery(Delivery $delivery){
        // dd($delivery);
        if($delivery->id != null){
            $delivery->transaction()->forceDelete();
            DeliveryDetailContract::where('delivery_id',$delivery->id)->forceDelete();
            $delivery->forceDelete();
        }
        return $this->redirect(route('dashboard.delivery'),navigate: true);
    }

    public function updatedSearch(){
        $this->render();
    }

    public function tablePageDelivery($page){
        $this->setPage($page);
    }

    public function tableSearchDelivery($search){
        $this->search = $search;
        $this->render();
    }

    public function render()
    {
        if(!Gate::allows('delivery-read'))
            abort('404');
        $heads = ['ID','Fecha','Codigo de Contrato','Importe (Bs)','Generar'];
        if($this->search != ''){
            $data = Delivery::where('id','like','%'.$this->search.'%')
                ->orWhere('date','like','%'.$this->search.'%')
                ->orWhereHas('contract',function(Builder $builder){
                    $builder->where('cod','like','%'.$this->search.'%');
                })->orderBy('date','desc')->paginate();
        }else{
            $data = Delivery::orderBy('date','desc')->paginate();
        }
        return view('livewire.delivery-view', compact(['heads','data']));
    }
}
