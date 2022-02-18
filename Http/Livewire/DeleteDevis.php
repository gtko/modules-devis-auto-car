<?php

namespace Modules\DevisAutoCar\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Modules\CoreCRM\Contracts\Repositories\DevisRepositoryContract;
use Modules\CoreCRM\Flow\Attributes\ClientDossierDevisDelete;
use Modules\CoreCRM\Flow\Attributes\ClientDossierNoteCreate;
use Modules\CoreCRM\Services\FlowCRM;
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
        (new FlowCRM())->add($this->devis->dossier, new ClientDossierDevisDelete($this->devis, Auth::user()));
        $this->emit('refreshDevi');
    }

    public function render()
    {
        return view('devisautocar::livewire.delete-devis');
    }
}
