<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
#[Title('Detalle de Producto')]
class ProductForm extends Component
{
    use WithFileUploads;

    public $code = 'nf-fa-stop';

    public $name;
    public $price;
    public $description;
    public $nit;
    public $category;
    public $img;

    #[Locked]
    public Product $product;
    #[Locked]
    public Supplier $supplier;

    public function mount($id = null){
        try{
            $this->product = Product::findOrFail($id);
            $this->name = $this->product->name;
            $this->price = $this->product->price;
            $this->description = $this->product->description;
            $this->nit = $this->product->supplier->nit;
            $data = Storage::disk('imgProduct')->get($id);
            $this->img = 'data:image/png;base64,'.base64_encode($data);
            // $this->category = $this->product->category;
        }catch(\Exception $e){
            $this->product = new Product();
        }
    }

    public function updatedNit(){
        $this->code = Supplier::where('nit', $this->nit)->exists() ? 'nf-fa-circle_check text-success' :'nf-oct-x_circle text-danger';
    }

    public function save(){
        $this->product->name = $this->name;
        $this->product->price = $this->price;
        $this->product->description = $this->description;
        // $this->product->category = $this->category;
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
        return view('livewire.product-form');
    }
}
