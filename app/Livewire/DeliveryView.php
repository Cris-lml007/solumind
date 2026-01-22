<?php

namespace App\Livewire;

use App\Models\Delivery;
use App\Models\DeliveryDetailContract;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class DeliveryView extends Component
{

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



    public function render()
    {
        if(!Gate::allows('delivery-read'))
            abort('404');
        $heads = ['ID','Fecha','Codigo de Contrato','Importe (Bs)','Generar'];
        $data = Delivery::all();
        return view('livewire.delivery-view', compact(['heads','data']));
    }
}
