<?php

namespace Modules\DevisAutoCar\Http\Livewire;

use Illuminate\Support\Str;
use Livewire\Component;
use Modules\CoreCRM\Models\Fournisseur;
use Modules\CrmAutoCar\Contracts\Service\DistanceApiContract;
use Modules\CrmAutoCar\Models\Brand;

class DevisEditProduct extends Component
{
    public $data;
    public $keyTrajet;


    public function mount($data, $trajet)
    {
        $this->data = $data;
        $this->keyTrajet = $trajet;
    }


    public function render()
    {
        $fournisseurs = Fournisseur::all();
        $brands = Brand::all();
        return view('devisautocar::livewire.devis-edit-product', compact('brands', 'fournisseurs'));
    }
}
