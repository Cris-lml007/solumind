<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Contract;
use App\Models\ContractPartner;
use App\Models\DetailContract;
use App\Models\Item;
use App\Models\Partner;
use App\Models\Product;
use App\Models\Transaction;
use App\StatusContract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;

#[Title('Proforma')]
class ContractForm extends Component
{

    public $listeners = ['saveInversion'=> 'saveInversion'];


    public $searchable;
    public $clients = [];

    #[Validate('required|unique:contracts,cod', as: 'codigo')]
    public $code;
    #[Validate('required', as: 'producto')]
    public $searchable_item;
    public $description;

    public $code_product;
    public $name_product;
    public $description_product;
    public $purchase_price;
    public $sale_price;
    public $quantity;

    public $subtotal = 0;

    public $searchable_product;
    public $searchable_product_item;

    public $bill = 0;
    public $interest = 0;
    public $operating = 0;
    public $comission = 0;
    public $bank = 0;
    public $unexpected = 0;

    public $list = [];
    public $products = [];

    #[Locked]
    public Contract $contract;
    public $product;
    public DetailContract $detail;

    public $partners = [];
    // #[Validate('required|integer')]
    public $partner_ci;
    // #[Validate('required|integer|exists:partners,id')]
    public $partner_id;
    // #[Validate('required')]
    public $partner_type;
    // #[Validate('required')]
    public $partner_interest;
    // #[Validate('required')]
    public $partner_description;
    // #[Validate('required')]
    public $partner_amount;
    public $valide;
    public $payment;
    public $delivery;
    public $partner;
    public ContractPartner $contract_partner;

    public function mount($id = null){
        if(!Gate::allows('voucher-read'))
            abort('404');
        $this->partners = Partner::all();
        $this->clients = Client::all();
        $this->products = array_merge(Product::all()->all(),Item::all()->all());
        try{
            $this->contract = Contract::where('id',$id)->firstOrFail();
            $this->code = $this->contract->cod;
            $this->searchable_item = $this->contract->client_id;
            $this->description = $this->contract->description;
            $this->payment = $this->contract->payment;
            $this->valide = $this->contract->time_valide;
            $this->delivery = $this->contract->time_delivery;
            $this->list = $this->contract->detail_contract;
            // dd($this->list[0]->detailable()->withTrashed()->get());
            $this->detail = new DetailContract();
            $this->contract_partner = new ContractPartner();
        }catch(\Exception){
            $this->contract = new Contract();
        }
    }

    public function updated(){
        $this->subtotal = (((float) $this->sale_price ?? 0) * (float) ($this->bill ?? 0)) / 100 + (((float) $this->sale_price ?? 0) * (float) ($this->interest ?? 0)) / 100 + (((float) $this->sale_price ?? 0) * (float) ($this->operating ?? 0)) / 100 + (((float) $this->sale_price ?? 0) * (float) ($this->comission ?? 0)) / 100 + (((float) $this->sale_price ?? 0) * (float) ($this->bank ?? 0)) / 100 + (((float) $this->sale_price ?? 0) * (float) ($this->unexpected ?? 0)) / 100 + (float) $this->purchase_price ?? 0;
    }

    public function updatedPartnerId(){
        if($this->partner_id != '0'){
            $this->partner = Partner::find($this->partner_id);
            $this->partner_ci = $this->partner->person->ci;
            $this->partner_id = $this->partner->id;
            return;
        }
        $this->partner = null;
        $this->partner_ci = null;
        $this->partner_id = null;
    }

    public function updatedPartnerCi(){
        if(!empty($this->partner_ci)){
            $this->partner = Partner::whereHas('person',function(Builder $query){
                $query->where('ci',$this->partner_ci);
            })->first();
            $this->partner_id = $this->partner?->id;
            return;
        }
        $this->partner = null;
        $this->partner_ci = null;
        $this->partner_id = null;
    }

    public function loadInversion($id){
        $this->contract_partner = ContractPartner::find($id);
        $this->partner_type = $this->contract_partner->type;
        $this->partner_interest = $this->contract_partner->interest;
        $this->partner_description = $this->contract_partner->description;
        $this->partner_amount = $this->contract_partner->amount;
        $this->partner_id = $this->contract_partner->partner->id;
        $this->partner_ci = $this->contract_partner->partner->person->ci;
        $this->partner = $this->contract_partner->partner;
    }

