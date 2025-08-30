<?php

namespace App\Livewire;

use App\Models\Category;
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

    public function mount($id = null){
        try {
            $this->category = Category::where('id',$id)->firstOrFail();
            $this->alias = $this->category->alias;
            $this->name = $this->category->name;
        } catch (\Exception) {
            $this->category = new Category();
        }
    }

    public function save(){
        $this->validate();
        $this->category->name = $this->name;
        $this->category->alias = $this->alias;
        $this->category->save();
        return $this->redirect(route('dashboard.settings'));
    }

    public function remove(){
        $this->category->delete();
        return $this->redirect(route('dashboard.settings'));
    }

    public function render()
    {
        return view('livewire.category-form');
    }
}
