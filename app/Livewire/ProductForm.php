<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;
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

    #[Validate('required')]
    public $name;
    #[Validate('required')]
    public $price;
    public $description;
    #[Validate('required|integer|exists:suppliers')]
    public $nit;
    #[Validate('required')]
    public $category;
    public $img;

    #[Validate('required|unique:products,cod|unique:items,cod')]
    public $cod;
    public $alias;

    #[Locked]
    public Product $product;
    #[Locked]
    public Supplier $supplier;

    public $searchable;
    public $item_searchable;
    public $list = [];

    public function mount($id = null){
        try{
            $this->product = Product::findOrFail($id);
            $this->name = $this->product->name;
            $this->price = $this->product->price;
            $this->description = $this->product->description;
            $this->nit = $this->product->supplier->nit;
            $data = Storage::disk('imgProduct')->get($id);
            $this->img = 'data:image/png;base64,'.base64_encode($data);
            $this->category = $this->product->category_id;
            $this->alias = $this->product->category->alias;
            $this->cod = $this->product->cod;
        }catch(\Exception $e){
            $this->product = new Product();
        }
    }

    public function updatedItemSearchable(){
        $this->nit = $this->item_searchable;
    }

    public function updatedSearchable(){
        $this->list = Supplier::where('organization','like','%'.$this->searchable.'%')->orWhereHas('person',function(Builder $query){
            $query->where('name','like','%'.$this->searchable.'%');
        })->get();
    }

    public function updatedNit(){
        $this->code = Supplier::where('nit', $this->nit)->exists() ? 'nf-fa-circle_check text-success' :'nf-oct-x_circle text-danger';
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
    }

    public function save(){
        $this->validate();
        $this->product->cod = $this->cod;
        $this->product->name = $this->name;
        $this->product->price = $this->price;
        $this->product->description = $this->description;
        $this->product->category_id = $this->category;
        $this->product->supplier_id = Supplier::where('nit', $this->nit)->first()->id; // Assuming nit is the supplier_id
        if($this->product->save() && $this->img != null){
            $this->img->storeAs(path: '.', name: $this->product->id,options: 'imgProduct');
        }
        session()->flash('message', 'Producto guardado exitosamente.');
        return redirect()->route('dashboard.product');
    }

    public function remove(){
        $this->product->delete();
        $this->redirect(route('dashboard.product'));
    }

    public function render()
    {
        $categories = Category::all();
        return view('livewire.product-form',compact(['categories']));
    }
}
