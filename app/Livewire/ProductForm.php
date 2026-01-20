<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\WithFileUploads;
#[Title('Detalle de Producto')]
class ProductForm extends Component
{
    use WithFileUploads;

    public $listeners = ['remove'=> 'remove'];

    public $code = 'nf-fa-stop';

    #[Validate('required',as: 'nombre')]
    public $name;
    #[Validate('required',as:'precio')]
    public $price;
    public $description;
    #[Validate('required|integer|exists:suppliers')]
    public $nit;
    #[Validate('required',as:'categoria')]
    public $category;
    public $img;
    public $unit;

    public $cod;
    public $alias;
    public $size;

    #[Locked]
    public Product $product;
    #[Locked]
    public Supplier $supplier;

    public $searchable;
    public $item_searchable;
    public $list = [];

    public $status = 0;

    protected function rules(){
        return [
            'cod' => ['required', Rule::unique('products','cod')->ignore($this->product->id),Rule::unique('items','cod')]
        ];
    }

    public function mount($id = null){
        if(!Gate::allows('product-read'))
            abort('404');
        try{
            $this->list = Supplier::all();
            $this->product = Product::findOrFail($id);
            $this->name = $this->product->name;
            $this->price = $this->product->price;
            $this->description = $this->product->description;
            $this->nit = $this->product->supplier->nit;
            $data = Storage::disk('imgProduct')->get($this->product->cod);
            $this->img = 'data:image/png;base64,'.base64_encode($data);
            $this->category = $this->product->category_id;
            $this->alias = $this->product->category->alias;
            $this->cod = $this->product->cod;
            $this->size = $this->product->size;
            $this->unit = $this->product->unit;
            $this->status = 1;


            // $words = explode(' ', $this->name);
            // $s = 0;
            // foreach ($words as $word) {
            //     if(strlen($word) > 2)
            //         $s += 3;
            // }
            // $this->size = substr($this->cod,$s,strlen($this->cod));
            // $this->size ="adas";
        }catch(\Exception $e){
            $this->product = new Product();
            $this->supplier = new Supplier();
        }
    }

    public function updatedItemSearchable(){
        $this->nit = $this->item_searchable;
        $this->updatedNit();
    }

    public function updatedSearchable(){
        $this->list = Supplier::where('organization','like','%'.$this->searchable.'%')->orWhereHas('person',function(Builder $query){
            $query->where('name','like','%'.$this->searchable.'%');
        })->get();
    }

    public function updatedNit(){
        $this->code = Supplier::where('nit', $this->nit)->exists() ? 'nf-fa-circle_check text-success' :'nf-oct-x_circle text-danger';
        $this->supplier = Supplier::where('nit',$this->nit)->first() ?? new Supplier();
        $this->updatedSize();
    }

    public function updatedCategory(){
        $this->alias = Category::find($this->category)->alias ?? '';
    }

    public function updatedName(){
        $words = explode(' ', $this->name);
        $this->cod = "";
        foreach ($words as $word) {
            if(strlen($word) > 2)
                $this->cod = $this->cod . substr($word,0,3);
        }
        if($this->supplier != null)
            $this->cod = $this->cod . 's'.$this?->supplier?->id ?? '';
    }

    public function updatedName1(){
        $words = explode(' ', $this->name);
        $this->cod = "";
        foreach ($words as $word) {
            if(strlen($word) > 2)
                $this->cod = $this->cod . substr($word,0,3);
        }
    }

    public function updatedSize(){
        $this->updatedName1();
        if($this->supplier != null)
            $this->cod = $this->cod . $this->size . 's'.$this->supplier?->id ?? '';
    }

    public function save(){
        if(!Gate::allows('product-permission',3))
            abort('404');
        $this->validate();
        $this->product->cod = $this->cod;
        $this->product->name = $this->name;
        $this->product->price = $this->price;
        $this->product->description = $this->description;
        $this->product->category_id = $this->category;
        $this->product->size = $this->size;
        $this->product->unit = $this->unit;
        $this->product->supplier_id = Supplier::where('nit', $this->nit)->first()->id; // Assuming nit is the supplier_id
        if($this->product->save() && $this->img != null && gettype($this->img) != 'string'){
            $this->img->storeAs(path: '.', name: $this->product->cod,options: 'imgProduct');
        }
        session()->flash('message', 'Producto guardado exitosamente.');
        return $this->redirect(route('dashboard.product'), navigate: true);
    }

    public function remove(){
        if(!Gate::allows('product-permission',3))
            abort('404');
        Storage::disk('imgProduct')->delete($this->product->cod);
        $this->product->delete();
        $this->redirect(route('dashboard.product'), navigate: true);
    }

    public function render()
    {
        if(!Gate::allows('product-read'))
            abort('404');
        $categories = Category::all();
        return view('livewire.product-form',compact(['categories']));
    }
}
