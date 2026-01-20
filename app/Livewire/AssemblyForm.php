<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\DetailItem;
use App\Models\Item;
use App\Models\Product;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;

class AssemblyForm extends Component
{
    use WithFileUploads;

    #[Validate('required|unique:items,cod|unique:products,cod', as: 'codigo')]
    public $code;
    #[Validate('required',as: 'nombre')]
    public $name;
    public $description;
    public $price = 0;
    public $extra = 0;
    #[Validate('required',as:'categoria')]
    public $category;
    public $alias;
    public $img;
    public $unit;

    public $search;
    public $list;
    public $na;
    public $s;
    public $p;
    public $q;
    public $ipd;

    public $is_update = false;

    #[Locked]
    public $products = [];
    #[Locked]
    public $deletes = [];

    #[Locked]
    public Item $item;

    public $listeners = ['add' => 'add','remove' => 'remove'];

    public function mount($code = null){
        if(!Gate::allows('item-read'))
            abort('404');
        $this->list = Product::all();
        try{
            $this->item = Item::where('cod',$code)->firstOrFail();
            $this->code = $code;
            $this->alias = $this->item->category->alias;
            $this->name = $this->item->name;
            $this->description = $this->item->description;
            $this->price = $this->item->price;
            $this->extra = $this->item->extra;
            $this->category = $this->item->category_id;
            $this->unit = $this->item->unit;
            $data = Storage::disk('imgProduct')->get($this->item->cod);
            $this->img = 'data:image/png;base64,'.base64_encode($data);
            foreach ($this->item->detail_items()->withTrashed()->get() as $value) {
                $this->products [] = [
                    'ipd' => $value->id,
                    'id' => $value->product_id,
                    'name' => $value->product->name,
                    'price' => $value->product->price,
                    'quantity' => $value->quantity
                ];
            }
        }catch(\Exception $e){
            $this->item = new Item();
        }
    }

    public function updatedName(){
        $words = explode(' ', $this->name);
        $this->code = "";
        foreach ($words as $word) {
            if(strlen($word) > 2)
                $this->code = $this->code . substr($word,0,3);
        }
    }

    public function updatedSearch(){
        if(!empty($this->search)){
            $this->list = Product::where('name','like','%'.$this->search.'%')->get();
        }else{
            $this->list = Product::all();
        }
        $this->p = null;
        $this->s = null;
        $this->na = null;
        //$this->dispatch('openModal');
    }

    public function updatedCategory(){
        $this->alias = Category::find($this->category)->alias ?? '';
    }

    public function updatedNa(){
        $obj = Product::find($this->na);
        if($obj != null){
            $this->p = $obj->price;
            $this->s = $obj->supplier->organization;
        }else{
            $this->p = null;
            $this->s = null;
        }
        //$this->dispatch('openModal');
    }

    public function add($id, $ipd,$name, $quantity, $price){
        if($this->is_update){
            foreach ($this->products as $key => $value) {
                if($value['id'] == $id){
                    $this->price -= (int)$value['price'] * (int)$value['quantity'];
                    $this->price += (int)$quantity * (int)$price;
                    $this->products[$key] = [
                        'ipd' => $ipd,
                        'id' => $id,
                        'name' => $name,
                        'quantity' => $quantity,
                        'price' => $price
                    ];
                    // $this->products [] = $o;
                    // unset($this->products[$key]);
                    $this->is_update = false;
                    $this->ipd = null;
                    $this->na = null;
                    $this->s = null;
                    $this->p = null;
                    $this->q = null;
                    return;
                }
            }
        }
        foreach($this->products as $i){
            if($i['id'] == $id) return $this->js("Swal.fire('Producto ya añadido.')");
        }
        $this->price += (int)$quantity * (int)$price;
        $this->products[] = ['id' => $id, 'name' => $name ,'quantity' => $quantity, 'price' => $price];
        $this->ipd = null;
        $this->na = null;
        $this->s = null;
        $this->p = null;
        $this->q = null;
    }

    public function delete($id){
        foreach ($this->products as $key => $value) {
            if($value['id'] == $id){
                $this->price -= $value['price'] * $value['quantity'];
                $this->deletes [] = $this->products[$key];
                unset($this->products[$key]);
                return;
            }
        }
    }

    public function update($id){
        $obj = $this->item->detail_items()->where('product_id',$id)->first();
        foreach ($this->products as $key => $value) {
            if($value['id'] == $id){
                $this->na = $value['id'];
                $this->ipd = $value['ipd'];
                $this->s = $obj->product->supplier->organization;
                $this->q = $value['quantity'];
                $this->p = $value['price'];
                $this->is_update = true;
                return;
            }
        }
        // if($obj != null){
        // }
    }

    public function create(){
        if(!Gate::allows('item-permission',3))
            abort('404');
        $this->validate();
        $this->item = new Item();
        $this->item->cod = $this->code;
        $this->item->name = $this->name;
        $this->item->category_id = $this->category;
        $this->item->save();
        $this->item = new Item();

        $this->code = '';
        $this->name = '';
        $this->category = null;
        $this->dispatch('render')->to(AssemblyView::class);
        // $this->redirect(route('dashboard.assembly'));
    }

    public function save(){
        if(!Gate::allows('item-permission',3))
            abort('404');
        Validator::make([
            'code' => $this->code,
            'name' => $this->name
        ],[
            'code' => ['required',Rule::unique('items','cod')->ignore($this->item->id),Rule::unique('products','cod')],
            'name' => 'required'
        ],[

        ],[
            'code' => 'codigo',
            'name' => 'nombre'
        ])->validate();
        $this->item->category_id = $this->category;
        $this->item->cod = $this->code;
        $this->item->name = $this->name;
        $this->item->price = $this->price;
        $this->item->extra = empty($this->extra) ? 0 : $this->extra;
        $this->item->description = $this->description;
        $this->item->unit = $this->unit;
        // $this->item->save();
        if($this->item->save() && $this->img != null && gettype($this->img) != 'string'){
            $this->img->storeAs(path: '.', name: $this->item->cod,options: 'imgProduct');
        }

        foreach ($this->deletes as $key => $value) {
            if($value['ipd'] != null) $this->item->detail_items()->where('id',$value['ipd'])->delete();
        }

        $data = [];
        foreach ($this->products as $key => $value) {
            $data [] = ['id' => $value['ipd'] ?? null, 'product_id' => $value['id'], 'quantity' => $value['quantity']];
        }
        $this->item->detail_items()->upsert(
            $data,
            ['id']
        );
        $this->redirect(route('dashboard.assembly'));
    }

    public function remove(){
        if(!Gate::allows('item-permission',3))
            abort('404');
        Storage::disk('imgProduct')->delete($this->item->cod);
        $this->item->delete();
        $this->redirect(route('dashboard.assembly'));
    }


    public function render()
    {
        if(!Gate::allows('item-read'))
            abort('404');
        $categories = Category::all();
        return view('livewire.assembly-form',compact(['categories']));
    }
}
