<?php

namespace Modules\DevisAutoCar\Http\Livewire;

use Livewire\Component;
use Modules\CoreCRM\Contracts\Entities\DevisEntities;
use Modules\CrmAutoCar\Contracts\Repositories\DevisAutocarRepositoryContract;

class DuplicateDevis extends Component
{
    public $devis;

    public function mount(DevisEntities $devis)
    {
        $this->devis = $devis;
    }

    public function duplicate()
    {
        app(DevisAutocarRepositoryContract::class)->duplicate($this->devis);

        $this->emit('refreshDevi');
    }

    public function render()
    {
        return view('devisautocar::livewire.dupluicate-devis');
    }
}
