<?php

use Livewire\Component;

new class extends Component {
    public $heads;
    public $page = 1;
    public $search = '';
    public $name;

    public function updatedPage(){
        $this->dispatch('tablePage'.$this->name, $this->page);
    }

    public function updatedSearch(){
        $this->dispatch('tableSearch'.$this->name, $this->search);
    }

    public function mount($heads, $name)
    {
        $this->heads = $heads;
        $this->name = $name;
    }
};
?>

<div>
    <div class="d-flex justify-content-between mb-3">
        <div class="d-flex align-items-center">
            <span>Pagina: </span>
            <input type="number" class="form-control" style="width: 80px;" wire:model.live="page"
                min="1" value="1">
        </div>
        <input  wire:model.live="search" type="text" class="form-control w-25" placeholder="Buscar...">
    </div>
    <table class="table table-striped">
        <thead>
            @foreach ($heads as $item)
                <th>{{ $item }}</th>
            @endforeach
        </thead>
        <tbody>
            {{ $slot }}
        </tbody>
        @if ($slots->has('footer'))
        <tfoot>
            {{ $slots['footer'] }}
        </tfoot>
        @endif
    </table>
    {{ $slots['paginate'] }}
</div>

