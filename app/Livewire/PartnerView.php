<?php

namespace App\Livewire;

use App\Models\Partner;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;

class PartnerView extends Component
{
    public function render()
    {
        if(!Gate::allows('partner-read'))
            abort('404');
        $heads = ['ID','CI','Nombre Completo','Contacto Principal','Acciones'];
        $config = ['columns' => [null, null, null,null, ['orderable' => false, 'searchable' => false]]];
        $data = Partner::all();
        return view('livewire.partner-view', compact(['heads','config','data']));
    }
}
