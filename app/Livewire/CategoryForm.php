<?php

namespace App\Livewire;

use App\Models\Category;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CategoryForm extends Component
{
    public $listeners = ['remove' => 'remove'];

    #[Validate('required|string|unique:categories,alias')]
    public $alias;
    #[Validate('required|string')]
    public $name;

    #[Locked]
    public Category $category;

    public $status = 0;

    public function mount($id = null){
        if(!Gate::allows('config-permission',3))
            abort('404');
        try {
            $this->category = Category::where('id',$id)->firstOrFail();
            $this->alias = $this->category->alias;
            $this->name = $this->category->name;
            $this->status = 1;
        } catch (\Exception) {
            $this->category = new Category();
        }
    }

    public function save(){
        if(!Gate::allows('config-permission',3))
            abort('404');
        $this->validate();
        $this->category->name = $this->name;
        $this->category->alias = $this->alias;
        $this->category->save();
        return $this->redirect(route('dashboard.settings'));
    }

    public function remove(){
        if(!Gate::allows('config-permission',3))
            abort('404');
        $this->category->delete();
        return $this->redirect(route('dashboard.settings'));
    }

    public function render()
    {
        if(!Gate::allows('config-permission',3))
            abort('404');
        return view('livewire.category-form');
    }
}
