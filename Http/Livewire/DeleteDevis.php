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
        if(!$this->devis->proformat) {
            app(DevisRepositoryContract::class)->delete($this->devis);
            (new FlowCRM())->add($this->devis->dossier, new ClientDossierDevisDelete($this->devis, Auth::user()));

            session()->flash('success', 'Suppression du devis');
            return redirect()->route('dossiers.show', [$this->devis->dossier->client, $this->devis->dossier, 'tab' => 'devis']);
        }

        session()->flash('error', 'Suppression impossible, car déjà validé');
        return redirect()->route('dossiers.show', [$this->devis->dossier->client, $this->devis->dossier, 'tab' => 'devis']);

    }

    public function render()
    {
        return view('devisautocar::livewire.delete-devis');
    }
}
