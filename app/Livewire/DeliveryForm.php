<?php

namespace App\Livewire;

use App\AssignedTransaction;
use App\Models\Contract;
use App\Models\Delivery;
use App\Models\DetailContract;
use App\Models\Transaction;
use App\StatusContract;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Number;
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

    public $is_canceled = false;

    public $max_quantity;

    public $contracts =[];
    public $product;

    public $balance;

    public $edit = 0;

    public $delivery_id;

    public $search_product;

    public function setNow(){
        $this->date = Carbon::now()->format('Y-m-d');
    }

    public function updatedSearchProduct(){
        if(!empty($this->search_product)){
            $this->products = $this->contract->detail_contract()->whereHas('detailable', function($query){
                $query->where('name','like','%'.$this->search_product.'%');
            })->get();
        }else{
            $this->products = $this->contract->detail_contract;
        }
    }

    public function mount($id = null){
        if(!Gate::allows('delivery-read'))
            abort('404');
        if(Delivery::where('id',$id)->exists()){
            if(Delivery::find($id)->contract->status != StatusContract::CONTRACT)
                abort('404');
        }
        $this->contracts = Contract::where('status',StatusContract::CONTRACT)->get();

        try {
            $delivery = Delivery::findorFail($id);
            $this->date = $delivery->date;
            $this->contract_cod = $delivery->contract()->withTrashed()->first()->cod;
            $this->receiver_by = $delivery->received_by;
            // dd($delivery);
            // $this->amount = Number::parseFloat($delivery->amount ?? 0);
            $this->is_canceled = $delivery->is_canceled == 2 ? true : false;
            $this->contract = $delivery->contract;

            // dd($delivery->detail_contract[0]->pivot->quantity * $delivery->detail_contract[0]->sale_price);
            foreach ($delivery->detail_contract as $item) {
                $this->list[] = [
                    'name'     => $item->detailable->name . ' ' . ($item->detailable->size ?? ''),
                    'id'       => $item->id,
                    'quantity' => (int) $item->pivot->quantity,
                ];
                // dd($this->amount);
                // if($this->amount == 0)
                    $this->amount += $item->sale_price * $item->pivot->quantity;
            }
            $this->edit = 1;
            $this->delivery_id = $id;
        } catch (\Throwable) {
            # code...
        }
    }

    public function canceled(){
        $delivery = Delivery::find($this->delivery_id);
        $delivery->amount = $this->amount;
        $delivery->is_canceled = 2;
        $this->balance =$this->contract?->detail_contract()?->sum(DB::raw('sale_price*quantity')) -
            $this->contract->transactions()->where('type',1)->where('assigned',AssignedTransaction::PAYMENT)->sum('amount');
        // dd($this->balance);
        $delivery->save();
        Transaction::create([
            'contract_id' => $delivery->contract_id,
            'delivery_id' => $delivery->id,
            'type' => 1,
            'description' => "Pago parcial de ". Number::format($this->amount, precision: 2) ." Bs a cuenta del contrato {$delivery->contract->cod}, referido a entrega N° {$delivery->id}. Saldo pendiente: ". Number::format(($this->balance - $this->amount), precision: 2) ." Bs.",
            'account_id' => $delivery->contract->client->account->id,
            'date' => Carbon::now(),
            'amount' => $this->amount,
            'assigned' => AssignedTransaction::PAYMENT
        ]);
        return $this->redirect(route('dashboard.delivery'), navigate: true);
    }

    public function updatedContractCod(){
        $this->contract = Contract::where('cod',$this->contract_cod)->first();
        $this->products = $this?->contract?->detail_contract ?? [];
        $this->balance =$this->contract?->detail_contract()?->sum(DB::raw('sale_price*quantity')) -
            $this->contract->transactions()->where('type',1)->where('assigned',AssignedTransaction::PAYMENT)->sum('amount');
    }

    public function updatedDetailId(){
        $this->max_quantity = $this->contract->detail_contract()->where('id',$this->detail_id)->first()->quantity -
            $this->contract->detail_contract()->find($this->detail_id)->deliveries()->sum('quantity');
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

            $this->amount += $detail->sale_price * $this->quantity;

            $this->detail_id = null;
            $this->quantity = null;
    }

    public function delete($id){
        foreach ($this->list as $key => $value) {
            if($value['id'] == $id){
                $this->amount -= DetailContract::withTrashed()->find($id)->sale_price * $value['quantity'];
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
        // dd($this->balance);
        $delivery = new Delivery();
        $delivery->contract_id = $this->contract->id;
        $delivery->date = $this->date;
        $delivery->received_by = $this->receiver_by;
        $delivery->amount = $this->amount;
        $delivery->is_canceled = $this->is_canceled == true ? 2 : 1;
        $delivery->save();
        $delivery->detail_contract()->attach($l);

        if(($this->amount != null || $this->amount >0) && $this->is_canceled){
            Transaction::create([
                'contract_id' => $this->contract->id,
                'delivery_id' => $delivery->id,
                'type' => 1,
                'description' => "Pago parcial de ". Number::format($this->amount, precision: 2) ." Bs a cuenta del contrato {$delivery->contract->cod}, referido a entrega N° {$delivery->id}. Saldo pendiente: ". Number::format(($this->balance - $this->amount), precision: 2) ." Bs.",
                'account_id' => $this->contract->client->account->id,
                'date' => $this->date,
                'amount' => $this->amount,
                'assigned' => AssignedTransaction::PAYMENT
            ]);
        }
        return $this->redirect(route('dashboard.delivery'), navigate:true);
    }


    public function render()
    {
        if(!Gate::allows('delivery-read'))
            abort('404');
        return view('livewire.delivery-form');
    }
}
