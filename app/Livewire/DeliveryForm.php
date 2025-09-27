<?php

namespace App\Livewire;

use App\Models\Contract;
use App\Models\Delivery;
use App\Models\DetailContract;
use App\Models\Transaction;
use App\StatusContract;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Validate;
use Livewire\Component;

class DeliveryForm extends Component
{
    public $list = [];
    public $products = [];
    public $detail_id;
    public $quantity;
    public $amount;
    #[Validate('required|date', as: 'fecha')]
    public $date;
    public $contract;
    #[Validate('required',as: 'codigo de contrato')]
    public $contract_cod;
    #[Validate('|required', as: 'recibido por')]
    public $receiver_by;

    public $max_quantity;

    public $contracts =[];
    public $product;

    public $balance;

    public function mount(){
        if(!Gate::allows('delivery-read'))
            abort('404');
        $this->contracts = Contract::where('status',StatusContract::CONTRACT)->get();
    }

    public function updatedContractCod(){
        $this->contract = Contract::where('cod',$this->contract_cod)->first();
        $this->products = $this?->contract?->detail_contract ?? [];
        $this->balance =$this->contract?->detail_contract()?->sum(DB::raw('sale_price*quantity')) -
            $this->contract->transactions()->where('account_id',2)->sum('amount');
    }

    public function updatedDetailId(){
        // dd($this->contract->detail_contract()->find($this->detail_id)->deliveries()->sum('quantity'));
        $this->max_quantity = $this->contract->detail_contract()->where('id',$this->detail_id)->first()->quantity -
            $this->contract->detail_contract()->find($this->detail_id)->deliveries()->sum('quantity');
        // dd($this->max_quantity);
    }

    public function add(){
        if (empty($this->detail_id) || empty($this->quantity) || !is_numeric($this->quantity) || $this->quantity <= 0) {
            return $this->js(<<<'JS'
            Swal.fire({
            title: 'Campos Vacíos',
            text: 'Por favor seleccione un producto y una cantidad válida',
            icon: 'warning'
            });
            JS);
            }
            if($this->quantity > $this->max_quantity){
            return $this->js(<<<'JS'
            Swal.fire({
            title: 'Cantidad Invalida',
            text: 'Por favor ingrese una cantidad valida',
            icon: 'warning'
            });
            JS);
            }
            $listIds = array_column($this->list ?? [], 'id');
            $key = array_search($this->detail_id, $listIds);

            if ($key !== false) {
            return $this->js(<<<'JS'
            Swal.fire({
            title: 'Producto Existente',
            text: 'Para cambiar la cantidad quite el producto de la lista y vuelva a agregarlo',
            icon: 'warning'
            });
            JS
            );
            }

            $detail = DetailContract::withTrashed()->find($this->detail_id);
            $name = null;
            if ($detail) {
                $detailable = $detail->detailable()->withTrashed()->first();
                $words = explode(' ', $detailable->name);
                $s = 0;
                foreach ($words as $word) {
                    if (strlen($word) > 2) {
                        $s += 3;
                    }
                }
                $size = substr($detailable->cod, $s, strlen($detailable->cod));
                $name = $detailable ? (($detailable->name ?? null) . $size) : null;
            }

            $this->list[] = [
                'name'     => $name,
                'id'       => $this->detail_id,
                'quantity' => (int) $this->quantity,
            ];

            $this->detail_id = null;
            $this->quantity = null;
    }

    public function delete($id){
        foreach ($this->list as $key => $value) {
            if($value['id'] == $id){
                array_splice($this->list,$key,1);
                return;
            }
        }
    }

    public function save(){
        if(!Gate::allows('delivery-permission',3))
            abort('404');
        if(empty($this->list)){
            return $this->js("Swal.fire({title: 'Lista Vacia',text: 'La lista de productos esta vacia.','icon':'warning'})");
        }
        $this->validate();
        $l = [];
        foreach ($this->list as $value) {
            $l[$value['id']] = ['quantity' => $value['quantity']];
        }
        $delivery = new Delivery();
        $delivery->contract_id = $this->contract->id;
        $delivery->amount = $this->amount;
        $delivery->date = $this->date;
        $delivery->received_by = $this->receiver_by;
        $delivery->save();
        $delivery->detail_contract()->attach($l);

        // if($this->amount != null || $this->amount >0){
        //     Transaction::create([
        //         'contract_id' => $this->contract->id,
        //         'delivery_id' => $delivery->id,
        //         'type' => 1,
        //         'description' => "Pago parcial de {$this->amount} Bs a cuenta del contrato {$this->contract->id}, referido a entrega {$delivery->id}. Saldo pendiente: ". ($this->balance - $this->amount) ."Bs.",
        //         'account_id' => 2,
        //         'date' => $this->date,
        //         'amount' => $this->amount
        //     ]);
        // }
        return $this->redirect(route('dashboard.delivery'));
    }


    public function render()
    {
        if(!Gate::allows('delivery-read'))
            abort('404');
        return view('livewire.delivery-form');
    }
}
