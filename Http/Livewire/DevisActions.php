<?php

namespace Modules\DevisAutoCar\Http\Livewire;

use Livewire\Component;
use Modules\CoreCRM\Contracts\Entities\DevisEntities;

class DevisActions extends Component
{

    public $devis;
    public $client;
    public $dossier;

    public function mount(DevisEntities $devis){
        $this->devis = $devis;
        $this->dossier = $this->devis->dossier;
        $this->client = $this->dossier->client;
    }


        /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|string
     */
    public function render()
    {



        return view('devisautocar::livewire.devis-actions', [
            'state' => $this->devis->getState()
        ]);
    }
}