    public function saveInversion(){
        if(!Gate::allows('voucher-permission',3))
            abort('404');
        Validator::make(
            [
                'partner_ci' => $this->partner_ci,
                'partner_id' => $this->partner_id,
                'partner_interest' => $this->partner_interest
            ],
            [
                'partner_ci' => 'required|exists:people,ci',
                'partner_interest' => 'integer|max:'.(100 - $this->contract->inversions()->sum('interest') + ($this->contract_partner->interest ?? 0) )
                // 'partner_id' => Rule::unique('contract_partners')->where(fn($query) => $query->where('contract_id',$this->contract->id))
            ],[],[
                'partner_ci' => 'CI',
                'partner_id' => 'socio',
                'partner_interest' => '% interes'
            ]
        )->validate();

        if($this->contract_partner->id == null)
            $this->contract_partner = new ContractPartner();
        // $this->contract_partner = new ContractPartner();
        // $this->contract_partner->type = $this->partner_type;
        $this->contract_partner->interest = $this->partner_interest;
        // $this->contract_partner->description = $this->partner_description;
        $this->contract_partner->contract_id = $this->contract->id;
        $this->contract_partner->partner_id = $this->partner->id;
        $this->contract_partner->save();
        $this->contract_partner = new ContractPartner();
        $this->reset('partner_ci',
            'partner_type',
            'partner_id',
            'partner_interest',
            'partner_description'
        );
        $this->js("$('#modal-partner').modal('hide')");
        // $this->redirect(route('dashboard.proof.form',$this->contract->id));
    }

    public function deleteInversion($id){
        if(!Gate::allows('voucher-permission',3))
            abort('404');
        ContractPartner::find($id)->delete();
    }

    public function updatedSearchableProduct(){
        if(!empty($this->searchable_product)){
            $a = Item::where('cod','like','%'.$this->searchable_product.'%')->orWhere('name','like','%'. $this->searchable_product .'%')->get();
            $b = Product::where('cod','like','%'.$this->searchable_product.'%')->orWhere('name','like','%'. $this->searchable_product .'%')->get();

            $this->products = array_merge($a->all(),$b->all());
        }else{
            $this->products = array_merge(Product::all()->all(),Item::all()->all());
        }
    }

    public function updatedSearchableProductItem(){
        $a = Product::where('cod',$this->searchable_product_item)->first();
        $b = Item::where('cod',$this->searchable_product_item)->first();
        if($a != null){
            $this->code_product = $a->cod;
            $this->name_product = $a->name;
            $this->purchase_price = $a->price;
            $this->product = $a;
        }else if($b !=null){
            $this->code_product = $b->cod;
            $this->name_product = $b->name;
            $this->purchase_price = $b->price ?? 0 + $b->extra ?? 0;
            $this->product = $b;
        }else{
            $this->code_product = null;
            $this->name_product = null;
        }
    }

    public function updatedCodeProduct(){
        $a = Product::where('cod',$this->code_product)->first();
        $b = Item::where('cod',$this->code_product)->first();
        if($a != null){
            $this->name_product = $a->name;
            $this->purchase_price = $a->price;
            $this->product = $a;
        }else if($b !=null){
            $this->name_product = $b->name;
            $this->purchase_price = $b->price ?? 0 + $b->extra ?? 0;
            $this->product = $b;
        }else{
            $this->name_product = null;
        }
    }

    public function updatedSearchable(){
        if(!empty($this->searchable))
            $this->clients = Client::where('organization','like','%'.$this->searchable.'%')->orWhereHas('person',function(Builder $query){
                $query->where('name','like','%'.$this->searchable.'%');
            })->get();
        else
            $this->clients = Client::all();
    }

    public function create(){
        if(!Gate::allows('voucher-permission',3))
            abort('404');
        $this->validate();
        $this->contract->cod = $this->code;
        $this->contract->client_id = $this->searchable_item;
        $this->contract->description = $this->description;
        $this->contract->save();
        $this->redirect(route('dashboard.proof'));
    }

    public function save(){
        if(!Gate::allows('voucher-permission',3))
            abort('404');
        Validator::make([
            'code' => $this->code,
            'client_id' => $this->searchable_item,
        ],[
            'code' => ['required', Rule::unique('contracts','cod')->ignore($this->contract->id)],
            'client_id' => 'required|exists:clients,id'
        ],[],[
            'code' => 'codigo',
            'client_id' => 'cliente'
        ])->validate();
        $this->contract->cod = $this->code;
        $this->contract->client_id = $this->searchable_item;
        $this->contract->description = $this->description;
        $this->contract->payment = $this->payment;
        $this->contract->time_delivery = $this->delivery;
        $this->contract->time_valide = $this->valide;
        $this->contract->save();
        $this->redirect(route('dashboard.proof'));
    }

