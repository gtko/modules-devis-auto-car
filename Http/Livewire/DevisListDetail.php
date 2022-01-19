<?php

namespace Modules\DevisAutoCar\Http\Livewire;

use Livewire\Component;
use Modules\CoreCRM\Contracts\Entities\DevisEntities;
use Modules\CoreCRM\Models\Client;
use Modules\CoreCRM\Models\Dossier;

class DevisListDetail extends Component
{
    public $devi;
    public $dossier;
    public $client;
    public $brands;

    public function mount(DevisEntities $devi, Client $client, Dossier $dossier, $brands)
    {
        $this->devi = $devi;
        $this->dossier = $dossier;
        $this->client = $client;
        $this->brands = $brands;
    }

    public function render()
    {
        return view('devisautocar::livewire.devis-list-detail');
    }
}
