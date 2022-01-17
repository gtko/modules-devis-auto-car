<?php

namespace Modules\DevisAutoCar\Http\Livewire;

use Livewire\Component;
use Modules\CoreCRM\Contracts\Repositories\DevisRepositoryContract;
use Modules\DevisAutoCar\Models\Devi;

class DeleteDevis extends Component
{
    public $devis;

    public function mount(Devi $devis)
    {
        $this->devis = $devis;
    }

    public function delete()
    {
        app(DevisRepositoryContract::class)->delete($this->devis);
    }
    public function render()
    {
        return view('devisautocar::livewire.delete-devis');
    }
}