    public function loadProduct($id){
        $this->detail = DetailContract::find($id);
        $this->bill = $this->detail->bill;
        $this->interest = $this->detail->interest;
        $this->operating = $this->detail->operating;
        $this->comission = $this->detail->comission;
        $this->bank = $this->detail->bank;
        $this->unexpected = $this->detail->unexpected;
        $this->purchase_price = $this->detail->purchase_price;
        $this->sale_price = $this->detail->sale_price;
        $this->quantity = $this->detail->quantity;
        $this->description_product = $this->detail->description;
        $this->code_product = $this->detail->detailable()->withTrashed()->first()->cod;
        $this->name_product = $this->detail->detailable()->withTrashed()->first()->name;
        $this->product = $this->detail->detailable()->withTrashed()->first();
        $this->updated();
    }

    public function add(){
        if(!Gate::allows('voucher-permission',3))
            abort('404');
        Validator::make(
            [
                'code_product' => $this->code_product,
                'purchase_price' => $this->purchase_price,
                'sale_price' => $this->sale_price,
                'quantity' => $this->quantity
            ],
            [
                'code_product' => 'required',
                'purchase_price' => 'required',
                'sale_price' => 'required',
                'quantity' => 'required'
            ],[],[
                'code_product' => 'codigo de producto',
                'purchase_price' => 'precio de adquisición',
                'sale_price' => 'precio de venta',
                'quantity' => 'cantidad'
            ]
        )->validate();
        if($this->detail->id == null){
            $this->detail = DetailContract::create([
                'contract_id' => $this->contract->id,
                'description' => $this->description_product,
                'bill' => $this->bill,
                'interest' => $this->interest,
                'operating' => $this->operating,
                'comission' => $this->comission,
                'bank' => $this->bank,
                'unexpected' => $this->unexpected,
                'purchase_price' => $this->purchase_price,
                'quantity' => $this->quantity,
                'sale_price' => $this->sale_price,
                'purchase_total' => (float)$this->subtotal * (float)$this->quantity
            ]);
        }else{
            $this->detail->bill = $this->bill;
            $this->detail->interest = $this->interest;
            $this->detail->operating = $this->operating;
            $this->detail->comission = $this->comission;
            $this->detail->bank = $this->bank;
            $this->detail->unexpected = $this->unexpected;
            $this->detail->purchase_price = $this->purchase_price;
            $this->detail->sale_price = $this->sale_price;
            $this->detail->quantity = $this->quantity;
            $this->detail->description = $this->description_product;
            $this->detail->purchase_total = (float)$this->subtotal * (float)$this->quantity;
        }
        $this->detail->detailable()->associate($this->product);
        $this->detail->save();
        $this->contract->refresh();
        $this->list = $this->contract->detail_contract;
        $this->detail = new DetailContract();
        // $this->reset(['code_product','name_product','description_product','bill','interest','operating','comission','bank','unexpected','purchase_price','quantity','sale_price','subtotal']);
        $this->reset(['code_product','name_product','description_product','purchase_price','quantity','sale_price','subtotal']);
    }

    public function aprove(){
        if(!Gate::allows('voucher-permission',3))
            abort('404');
        $this->contract->status = StatusContract::CONTRACT->value;
        $this->contract->save();
        $this->redirect(route('dashboard.proof'));
    }

    public function finish(){
        if(!Gate::allows('voucher-permission',3))
            abort('404');
        $this->contract->status = StatusContract::CONTRACT_COMPLETE->value;
        $this->contract->save();
        $this->redirect(route('dashboard.proof'));
    }

    public function proofFail(){
        if(!Gate::allows('voucher-permission',3))
            abort('404');
        $this->contract->status = StatusContract::PROFORMA_FAIL->value;
        $this->contract->save();
        $this->redirect(route('dashboard.proof'));
    }

    public function contractFail(){
        if(!Gate::allows('voucher-permission',3))
            abort('404');
        $this->contract->status = StatusContract::CONTRACT_FAIL->value;
        $this->contract->save();
        $this->redirect(route('dashboard.proof'));
    }


    public function remove(){
        if(!Gate::allows('voucher-permission',3))
            abort('404');
        $this->contract->delete();
        $this->redirect(route('dashboard.proof'));
    }

    public function delete($id){
        if(!Gate::allows('voucher-permission',3))
            abort('404');
        DetailContract::find($id)->delete();
        $this->contract->refresh();
        $this->list = $this->contract->detail_contract;
    }

    public function render()
    {
        if(!Gate::allows('voucher-read'))
            abort('404');
        $heads = ['ID', 'Producto', 'Precio (Bs)', 'Cantidad', 'Subtotal', 'Acciones'];
        $transactions = Transaction::where('contract_id',$this->contract->id)->get();
        return view('livewire.contract-form',compact(['heads','transactions']));
    }
}
